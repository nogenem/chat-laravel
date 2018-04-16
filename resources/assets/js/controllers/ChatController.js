import Echo from "laravel-echo";
import io from "socket.io-client";
import ChatUsersController from "./ChatUsersController";

class ChatController {
  constructor() {
    this.chatUsersController = new ChatUsersController(this);

    this.chatContainer = document.getElementById("chat-container");
    this.chat = this.chatContainer.querySelector(".chat");
    this.chatRow = document.getElementById("chat-row");
    this.textarea = document.getElementById("chat-message");
    this.chatAudio = document.getElementById("chat-audio");

    this.messages = [];
    this.userId = -1;
    this.talkingToId = -1;
    this.sendingMsg = false;

    this.onSendMessage = this.onSendMessage.bind(this);
    this.getMessageLi = this.getMessageLi.bind(this);
  }

  init() {
    this.textarea.addEventListener("keydown", this.onSendMessage);

    window.io = io;
    window.Echo = new Echo({
      broadcaster: "socket.io",
      host: `${window.location.hostname}:6001`
    });

    this.chatUsersController.init();
    this.startEchoListeners();
  }

  startEchoListeners() {
    window.Echo.private(`chat.${this.userId}`).listen("NewMessage", data => {
      const msg = data.message;
      if (msg.from === this.talkingToId) {
        this.addMessageToChat(msg);
      } else {
        this.chatUsersController.updateUnreadMessages({
          from: msg.from,
          toDelete: false
        });
      }
      this.chatUsersController.displayLastMsg(msg);
      this.chatAudio.play();
    });
  }

  onMessagesReceived(msgs, talkingToId) {
    if (msgs === null) {
      // Error
      this.messages = [];
      this.talkingToId = -1;
      this.chatContainer.style.display = "none";
    } else {
      this.talkingToId = talkingToId;
      this.messages = msgs;
      this.showMessages();
    }
  }

  onSendMessage(e) {
    if ((e.key === "Enter" || e.keyCode === 13) && !e.shiftKey) {
      e.preventDefault();
      e.stopPropagation();

      if (this.sendingMsg) return;

      this.textarea.setAttribute("disabled", true);
      this.sendingMsg = true;
      axios
        .post("/chat/sendMessage", {
          body: this.textarea.value,
          to: this.talkingToId
        })
        .then(resp => {
          this.addMessageToChat(resp.data);
          this.textarea.value = "";
          this.textarea.removeAttribute("disabled");
          this.sendingMsg = false;
        })
        .catch(err => {
          console.error(err.response ? err.response : err);
          this.textarea.removeAttribute("disabled");
          this.sendingMsg = false;
        });
    }
  }

  getMessageLi(msg) {
    const friend = this.talkingToId;
    return `<li class="chat__msg ${
      msg.from === friend ? "chat__msg--friend" : "chat__msg--me"
    }">${msg.body}</li>`;
  }

  showMessages() {
    this.chatUsersController.updateUnreadMessages({
      from: this.talkingToId,
      toDelete: true
    });
    const lis = this.messages.map(this.getMessageLi);

    this.chat.innerHTML = lis.join("");
    this.chatContainer.style.display = "block";
    this.chatRow.scrollTop = this.chatRow.scrollHeight;
  }

  addMessageToChat(msg) {
    if (Array.isArray(msg)) return;

    this.messages.push(msg);
    this.chat.innerHTML += this.getMessageLi(msg);
    this.chatRow.scrollTop = this.chatRow.scrollHeight;
  }
}

export default ChatController;

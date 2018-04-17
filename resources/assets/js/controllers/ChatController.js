import Echo from "laravel-echo";
import io from "socket.io-client";
import ChatUsersController from "./ChatUsersController";
import { USER_TYPE, GROUP_TYPE } from "../constants";

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
    this.talkingToType = "";
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

      let fromId = msg.from;
      let fromType = USER_TYPE;
      if (msg.to_type === GROUP_TYPE) {
        fromId = msg.to_id;
        fromType = msg.to_type;
      }

      if (fromId === this.talkingToId) {
        this.addMessageToChat(msg);
      } else {
        this.chatUsersController.updateUnreadMessages({
          fromId,
          fromType,
          toDelete: false
        });
      }
      this.chatUsersController.displayLastMsg(msg);
      this.chatAudio.play();
    });
  }

  onMessagesReceived(msgs, talkingToId, talkingToType) {
    if (msgs === null) {
      // Error
      this.messages = [];
      this.talkingToId = -1;
      this.talkingToType = "";
      this.chatContainer.style.display = "none";
    } else {
      this.talkingToId = talkingToId;
      this.talkingToType = talkingToType;
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
          to_id: this.talkingToId,
          to_type: this.talkingToType
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
    const me = this.userId;
    return `<li class="chat__msg ${
      msg.from !== me ? "chat__msg--friend" : "chat__msg--me"
    }">${msg.body}</li>`;
  }

  showMessages() {
    this.chatUsersController.updateUnreadMessages({
      fromId: this.talkingToId,
      fromType: this.talkingToType,
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

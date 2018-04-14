import Echo from "laravel-echo";
import io from "socket.io-client";

class ChatController {
  constructor() {
    this.usersContainer = document.getElementById("chat-users-container");
    this.chatContainer = document.getElementById("chat-container");
    this.chat = this.chatContainer.querySelector(".chat");
    this.chatRow = document.getElementById("chat-row");
    this.textarea = document.getElementById("chat-message");

    this.messages = [];
    this.myId = -1;
    this.talkingToId = -1;

    this.onUserPanelClicked = this.onUserPanelClicked.bind(this);
    this.onMessageSend = this.onMessageSend.bind(this);
    this.showMessages = this.showMessages.bind(this);
    this.addMessageToChat = this.addMessageToChat.bind(this);
    this.startEchoListeners = this.startEchoListeners.bind(this);
  }

  init() {
    const ul = this.usersContainer.getElementsByTagName("ul")[0];

    this.myId = +ul.dataset.myUserid;
    ul.addEventListener("click", this.onUserPanelClicked);
    this.textarea.addEventListener("keydown", this.onMessageSend);

    window.io = io;
    window.Echo = new Echo({
      broadcaster: "socket.io",
      host: `${window.location.hostname}:6001`
    });

    this.startEchoListeners();
  }

  startEchoListeners() {
    window.Echo.private(`chat.${this.myId}`).listen("NewMessage", data => {
      const msg = data.message;
      if (msg.from === this.talkingToId) {
        this.addMessageToChat(msg);
      }
    });
  }

  onUserPanelClicked(e) {
    e.preventDefault();
    e.stopPropagation();

    const li = this.getFirstLi(e);
    if (!li) return;

    const userId = li.dataset.id;
    axios
      .get(`/chat/getMessagesWith/${userId}`)
      .then(resp => {
        this.messages = resp.data;
        this.talkingToId = +userId;
        this.showMessages();
      })
      .catch(err => {
        console.error(err);
        this.messages = [];
        this.talkingToId = -1;
        this.chatContainer.style.display = "none";
      });
  }

  getFirstLi(e) {
    let node = e.target;
    while (node && node.nodeName !== "LI") {
      node = node.parentNode;
    }
    return node;
  }

  onMessageSend(e) {
    if ((e.key === "Enter" || e.keyCode === 13) && !e.shiftKey) {
      e.preventDefault();
      e.stopPropagation();

      this.textarea.setAttribute("disabled", true);
      axios
        .post("/chat/sendMessage", {
          body: this.textarea.value,
          to: this.talkingToId
        })
        .then(resp => {
          this.addMessageToChat(resp.data);
          this.textarea.value = "";
          this.textarea.removeAttribute("disabled");
        })
        .catch(err => {
          console.error(err.response ? err.response : err);
        });
    }
  }

  showMessages() {
    const friend = this.talkingToId;
    const lis = this.messages.map(
      msg =>
        `<li class="chat__msg ${
          msg.from === friend ? "chat__msg--friend" : "chat__msg--me"
        }">${msg.body}</li>`
    );

    this.chat.innerHTML = lis.join("");
    this.chatContainer.style.display = "block";
    this.chatRow.scrollTop = this.chatRow.scrollHeight;
  }

  addMessageToChat(msg) {
    if (Array.isArray(msg)) return;

    const friend = this.talkingToId;

    this.messages.push(msg);
    this.chat.innerHTML += `<li class="chat__msg ${
      msg.from === friend ? "chat__msg--friend" : "chat__msg--me"
    }">${msg.body}</li>`;
    this.chatRow.scrollTop = this.chatRow.scrollHeight;
  }
}

export default ChatController;

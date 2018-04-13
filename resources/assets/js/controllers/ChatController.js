class ChatController {
  constructor() {
    this.usersContainer = document.getElementById("chat-users-container");
    this.chatContainer = document.getElementById("chat-container");
    this.messages = [];
    this.talkingToId = -1;

    this.onUserPanelClicked = this.onUserPanelClicked.bind(this);
    this.showMessages = this.showMessages.bind(this);
  }

  init() {
    this.usersContainer
      .getElementsByTagName("ul")[0]
      .addEventListener("click", this.onUserPanelClicked);
  }

  onUserPanelClicked(e) {
    e.preventDefault();
    e.stopPropagation();

    let li = null;
    const { path } = e;
    for (let i = 0; i < path.length; i++) {
      if (path[i].nodeName === "LI") {
        li = path[i];
        break;
      }
    }

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

  showMessages() {
    const chat = this.chatContainer.querySelector(".chat");
    const friend = this.talkingToId;
    const lis = this.messages.map(
      msg =>
        `<li class="chat__msg ${
          msg.from === friend ? "chat__msg--friend" : "chat__msg--me"
        }">${msg.body}</li>`
    );

    chat.innerHTML = lis.join("");
    this.chatContainer.style.display = "block";
  }
}

export default ChatController;

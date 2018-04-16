const formatDate = dateStr => {
  const date = new Date(dateStr);
  const d = `0${date.getDate()}`.slice(-2);
  const m = `0${date.getMonth() + 1}`.slice(-2);

  return `${date.getFullYear()}-${m}-${d}`;
};

class ChatUsersController {
  constructor(chatController) {
    this.chatController = chatController;
    this.usersContainer = document.getElementById("chat-users-container");

    this.unreadMessages = {};

    this.onUserPanelClicked = this.onUserPanelClicked.bind(this);
  }

  init() {
    const ul = this.usersContainer.getElementsByTagName("ul")[0];

    this.chatController.userId = +ul.dataset.myUserid;
    ul.addEventListener("click", this.onUserPanelClicked);

    this.startEchoListeners();
  }

  startEchoListeners() {
    Echo.join("Online")
      .here(users => {
        this.updateOnlineUsers(users);
      })
      .joining(user => {
        this.setOnline(user.id);
      })
      .leaving(user => {
        this.setOffline(user.id);
      });
  }

  updateOnlineUsers(users) {
    users.forEach(user => {
      this.setOnline(user.id);
    });
  }

  setOnline(id) {
    const icon = this.usersContainer.querySelector(
      `li[data-id="${id}"] .online-status-icon`
    );

    if (icon && !icon.classList.contains("green-text")) {
      icon.classList.add("green-text");
    }
  }

  setOffline(id) {
    const icon = this.usersContainer.querySelector(
      `li[data-id="${id}"] .online-status-icon`
    );

    if (icon && icon.classList.contains("green-text")) {
      icon.classList.remove("green-text");
    }
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
        this.chatController.onMessagesReceived(resp.data, +userId);
      })
      .catch(err => {
        console.error(err);
        this.chatController.onMessagesReceived(null, -1);
      });
  }

  getFirstLi(e) {
    let node = e.target;
    while (node && node.nodeName !== "LI") {
      node = node.parentNode;
    }
    return node;
  }

  updateUnreadMessages({ from, toDelete }) {
    if (toDelete) {
      if (!this.unreadMessages[from]) return;
      this.unreadMessages[from] = null;
    } else {
      if (!this.unreadMessages[from]) this.unreadMessages[from] = 0;
      this.unreadMessages[from] += 1;
    }

    const n = this.unreadMessages[from];
    const badge = this.usersContainer.querySelector(
      `li[data-id="${from}"] span.round-badge`
    );
    const { display } = badge.style;

    badge.textContent = !n ? "" : n;
    if (!n && display === "inline-block") badge.style.display = "none";
    if (n && display === "none") badge.style.display = "inline-block";
  }

  displayLastMsg(msg) {
    // msg text
    const span = this.usersContainer.querySelector(
      `li[data-id="${msg.from}"] span.last-message`
    );

    span.innerHTML = msg.body;
    if (span.style.display === "none") span.style.display = "block";

    // msg date
    const div = this.usersContainer.querySelector(
      `li[data-id="${msg.from}"] div.last-message-date`
    );
    const date = formatDate(msg.created_at);

    div.innerHTML = date;
    if (div.style.display === "none") div.style.display = "block";
  }
}

export default ChatUsersController;

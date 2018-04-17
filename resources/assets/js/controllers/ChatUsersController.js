import { USER_TYPE, GROUP_TYPE } from "../constants";

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

    this.onGroupPanelClicked = this.onGroupPanelClicked.bind(this);
    this.onUserPanelClicked = this.onUserPanelClicked.bind(this);
  }

  init() {
    const uls = this.usersContainer.getElementsByTagName("ul");

    uls[0].addEventListener("click", this.onGroupPanelClicked);

    this.chatController.userId = +uls[1].dataset.myUserid;
    uls[1].addEventListener("click", this.onUserPanelClicked);

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
      `li[data-user-id="${id}"] .online-status-icon`
    );

    if (icon && !icon.classList.contains("green-text")) {
      icon.classList.add("green-text");
    }
  }

  setOffline(id) {
    const icon = this.usersContainer.querySelector(
      `li[data-user-id="${id}"] .online-status-icon`
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

    const { userId } = li.dataset;
    axios
      .get(`/chat/getMessagesWithUser/${userId}`)
      .then(resp => {
        this.chatController.onMessagesReceived(resp.data, +userId, USER_TYPE);
      })
      .catch(err => {
        console.error(err);
        console.error(Object.values(err));
        this.chatController.onMessagesReceived(null);
      });
  }

  onGroupPanelClicked(e) {
    e.preventDefault();
    e.stopPropagation();

    const li = this.getFirstLi(e);
    if (!li) return;

    const { groupId } = li.dataset;
    axios
      .get(`/chat/getMessagesWithGroup/${groupId}`)
      .then(resp => {
        this.chatController.onMessagesReceived(resp.data, +groupId, GROUP_TYPE);
      })
      .catch(err => {
        console.error(err);
        console.error(Object.values(err));
        this.chatController.onMessagesReceived(null);
      });
  }

  getFirstLi(e) {
    let node = e.target;
    while (node && node.nodeName !== "LI") {
      node = node.parentNode;
    }
    return node;
  }

  updateUnreadMessages({ fromId, fromType, toDelete }) {
    const fromKey = `${fromId}_${fromType}`;
    if (toDelete) {
      if (!this.unreadMessages[fromKey]) return;
      this.unreadMessages[fromKey] = null;
    } else {
      if (!this.unreadMessages[fromKey]) this.unreadMessages[fromKey] = 0;
      this.unreadMessages[fromKey] += 1;
    }

    const n = this.unreadMessages[fromKey];
    const dataAttr = this.getDataAttr(fromId, fromType);
    const badge = this.usersContainer.querySelector(
      `li[${dataAttr}] span.round-badge`
    );
    const { display } = badge.style;

    badge.textContent = !n ? "" : n;
    if (!n && display === "inline-block") badge.style.display = "none";
    if (n && display === "none") badge.style.display = "inline-block";
  }

  displayLastMsg(msg) {
    const dataAttr = this.getDataAttr(
      msg.to_type === USER_TYPE ? msg.from : msg.to_id,
      msg.to_type
    );

    // msg text
    const span = this.usersContainer.querySelector(
      `li[${dataAttr}] span.last-message`
    );

    span.innerHTML = msg.body;
    if (span.style.display === "none") span.style.display = "block";

    // msg date
    const div = this.usersContainer.querySelector(
      `li[${dataAttr}] div.last-message-date`
    );
    const date = formatDate(msg.created_at);

    div.innerHTML = date;
    if (div.style.display === "none") div.style.display = "block";
  }

  getDataAttr(id, type) {
    return type === USER_TYPE
      ? `data-user-id="${id}"`
      : `data-group-id="${id}"`;
  }
}

export default ChatUsersController;

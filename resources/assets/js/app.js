import ChatController from "./controllers/ChatController";

require("./bootstrap");

const elem = document.querySelector(".sidenav");
M.Sidenav.init(elem, {});

if (window.location.pathname.startsWith("/chat")) {
  const controller = new ChatController();
  controller.init();
}

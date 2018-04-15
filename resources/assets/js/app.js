import ChatController from "./controllers/ChatController";

require("./bootstrap");

const sidenav = document.querySelector(".sidenav");
M.Sidenav.init(sidenav, {});
const tooltips = document.querySelectorAll(".tooltipped");
M.Tooltip.init(tooltips, {});

if (window.location.pathname.startsWith("/chat")) {
  const controller = new ChatController();
  controller.init();
}

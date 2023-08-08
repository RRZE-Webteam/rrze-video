/**
 * RRZE-Video Plugin: Front Dependencies
 */
import Plyr from "plyr";
import "plyr/src/sass/plyr.scss";
import "./custom.scss";

const getVideoTitle = (player) => {
  const container = player.elements?.container;
  if (!container) return null;
  const potentialTitleElem = container.nextSibling?.nextSibling;
  if (!potentialTitleElem || potentialTitleElem.id === undefined) return null;
  return document.getElementById(potentialTitleElem.id);
};

document.addEventListener("DOMContentLoaded", function () {
  try {
    const players = Plyr.setup(".plyr-instance", {
      fullscreen: { iosNative: true },
    });

    players.forEach((player) => {
      const videoTitle = getVideoTitle(player);
      if (!videoTitle) return;

      videoTitle.style.zIndex = "1";
      videoTitle.classList.remove("rrze-video-hide");

      player.on("play", function () {
        videoTitle.classList.add("rrze-video-hide");
      });

      player.on("pause", function () {
        videoTitle.classList.remove("rrze-video-hide");
        videoTitle.style.opacity = "1"; // Reset opacity to 1 immediately to make fadeIn work correctly
      });

      const isIOSWithWebkit =
        "webkitEnterFullscreen" in document.createElement("video");
      if (isIOSWithWebkit) {
        ["webkitbeginfullscreen", "webkitendfullscreen"].forEach((event) => {
          player.media.addEventListener(event, (e) => {
            if (e.type === "webkitbeginfullscreen") {
              document.documentElement.style.setProperty(
                "--webkit-text-track-display",
                "block"
              );
            } else {
              document.documentElement.style.setProperty(
                "--webkit-text-track-display",
                "none"
              );
            }
          });
        });
      }
    });
  } catch (error) {
    console.error("Error in rrze-video/src/front/index.js: ", error);
  }
});

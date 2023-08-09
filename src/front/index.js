/**
 * RRZE-Video Plugin: Front Dependencies
 */
import Plyr from "plyr";
import "plyr/src/sass/plyr.scss";
import "./custom.scss";

const isIOSWithWebkit = "webkitEnterFullscreen" in document.createElement("video");
const VIDEO_CONTAINER_PREFIX = "rrze-video-container-";
const VIDEO_TITLE_PREFIX_LENGTH = VIDEO_CONTAINER_PREFIX.length;

const handlePlayerPlay = (videoTitle) => {
  videoTitle.classList.add("rrze-video-hide");
};

const handlePlayerPause = (videoTitle) => {
  videoTitle.classList.remove("rrze-video-hide");
  videoTitle.style.opacity = "1"; // Reset opacity to 1 for fadeIn effect
};

document.addEventListener("DOMContentLoaded", () => {
  try {
    console.log("Successfully loaded front.js for rrze-video.");
    const players = Plyr.setup(".plyr-instance", {
      fullscreen: { iosNative: true },
    });

    players.forEach((player) => {
      try {
        const parentElementClass =
          player?.elements?.container?.parentElement?.classList[1];

        if (
          parentElementClass &&
          parentElementClass.startsWith(VIDEO_CONTAINER_PREFIX)
        ) {
          const videoTitleId = `rrze-video-title-${parentElementClass.slice(
            VIDEO_TITLE_PREFIX_LENGTH
          )}`;
          const videoTitle = document.getElementById(videoTitleId);

          if (videoTitle) {
            videoTitle.style.zIndex = "1";
            videoTitle.classList.remove("rrze-video-hide");

            player.on("play", () => handlePlayerPlay(videoTitle));
            player.on("pause", () => handlePlayerPause(videoTitle));
          } else {
            console.log(
              `Video Title not found or disabled for video with id: ${videoTitleId}`
            );
          }
        } else {
          console.log(
            "No matching parent class inside the Plyr video container. Check the template."
          );
        }

        if (isIOSWithWebkit) {
          const handleFullscreenChange = (e) => {
            const displayMode =
              e.type === "webkitbeginfullscreen" ? "block" : "none";
            document.documentElement.style.setProperty(
              "--webkit-text-track-display",
              displayMode
            );
          };

          ["webkitbeginfullscreen", "webkitendfullscreen"].forEach((event) => {
            player.media.addEventListener(event, handleFullscreenChange);
          });
        }
      } catch (playerError) {
        console.error(`Error processing player: ${playerError}`);
      }
    });
  } catch (generalError) {
    console.error("Error in rrze-video/src/front/index.js: ", generalError);
  }
});

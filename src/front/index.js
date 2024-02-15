import Plyr from "plyr";
import "plyr/src/sass/plyr.scss";
import "./custom.scss";

const debounce = (func, delay = 300) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func.apply(this, args), delay);
  };
};

const isIOSWithWebkit =
  "webkitEnterFullscreen" in document.createElement("video");
const VIDEO_CONTAINER_PREFIX = "rrze-video-container-";
const VIDEO_TITLE_PREFIX_LENGTH = VIDEO_CONTAINER_PREFIX.length;

const SELECTORS = {
  CONTROLS: [
    'button[data-plyr="pip"]',
    'button[data-plyr="captions"]',
    'button[data-plyr="airplay"]',
    'input[data-plyr="volume"]',
  ].join(","),
  MICRO_CONTROLS: ".plyr__controls__item.plyr__progress__container",
  UI_ELEMENTS: "p.rrze-video-title",
};

const handlePlayerPlay = (videoTitle) =>
  videoTitle.classList.add("rrze-video-hide");
const handlePlayerPause = (videoTitle) =>
  videoTitle.classList.remove("rrze-video-hide");

const adjustControls = (player) => {
  const playerWidth = player?.elements?.container?.clientWidth || 1100;
  const playerContainer = player?.elements?.container;
  const videoTitle = playerContainer?.nextElementSibling;

  if (videoTitle && videoTitle.matches("p.rrze-video-title")) {
    videoTitle.classList[playerWidth <= 450 ? "add" : "remove"]("minified");
    videoTitle.classList[playerWidth <= 450 ? "add" : "remove"]("cropped");
  }

  const controls = Array.from(
    playerContainer.querySelectorAll(SELECTORS.CONTROLS)
  );
  const microControls = Array.from(
    playerContainer.querySelectorAll(SELECTORS.MICRO_CONTROLS)
  );

  controls.forEach((control) =>
    control.classList[playerWidth <= 450 ? "add" : "remove"](
      "rrze-video-display-none"
    )
  );
  microControls.forEach((control) =>
    control.classList[playerWidth <= 230 ? "add" : "remove"](
      "rrze-video-display-none"
    )
  );
};

document.addEventListener("DOMContentLoaded", () => {
  try {
    console.log("Successfully loaded front.js for rrze-video.");

    const players = Plyr.setup(".plyr-instance", {
      fullscreen: { iosNative: true },
    });

    let vidConfig = [];
    players.forEach((player, index) => {
      adjustControls(player);
      // Directly use index to manage unique properties
      let propertyName = `rrzeVideoPluginData${index + 1}`; // Assuming your PHP data starts with 1
      if (window[propertyName] && window[propertyName].plyrconfigJS) {
        vidConfig[index] = JSON.parse(window[propertyName].plyrconfigJS); // Store config by index
      }

      const playerConfig = vidConfig[index];
      const playerID = parseInt(playerConfig["id"] || 0);
      if (playerConfig["loop"] && playerID === index + 1) {
        player.loop = true;
      }

      const parentElementClass =
        player?.elements?.container?.parentElement?.classList[1];
      const videoTitleId = `rrze-video-title-${parentElementClass.slice(
        VIDEO_TITLE_PREFIX_LENGTH
      )}`;
      const videoTitle = document.getElementById(videoTitleId);

      let skipped = false; // Initialize skipped flag for each player
      player.on("canplay", function () {
        const startTime = parseFloat(playerConfig["start"] || "0");
        if (!skipped && startTime > 0) {
          player.currentTime = startTime;
          skipped = true;
        }
      });

      if (playerConfig["loop"]) {
        player.on("timeupdate", function () {
          let maximumTime = player.duration - parseFloat(playerConfig["clipend"]);
         if (player.currentTime >= maximumTime) {
            player.currentTime = parseFloat(playerConfig["clipstart"]);
          }
        });
      }

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
    });

    window.addEventListener(
      "resize",
      debounce(() => {
        players?.forEach((player) => adjustControls(player));
      }, 500)
    );
  } catch (generalError) {
    console.error("Error in rrze-video/src/front/index.js: ", generalError);
  }
});

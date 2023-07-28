/**
 * RRZE-Video Plugin: Front Dependencies
 */
import Plyr from "plyr";
import "plyr/src/sass/plyr.scss";
import "./custom.scss";

document.addEventListener("DOMContentLoaded", function () {
    try {
      const players = Plyr.setup(".plyr-instance");
  
      players.forEach((player) => {
        let videoTitleId = player.elements?.container?.nextSibling?.nextSibling?.id;
        let videoTitle = document.getElementById(videoTitleId);
        
        if (videoTitle === null) {
          return;
        }
        
        videoTitle.classList.remove("rrze-video-hide");
  
        player.on("play", function () {
          videoTitle.classList.add("rrze-video-hide");
        });
  
        player.on("pause", function () {
          videoTitle.classList.remove("rrze-video-hide");
          videoTitle.style.opacity = "1"; // Reset opacity to 1 immediately to make fadeIn work correctly
        });
      });
    } catch (error) {
      console.error("Error in rrze-video/src/front/index.js: ", error);
    }
});

/**
 * RRZE-Video Plugin: Front Dependencies
 */
import Plyr from "plyr";
import "plyr/src/sass/plyr.scss";
import "./custom.scss";

try{
const players = Plyr.setup(".plyr-instance");
console.log("I am here!");

players.forEach((player, index) => {
    index = index + 2;

    player.on('play', function() {
        let videoTitle = document.getElementById(`rrze-video-title-${index}`);
        if (videoTitle !== null) {
            videoTitle.classList.add('rrze-video-hide');
        }
    });

    player.on('pause', function() {
        let videoTitle = document.getElementById(`rrze-video-title-${index}`);
        if (videoTitle !== null) {
            videoTitle.classList.remove('rrze-video-hide');
            videoTitle.style.opacity = '1'; // Reset opacity to 1 immediately to make fadeIn work correctly
        }
    });
});
} catch (error) {
    console.error('Error in rrze-video/src/front/index.js: ', error);
}
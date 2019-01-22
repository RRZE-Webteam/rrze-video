<?php

namespace RRZE\PostVideo;

function get_js_player_action(){
    // dummy callback from wp_ajax api
    // empty on purpose.
}

function js_player_ajax()
{
    $players = array(
        'mediaelement',
        'youtube',
        'fauvideo'
    );
    ?>
    <script type="text/javascript" >
        jQuery(document).ready(function($){
<?php
    foreach( $players as $player) {
?>
            $('a[data-player-type="<?php echo $player; ?>"]').click(function(){

                var video_id  = $(this).attr('data-video-id');
                var id        = $(this).attr('data-box-id');
                var video_url = $(this).attr('data-video-url');     // nur bei FAU video?
                var poster    = $(this).attr('data-preview-image'); // nur bei FAU video?

                $.ajax({
                    url: videoajax.ajaxurl,
                    data: {
                        'action'    : 'get_js_player_action',
                        'video_id'  : video_id,
                        'id'        : id,
                        'poster'    : poster,
                        'video_url' : video_url
                    },
                    success: function(data){
<?php
    switch( $player ) {
        case 'mediaelement' :
?>
                    var video = '<video class="player" width="640" height="360" controls="controls" preload="none">' +
                        '<source src="https://www.youtube.com/watch?v=' + video_id + '" type="video/youtube" />' +
                        '</video>';
                        $(".videocontent" + id)
                        .html(video)
                        .find(".player")
                        .mediaelementplayer({
                        alwaysShowControls: true,
                            features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
                        });
<?php
            break;
        case 'youtube' :
?>
                    var iframe = document.createElement("iframe");
                        iframe.setAttribute("frameborder", "0");
                        iframe.setAttribute("allowfullscreen", "");
                        iframe.setAttribute("src", "https://www.youtube.com/embed/" + video_id + "?rel=0&showinfo=0");

                        $(".embed-container" + id)
                            .html(iframe)
                                .find(".youtube-video"); // <-- ??
<?php
            break;
        case 'fauvideo' :
?>
                     var video = '<video class="player img-responsive center-block" style="width:100%;height:100%;" width="639" height="360" poster="' + poster + '" controls="controls" preload="none">' +
                            '<source src="' + video_url + '" type="video/mp4" />' + '</video>';
                        $(".videocontent" + id)
                            .html(video)
                                .find(".player")
                                    .mediaelementplayer({
                                        alwaysShowControls: true,
                                        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
                                    });
<?php
    }
?>
                   },
                    error: function(errorThrown){
                        window.console.log(errorThrown);
                    }
                });

            });
<?php
    } // endforeach;
?>
        });
    </script><?php
}

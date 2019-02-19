<?php

namespace RRZE\PostVideo;

function get_js_player_action()
{
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
            $('a[data-player-type="<?php echo $player; ?>"]').click(function(e){

                e.preventDefault();

                var me         = $(this);
                var video_id   = me.attr('data-video-id');
                var id         = me.attr('data-box-id');
                var video_url  = me.attr('data-video-url');     // nur bei FAU video?
                var poster     = me.attr('data-preview-image'); // nur bei FAU video?
                var stage_w    = $('#video-thumbnail'+id).width();
                var document_w = $(document).width();

                var target     = ".videocontent" + id;
                if( stage_w >= 640 || document_w < 640 ){

                    me.attr('data-toggle',false);
                    me.attr('data-target',target);
                    target = '.video-preview' + id;

                    // remove scrolltop click on container
                    $(target).parents('.rrze-video-container').unbind('click');

                }

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
                    var embed_html = '<video class="player" width="640" height="360" controls="controls" preload="none">' +
                        '<source src="https://www.youtube.com/watch?v=' + video_id + '" type="video/youtube" />' +
                        '</video>';
                        $(target)
                            .html(embed_html)
                                .find('.player')
                                    .mediaelementplayer({
                                        alwaysShowControls: true,
                                        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
                                    });
<?php
            break;
        case 'fauvideo' :
?>
                     var embed_html = '<video class="player" width="640" height="360" controls="controls" preload="none" poster="' + poster + '">' +
                        '<source src="' + video_url + '" type="video/mp4" />' +
                        '</video>';
                        $(target)
                            .html(embed_html)
                                .find('.player')
                                    .mediaelementplayer({
                                        alwaysShowControls: true,
                                        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
                                    });
<?php
            break;
        case 'youtube' :
?>
                    var embed_html = document.createElement('iframe');
                        embed_html.setAttribute('frameborder', '0');
                        embed_html.setAttribute('allowfullscreen', '');
                        embed_html.setAttribute('src', 'https://www.youtube.com/embed/' + video_id + '?rel=0&showinfo=0');
                        $(target)
                            .html(embed_html);
<?php
    } // end switch;
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

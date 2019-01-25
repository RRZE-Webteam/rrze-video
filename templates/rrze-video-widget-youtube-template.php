<?php
    $thistitle = ( ! empty($showtitle) ) ? $showtitle : $modaltitle;
    $player_type = ( !$show_youtube_player ) ? 'mediaelement' : 'youtube';
?>
<div class="rrze-video rrze-video-widget-container">
    <h2><?php echo $thistitle; ?></h2>
    <div class="rrze-video-youtubeplayer">
        <a
            href="<?php echo 'https://www.youtube.com/watch?v=' . $video_id; ?>"
            data-player-type="<?php echo $player_type ?>"
            data-toggle="modal"
            data-box-id="<?php echo $instance_id ?>"
            data-video-id="<?php echo $video_id ?>"
            data-target="#videoModal<?php echo $instance_id ?>"
        >
	    <div class="rrze-video-widget-box box-widget<?php echo $instance_id ?>">
	        <img src="<?php echo $preview_image; ?>" title="<?php echo (!isset($modaltitle)) ? 'Preview Image' : get_the_title() ?>" alt="<?php _e('Video aufrufen') ?>" class="image<?php echo $instance_id ?>"/>
            <div class="middle" aria-hidden="true">
                <div class="play-button"><i class="fa fa-play-circle-o"></i></div>
            </div>
	    </div><!-- .rrze-video-widget-box -->
        </a>
    </div><!-- .rrze-video-youtubeplayer -->
    <div class="modal fade rrze-video-modal" id="videoModal<?php echo $instance_id ?>" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">
	        <div class="modal-content">
                <div class="modal-header">
                    <div class="close-modal" data-dismiss="modal">
                        <em class="fa fa-times" aria-hidden="true"></em>
                    </div>
                    <?php echo ( isset($modaltitle) ) ? '<h2 class="widget-title">' . $modaltitle . '</h2>' : '' ?>
                </div>
                <div class="modal-body">
                    <div class="videocontent<?php echo $instance_id ?>">
                        <?php if ( ! $show_youtube_player ) { ?>
                        <div class="player">
                            <img src="<?php echo $preview_image; ?>" title="<?php echo (!isset($modaltitle)) ? 'Preview Image' : get_the_title() ?>" alt="<?php _e('Video aufrufen') ?>" class="image<?php echo $instance_id ?>"/>
                        </div>
                        <?php } else { ?>
                        <div class="embed-container<?php echo $instance_id ?>">
                            <div class="youtube-video"></div>
                        </div>
                        <?php } ?>
                    </div><!-- video-content -->
                </div><!-- .modal-body -->
                <div class="modal-footer">
                    <p><?php echo ( isset($desc) ) ? $desc : '' ?></p>
                </div><!-- .modal-footer -->
	        </div><!-- .modal-content -->
	    </div><!-- .modal-dialog -->
    </div><!-- .modal -->
</div><!-- .rrze-video-widget-container -->

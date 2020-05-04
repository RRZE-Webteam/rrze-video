<?php
    $video_title = '';
    if (!empty($showtitle)) {
        $video_title = '<' . $rrze_video_shortcode['titletag'] . '>' . $showtitle . '</' . $rrze_video_shortcode['titletag'] . '>';
    }
    $player_type ='mediaelement';
    if ($show_youtube_player) {
	$player_type = 'youtube';
    }
?>
<div class="rrze-video">
    <?php echo $video_title; ?>
    <div class="rrze-video-container rrze-video-id-<?php echo $instance_id ?>">
        <div class="<?php echo $player_type ?> video-preview<?php echo $instance_id ?>" id="video-preview<?php echo $instance_id ?>">
            <a
                href="<?php echo 'https://www.youtube.com/watch?v=' . $video_id; ?>"
                data-player-type="<?php echo $player_type ?>"
                data-box-id="<?php echo $instance_id ?>"
                data-video-id="<?php echo $video_id ?>"
                data-toggle="modal"
                data-target="#videoModal<?php echo $instance_id ?>"
            >
            <img id="video-thumbnail<?php echo $instance_id ?>" src="<?php echo $preview_image; ?>" title="<?php echo (!isset($modaltitle)) ? 'Preview Image' : get_the_title() ?>" alt="Video aufrufen" class="image<?php echo $instance_id ?>"/>
            <div class="middle" aria-hidden="true">
                <div class="play-button"><em class="fa fa-play-circle-o"></em></div>
            </div>
            </a>
        </div>
    </div>

    <div class="modal fade is_youtube rrze-video-modal" id="videoModal<?php echo $instance_id ?>" role="dialog" data-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close-modal" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                    <?php echo (!empty($modaltitle)) ? '<h2 class="modal-title">' . $modaltitle . '</h2>' : ''; ?>
                </div>
                <div class="modal-body">
                    <div class="videocontent<?php echo $instance_id ?>">
                        <div class="player">
                            <?php if(!$show_youtube_player) { ?>
                                <img title="preview_image" width="100%" src="<?php echo $preview_image; ?>" alt="Video Preview">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p><?php echo (!empty($description)) ? $description : '' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $inline_css = '';
    if ($width_shortcode != 640) {
        $inline_css = ' style="width: ' . $rrze_video_shortcode['width'] . 'px;"';
    }
    $video_title = '';
    if (!empty($showtitle)) {
        $video_title = '<' . $rrze_video_shortcode['titletag'] . '>' . $showtitle . '</' . $rrze_video_shortcode['titletag'] . '>';
    }
?>
<div class="rrze-video"<?php echo $inline_css; ?>>
    <?php echo $video_title; ?>
    <div class="rrze-video-container rrze-video-id-<?php echo $instance_id ?>">
        <?php  $player_type = ( !$show_youtube_player && $youtube_support == 0 ) ? 'mediaelement' : 'youtube'; ?>
        <a
            href="<?php echo 'https://www.youtube.com/watch?v=' . $video_id; ?>"
            data-player-type="<?php echo $player_type ?>"
            data-box-id="<?php echo $instance_id ?>"
            data-youtube-id="<?php echo $video_id ?>"
            data-toggle="modal"
            data-target="#videoModal<?php echo $instance_id ?>"
        >
        <img src="<?php echo $preview_image; ?>" title="<?php echo (!isset($modaltitle)) ? 'Preview Image' : get_the_title() ?>" alt="Video aufrufen" class="image<?php echo $instance_id ?>"/>
        <div class="middle">
            <div class="play-button">
                <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            </div>
        </div>
        </a>
    </div>

    <div class="modal fade is_youtube" id="videoModal<?php echo $instance_id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close-modal" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                    <h2 class="modal-title" style="text-align:left;padding:<?php echo (!empty($modaltitle)) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap((!empty($modaltitle)) ? $modaltitle : '', 30, "<br/>") ?></h2>
                </div>
                <div class="modal-body">
                    <div class="videocontent<?php echo $instance_id ?>">
                    <?php if(!$show_youtube_player && $youtube_support == 0) {
                        // use wp medialement
                    ?>
                        <div class="player">
                            <img title="preview_image" width="100%" src="<?php echo $preview_image; ?>" alt="Video Preview">
                        </div>
                    <?php } else {
                        // use youtube embed
                    ?>
                        <div class="embed-container<?php echo $instance_id ?>">
                            <div class="youtube-video"></div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <p><?php echo (!empty($description)) ? $description : '' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

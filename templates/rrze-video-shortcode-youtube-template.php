<?php
echo '<script type="text/javascript">';
echo 'jQuery(function($) { 
$(document).keydown(function (e) {
if (e.keyCode == 38) { $(".modal-overlay").hide();
$(".modal").hide();
$("video").trigger("pause");';
if(!$show_youtube_player and $youtube_support == 0) {
echo 'player = new YT.Player("ytplayer");';
}   
echo 'stopVideo($("#ytplayer'. $box_id . '"));}';  
echo 'function stopVideo(player) {
var vidSrc = player.prop("src");
player.prop("src", "");
player.prop("src", vidSrc);
};
});
});';
echo '</script>';
?>
<div class="rrze-video">
<?php if(!empty($showtitle)) { ?>
<<?php echo $rrze_video_shortcode['titletag'] ?>><?php echo $showtitle ?></<?php echo $rrze_video_shortcode['titletag'] ?>>
<?php } ?>
    <div class="rrze-video-container<?php echo $box_id ?>">
        <?php if(!$show_youtube_player and $youtube_support == 0) { ?>
        <a href="#get_mejs_youtube" data-box-id="<?php echo $id ?>" data-youtube-id="<?php echo $youtube_id ?>" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
        <?php } else { ?>
        <a href="#get_youtube" data-box-id="<?php echo $id ?>" data-youtube-id="<?php echo $youtube_id ?>" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
        <?php } ?>
        <?php if( $youtube_resolution == 1 ) { ?>
        <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" class="image<?php echo $box_id ?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/maxresdefault.jpg"/> <!-- width="100%" responsive maxresdefault.jpg hqdefault.jpg -->
        <?php } elseif( $youtube_resolution == 2 ) { ?>
        <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" class="image<?php echo $box_id ?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/default.jpg"/>
        <?php } elseif( $youtube_resolution == 3 ) { ?>
        <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" class="image<?php echo $box_id ?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/hqdefault.jpg"/>
        <?php } elseif( $youtube_resolution == 4 ) { ?>
        <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" class="image<?php echo $box_id ?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"/>
        <?php } else { ?>
        <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" class="image<?php echo $box_id ?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/sddefault.jpg"/>
        <?php } ?>
        <div class="middle">
            <div class="play-button">
                <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            </div>
        </div>
        </a>
    </div>
<div class="modal fade is_youtube" id="videoModal<?php echo $id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <div class="close-modal" data-dismiss="modal">
                <i class="fa fa-times" aria-hidden="true"></i>
            </div>
            <h2 class="modal-title" style="text-align:left;padding:<?php echo (!empty($modaltitle)) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap((!empty($modaltitle)) ? $modaltitle : '', 30, "<br/>") ?></h2>
        </div>
        <div class="modal-body">
            <div class="videocontent<?php echo $id ?>">
                <?php if(!$show_youtube_player and $youtube_support == 0) { ?>
                <div class="player">
                      <img title="preview_image" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"/>
                </div>
                <?php } else { ?>
                <div class="embed-container<?php echo $id ?>">
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
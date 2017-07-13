<div class="rrze-video">
<<?php echo $rrze_video_shortcode['titletag'] ?>><?php echo (!empty($showtitle)) ? $showtitle : '' ?></<?php echo $rrze_video_shortcode['titletag'] ?>>
<div>
    <a href="" data-toggle="modal" data-target="#videoModal<?php echo $id ?>" data-theVideo="https://www.youtube.com/embed/<?php echo $youtube_id ?>">
    <div class="box<?php echo $box_id ?>">
        <?php if( $youtube_resolution == 1 ) { ?>
        <img alt="" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/maxresdefault.jpg"/> <!-- width="100%" responsive maxresdefault.jpg hqdefault.jpg -->
        <?php } elseif( $youtube_resolution == 2 ) { ?>
        <img alt="" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/default.jpg"/>
        <?php } elseif( $youtube_resolution == 3 ) { ?>
        <img alt="" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/hqdefault.jpg"/>
        <?php } elseif( $youtube_resolution == 4 ) { ?>
        <img alt="" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"/>
        <?php } else { ?>
        <img alt="" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/sddefault.jpg"/>
        <?php } ?>
        <div class="overlay">
            <div class="text">
                <span class="yt-icon-shortcode">
                    <i class="fa fa-play-circle-o colored" aria-hidden="true"></i>
                </span>
            </div>
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
            <div class="videocontent">
                <video width="640" height="360" class="player" preload="none">
                    <source type="video/youtube" src="https://www.youtube.com/watch?v=<?php echo $youtube_id ?>" />
                </video>
                <!-- <iframe width="1280" height="720" src=""></iframe> -->
            </div>
        </div>
        <div class="modal-footer">
            <p><?php echo (!empty($description)) ? $description : '' ?></p>
        </div>
    </div>
</div>
</div>
</div>
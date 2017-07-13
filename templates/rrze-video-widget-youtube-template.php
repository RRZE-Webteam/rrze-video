<div class="rrze-video">
<?php  wp_enqueue_script( 'jquery' ); wp_add_inline_script( 'jquery', 
'jQuery(document).ready(function(){ 

    if ($(window).width() >= 768){	

        $( "body" ).removeClass( "is-mobile" );
        $("video").attr({ style: "height: 360px; width: 100%;" });

    } else {

        $("video").attr({ style: "height: 100%; width: 100%" });
    }

});' ); ?>
<h2 class="small"><?php echo wordwrap($showtitle, 30, "<br/>") ?></h2>
<a href="" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
<div class="box-widget<?php echo $box_id ?>">
    <?php if( $youtube_resolution == 1 ) { ?>
    <img alt="" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/maxresdefault.jpg"/> <!-- width="100%" responsive maxresdefault.jpg hqdefault.jpg -->
    <?php } elseif( $youtube_resolution == 2 ) { ?>
    <img alt="" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/default.jpg"/>
    <?php } elseif( $youtube_resolution == 3 ) { ?>
    <img alt="" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/hqdefault.jpg"/>
    <?php } elseif( $youtube_resolution == 4 ) { ?>
    <img alt=""width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"/>
    <?php } else { ?>
    <img alt="" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/sddefault.jpg"/>
    <?php } ?>
    <div class="overlay-widget">
        <div class="text">
            <span class="yt-icon-widget">
                <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            </span>
        </div>
    </div>
</div>
</a>
<div class="modal fade" id="videoModal<?php echo $id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close-modal" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <h2 class="widget-title" style="color:#000;padding:<?php echo ($modaltitle) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap($modaltitle, 30, "<br/>") ?></h2>
            </div>
            <div class="modal-body">
                <div class="videocontent">
                    <video width="640" height="360" class="player" preload="none">
                        <source type="video/youtube" src="https://www.youtube.com/watch?v=<?php echo $youtube_id ?>" />
                    </video> 
                </div>
            </div>
            <div class="modal-footer">
                <p><?php echo $description ?></p>
            </div>
        </div>
    </div>
</div>
</div>
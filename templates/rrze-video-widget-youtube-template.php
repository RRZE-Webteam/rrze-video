<div class="rrze-video">
<h2 class="small"><?php echo ($showtitle) ? wordwrap($showtitle, 50, "<br/>") : '' ?></h2>
<?php if(!$show_youtube_player) { ?>
<a href="#get_widget_mejs_youtube" data-toggle="modal"  data-box-id="<?php echo $id ?>" data-youtube-id="<?php echo $youtube_id ?>" data-target="#videoModal<?php echo $id ?>">
<?php } else { ?>
<a href="" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
<?php } ?>    
<div class="box-widget<?php echo $box_id ?>">
    <?php if( $youtube_resolution == 1 ) { ?>
    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/maxresdefault.jpg"/> <!-- width="100%" responsive maxresdefault.jpg hqdefault.jpg -->
    <?php } elseif( $youtube_resolution == 2 ) { ?>
    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/default.jpg"/>
    <?php } elseif( $youtube_resolution == 3 ) { ?>
    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/hqdefault.jpg"/>
    <?php } elseif( $youtube_resolution == 4 ) { ?>
    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"/>
    <?php } else { ?>
    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/sddefault.jpg"/>
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
                <div class="videocontent<?php echo $id ?>">
                    <?php if(!$show_youtube_player) { ?>
                    <div class="player"></div>
                    <!--<video width="640" height="360" class="player" preload="none">
                        <source type="video/youtube" src="https://www.youtube.com/watch?v=<?php echo $youtube_id ?>" />
                    </video>-->
                    <?php } else { ?>
                    <div class="embed-container">
                        <iframe frameborder="0" allowfullscreen width="640" height="360" src="https://www.youtube.com/embed/<?php echo $youtube_id ?>?rel=0&enablejsapi=1&origin=http://example.com"></iframe>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <p><?php echo $description ?></p>
            </div>
        </div>
    </div>
</div>
</div>
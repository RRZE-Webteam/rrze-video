<?php  wp_enqueue_script( 'jquery' ); wp_add_inline_script( 'jquery', 
'jQuery(document).ready(function(){ 

    if ($(window).width() >= 768){	

        $( "body" ).removeClass( "is-mobile" );
        $("video").attr({ style: "height: 360px; width: 100%;" });

    } else {

        $("video").attr({ style: "height: 100%; width: 100%" });
    }

});' ); ?>
<h2 class="small"><?php echo wordwrap($showtitle, 50, "<br/>") ?></h2>
<div class="box-widget<?php echo $box_id ?>">
    <?php if($thumbnail) {     
        //echo $picture;
        echo '<img src="' . $thumbnail[0]  . '" width="'. $instance['width'] . '" height="' . $instance['height']  . '"  />'; // width="100%" responsive
        echo '</a>';
    } else { ?>
        <img alt="<?php echo $showtitle ?>"width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"  src="<?php echo $picture ?>"/></a> <!-- width="100%" responsive  -->
    <?php }?>
    <div class="overlay-widget">
        <div class="text">
            <a href="" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
                <span class="yt-icon-widget"><i class="fa fa-play-circle-o" aria-hidden="true"></i></span>
            </a>
        </div>
    </div>
</div>
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
                    <video class="player img-responsive center-block" width="640"
                           height="360" poster="<?php echo $preview_image ?>" controls="controls" preload="none">
                        <source type="video/mp4" src="<?php echo $video_file ?>" />
                    </video>
                </div>
            </div>
            <div class="modal-footer">
                <p class="description"><?php echo (!empty($description)) ? 'Beschreibung: ' . $description : '' ?></p><br/>
                 <?php if($meta == 1) { ?>
                    <span class="meta_heading">Author: </span><span class="meta_content"><?php echo $author ?></span><br/>
                    <span class="meta_heading">Quelle: </span><span class="meta_content">
                        <a href="<?php echo $video_file ?>">Download</a>
                    </span><br />
                    <span class="meta_heading">Copyright: </span><span class="meta_content"><?php echo $copyright ?></span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
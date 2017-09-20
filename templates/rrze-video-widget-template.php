<div class="rrze-video rrze-video-widget-container">
<h2 class="small"><?php echo wordwrap($showtitle, 50, "<br/>") ?></h2>
<a href="#get_video_widget" data-id="<?php echo $id ?>" data-preview-image="<?php echo $preview_image ?>" data-video-file="<?php echo $video_file ?>" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
<div class="box-widget<?php echo $box_id ?>">
    <?php if(!empty($thumbnail)) {     
        //echo $picture;
        echo '<img title="'. $modaltitle .'" src="' . $thumbnail[0]  . '" width="'. $instance['width'] . '" height="' . $instance['height']  . '"  />'; // width="100%" responsive
    } else { ?>
        <img title="<?php echo $modaltitle ?>" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"  src="<?php echo $picture ?>"/> <!-- width="100%" responsive  -->
    <?php }?>
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
                <h2 class="modal-title" style="color:#000;padding:<?php echo ($modaltitle) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap($modaltitle, 30, "<br/>") ?></h2>
            </div>
            <div class="modal-body">
                <div class="videocontent<?php echo $id ?>">
                    <div class="player">
                        <img src="<?php echo $preview_image ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="description"><?php echo (!empty($desc)) ? 'Beschreibungs: ' . $desc : '' ?></p><br/>
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
</div>
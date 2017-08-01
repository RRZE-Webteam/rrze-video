<div class="rrze-video">
<?php if(!empty($showtitle)) { ?>
<<?php echo $rrze_video_shortcode['titletag'] ?>><?php echo $showtitle ?></<?php echo $rrze_video_shortcode['titletag'] ?>>
<?php } ?>
<div class="rrze-video-container<?php echo $box_id ?>">
    <a href="" data-toggle="modal" data-target="#videoModal<?php echo $id ?>"><?php if(!empty($thumbnail)){echo '<img src="' . $thumbnail[0]  . '" title="'. $video_url['title'] .'" class="image' . $box_id .'"  />';
    } else { ?>
    <img title="<?php echo $video_url['title'] ?>"  src="<?php echo $picture ?>" class="image<?php echo $id ?>"/>
    <?php }?>
    <div class="middle"><div class="play-button"><i class="fa fa-play-circle-o" aria-hidden="true"></i></div></div>
    </a>
</div> 
<div class="modal fade" id="videoModal<?php echo $id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close-modal" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <h2 class="modal-title" style="text-align:left;padding:<?php echo ($modaltitle) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap((!empty($modaltitle)) ? $modaltitle : '', 30, "<br/>") ?></h2>
            </div>
            <div class="modal-body">
                <div class="videocontent">
                    <video class="player img-responsive center-block" style="width:100%;height:100%;" width="639" height="360" poster="<?php echo $preview_image ?>" controls="controls" preload="none">
                        <source type="video/mp4" src="<?php echo $video_file ?>" />
                    </video>
                </div>
            </div>
             <div class="modal-footer">
                <p class="description"><?php echo (!empty($description)) ? 'Beschreibung: ' . $description : '' ?></p>
                 <?php if($rrze_video_shortcode['showinfo'] == '1') { ?>
                    <span class="meta_heading">Author: </span><span class="meta_content"><?php echo $author; ?></span><br/>
                    <span class="meta_heading">Quelle: </span><span class="meta_content"><a href="<?php echo $video_file; ?>">Download</a></span>
                    <span class="meta_heading">Copyright: </span><span class="meta_content"><?php echo $copyright; ?></span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>
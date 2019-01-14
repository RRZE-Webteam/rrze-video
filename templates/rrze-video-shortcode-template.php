<?php
    $inline_css = '';
    if ($width_shortcode != 640) {
      $inline_css = ' style="width: ' . $width_shortcode . (!empty($suffix) ? $suffix : '') . ';"';
    }
    $video_title = '';
    if (!empty($showtitle)) {
        $video_title = '<' . $rrze_video_shortcode['titletag'] . '>' . $showtitle . '</' . $rrze_video_shortcode['titletag'] . '>';
    }
    $preview_image_src   = $picture;
    $preview_image_title = $video_url['title'];
    $preview_image_class = 'image' . $id;
    if (!empty($thumbnail)) {
        $preview_image_src   = $thumbnail[0];
        $preview_image_class = 'image' . $box_id;
    }
?>

<div class="rrze-video"<?php echo $inline_css;?>>
    <?php echo $video_title; ?>
    <div class="rrze-video-container rrze-video-id-<?php echo $box_id ?>">
        <a
            href="#get_video"
            data-toggle="modal"
            data-id="<?php echo $id ?>"
            data-preview-image="<?php echo $preview_image ?>"
            data-video-file="<?php echo $video_file ?>"
            data-target="#videoModal<?php echo $id ?>"
        >
        <img src="<?php echo $preview_image_src; ?>" title="<?php echo $preview_image_title; ?>"  alt="Video aufrufen" class="<?php echo $preview_image_class; ?>" />
        <div class="middle">
            <div class="play-button"><i class="fa fa-play-circle-o" aria-hidden="true"></i></div>
        </div>
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
                    <div class="videocontent<?php echo $id ?>">
                        <div class="player">
                            <img src="<?php echo $preview_image ?>" alt="<?php echo $video_url['title'] ?>" />
                        </div>
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

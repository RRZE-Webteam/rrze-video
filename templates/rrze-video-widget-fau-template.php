<?php
    $thistitle = wordwrap($showtitle, 50, "<br/>");
    if (empty($thistitle)) {
        $thistitle = wordwrap($modaltitle, 50, "<br/>");
    }
?>
<div class="rrze-video rrze-video-widget-container">
    <h2><?php echo $thistitle; ?></h2>
    <div class="rrze-video-defaultplayer">
	    <a
	        href="<?php echo $orig_video_url ?>"
	        data-player-type="fauvideo"
	        data-type="videothumb"
	        data-id="<?php echo $instance_id ?>"
	        data-preview-image="<?php echo $preview_image ?>"
	        data-video-file="<?php echo $video_file ?>"
	        data-toggle="modal" data-target="#videoModal<?php echo $instance_id ?>"
	    >
	    <div class="rrze-video-widget-box">
	        <?php
	        if(!empty($thumbnail)) {
		        echo '<img title="'. $modaltitle .'" alt="Bild zum Video '. $modaltitle .'" src="' . $thumbnail[0]  . '" width="'. $instance['width'] . '" height="' . $instance['height']  . '"  />'; // width="100%" responsive
	        } else {
	        ?>
		    <img title="<?php echo $modaltitle ?>" alt="Bild zum Video <?php echo $modaltitle ?>"  width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"  src="<?php echo $picture ?>"/> <!-- width="100%" responsive  -->
	        <?php }?>
	        <div class="overlay-widget">
		        <div class="text">
		            <span class="yt-icon-widget">
			            <em class="fa fa-play-circle-o" aria-hidden="true"></em>
		            </span>
		        </div>
	        </div>
	    </div><!-- .rrze-video-widget-box -->
	    </a>
    </div><!-- .rrze-video-defaultplayer -->
    <div class="modal fade" id="videoModal<?php echo $instance_id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
	    <div class="modal-dialog">
	        <div class="modal-content">
		        <div class="modal-header">
		            <div class="close-modal" data-dismiss="modal">
			            <em class="fa fa-times" aria-hidden="true"></em>
		            </div>
		            <h2 class="modal-title" style="color:#000;padding:<?php echo ($modaltitle) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap($modaltitle, 30, "<br/>") ?></h2>
		        </div><!-- .modal-header -->
		        <div class="modal-body">
		            <div class="videocontent<?php echo $instance_id ?>">
			            <div class="player">
			                <img src="<?php echo $preview_image ?>" alt="<?php echo $thistitle; ?>">
			            </div>
		            </div>
		        </div><!-- .modal-body -->
		        <div class="modal-footer">
                    <p class="description"><?php _e('Beschreibung:'); ?> <?php echo (!empty($desc)) ? $desc : __('Keine Angaben') ?></p>
                     <?php if($rrze_video_shortcode['showinfo'] == '1') { ?>
                        <dl>
                            <dt class="meta_heading">Author:</dt>
                            <dd class="meta_content"><?php echo $author; ?></dd>
                            <dt class="meta_heading">Quelle:</dt>
                            <dd class="meta_content"><a href="<?php echo $video_file; ?>">Download</a></dd>
                            <dt class="meta_heading">Copyright:</dt>
                            <dd class="meta_content"><?php echo $copyright; ?></dd>
                        </dl>
                    <?php } ?>
                </div><!-- .modal-footer -->
	        </div><!-- .modal-content -->
	    </div><!-- .modal-dialog -->
    </div><!-- .modal -->
</div><!-- .rrze-video -->

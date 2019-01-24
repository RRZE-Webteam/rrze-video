<?php
    $thistitle         = ( ! empty($thistitle) ) ? $showtitle    : $modaltitle;
    $preview_image_src = ( ! empty($thumbnail) ) ? $thumbnail[0] : $picture;
    $preview_image_alt = sprintf( __('Bild zum Video %s'), $modaltitle );
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
		    <img src="<?php echo $preview_image_src; ?>" title="<?php echo $modaltitle ?>" alt="<?php echo $preview_image_alt; ?>"  width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"/>
	        <div class="middle" aria-hidden="true">
                <div class="play-button"><i class="fa fa-play-circle-o"></i></div>
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
		            <h2 class="modal-title"><?php echo $modaltitle; ?></h2>
		        </div><!-- .modal-header -->
		        <div class="modal-body">
		            <div class="videocontent<?php echo $instance_id ?>">
			            <div class="player">
			                 <img src="<?php echo $preview_image_src; ?>" title="<?php echo $modaltitle ?>" alt="<?php echo $preview_image_alt; ?>"  width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"/>
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

<?php
    $description       = $desc;
    $video_title       = ( ! empty($showtitle) ) ? '<h2>' . $showtitle . '</h2>' : '';
    $preview_image_src = ( ! empty($thumbnail) ) ? $thumbnail[0] : $picture;
    $preview_image_alt = sprintf( __('Bild zum Video %s'), $modaltitle );
?>
<div class="rrze-video rrze-video-widget-container">
    <?php echo $video_title; ?>
    <div class="rrze-video-defaultplayer">
        <div class="video-preview<?php echo $instance_id ?>" id="video-preview<?php echo $instance_id ?>">
            <a
                href="<?php echo $orig_video_url ?>"
                data-player-type="fauvideo"
                data-toggle="modal"
                data-type="videothumb"
                data-id="<?php echo $instance_id ?>"
                data-preview-image="<?php echo $preview_image ?>"
                data-video-file="<?php echo $video_file ?>"
                data-target="#videoModal<?php echo $instance_id ?>"
            >
            <div class="rrze-video-widget-box">
                <img id="video-thumbnail<?php echo $instance_id ?>" src="<?php echo $preview_image_src; ?>" title="<?php echo $modaltitle ?>" alt="<?php echo $preview_image_alt; ?>"  width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"/>
                <div class="middle" aria-hidden="true">
                    <div class="play-button"><i class="fa fa-play-circle-o"></i></div>
                </div>
            </div><!-- .rrze-video-widget-box -->
            </a>
	    </div><!-- .video-preview -->
    </div><!-- .rrze-video-defaultplayer -->
    <div class="modal fade rrze-video-modal" id="videoModal<?php echo $instance_id ?>" role="dialog" data-backdrop="false">
	    <div class="modal-dialog">
	        <div class="modal-content">
		        <div class="modal-header">
		            <div class="close-modal" data-dismiss="modal">
			            <em class="fa fa-times" aria-hidden="true"></em>
		            </div>
		            <?php echo ( ! empty( $modaltitle ) ) ? '<h2 class="modal-title">' . $modaltitle . '</h2>' : '';  ?>
		        </div><!-- .modal-header -->
		        <div class="modal-body">
		            <div class="videocontent<?php echo $instance_id ?>">
			            <div class="player">
			                 <img src="<?php echo $preview_image_src; ?>" title="<?php echo $modaltitle ?>" alt="<?php echo $preview_image_alt; ?>"  width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height'] ?>"/>
			            </div>
		            </div>
		        </div><!-- .modal-body -->
		        <div class="modal-footer">
                    <p class="description"><?php _e('Beschreibung:'); ?> <?php echo (!empty($description)) ? $description : __('Keine Angaben') ?></p>
                     <?php if($meta == '1') { ?>
                        <dl>
                            <dt class="meta_heading"><?php _e('Autor:'); ?></dt>
                            <dd class="meta_content"><?php echo $author; ?></dd>
                            <dt class="meta_heading"><?php _e('Quelle:'); ?></dt>
                            <dd class="meta_content"><a href="<?php echo $video_file; ?>"><?php _e('Download:'); ?></a></dd>
                            <dt class="meta_heading"><?php _e('Copyright:'); ?></dt>
                            <dd class="meta_content"><?php echo $copyright; ?></dd>
                        </dl>
                    <?php } ?>
                </div><!-- .modal-footer -->
	        </div><!-- .modal-content -->
	    </div><!-- .modal-dialog -->
    </div><!-- .modal -->
</div><!-- .rrze-video -->

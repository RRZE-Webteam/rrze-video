<div class="rrze-video rrze-video-widget-container">
<?php 
$thistitle = wordwrap($showtitle, 50, "<br/>");
if (empty($thistitle)) {
   $thistitle = wordwrap($modaltitle, 50, "<br/>");
}
echo "<h2>".$thistitle."</h2>";
?> 
    <div class="rrze-video-youtubeplayer">
	<?php if(!$show_youtube_player) { ?>
	<a href="#get_widget_mejs_youtube" data-toggle="modal"  data-box-id="<?php echo $id ?>" data-youtube-id="<?php echo $youtube_id ?>" data-target="#videoModal<?php echo $id ?>">
	<?php } else { ?>
	<a href="#get_widget_youtube" data-toggle="modal" data-box-id="<?php echo $id ?>" data-youtube-id="<?php echo $youtube_id ?>" data-target="#videoModal<?php echo $id ?>">
	<?php } ?>    
	<div class="box-widget<?php echo $box_id ?>">
	    <?php if( $youtube_resolution == 1 ) { ?>
	    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" alt="Video aufrufen" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/maxresdefault.jpg"/> <!-- width="100%" responsive maxresdefault.jpg hqdefault.jpg -->
	    <?php } elseif( $youtube_resolution == 2 ) { ?>
	    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" alt="Video aufrufen" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/default.jpg"/>
	    <?php } elseif( $youtube_resolution == 3 ) { ?>
	    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" alt="Video aufrufen" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/hqdefault.jpg"/>
	    <?php } elseif( $youtube_resolution == 4 ) { ?>
	    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" alt="Video aufrufen" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"/>
	    <?php } else { ?>
	    <img title="<?php echo (!isset($modaltitle)) ? 'Youtube Image' : get_the_title() ?>" alt="Video aufrufen" width="<?php echo $instance['width'] ?>" height="<?php echo $instance['height']?>" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/sddefault.jpg"/>
	    <?php } ?>
	    <div class="overlay-widget">
		<div class="text">
		    <span class="yt-icon-widget">
			<em class="fa fa-play-circle-o" aria-hidden="true"></em>
		    </span>
		</div>
	    </div>
	</div>
	</a>
    </div>
    <div class="modal fade" id="videoModal<?php echo $id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
	<div class="modal-dialog">
	    <div class="modal-content">
		<div class="modal-header">
		    <div class="close-modal" data-dismiss="modal">
			<em class="fa fa-times" aria-hidden="true"></em>
		    </div>
		    <h2 class="widget-title" style="color:#000;padding:<?php echo ($modaltitle) ? '30px 0px' : '20px 0px' ?>"><?php echo (isset($modaltitle)) ? wordwrap($modaltitle, 30, "<br/>") : '' ?></h2>
		</div>
		<div class="modal-body">
		    <div class="videocontent<?php echo $id ?>">
			<?php if(!$show_youtube_player) { ?>
			<div class="player">
			    <img title="preview_image" width="100%" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/mqdefault.jpg"  alt="<?php echo $thistitle; ?>">
			</div>
			<?php } else { ?>
			<div class="embed-container<?php echo $id ?>">
			    <div class="youtube-video"></div>
			</div>
			<?php } ?>
		    </div>
		</div>
		<div class="modal-footer">
		    <p><?php echo (isset($desc)) ? wordwrap($desc, 50, "<br/>") : '' ?></p>
		</div>
	    </div>
	</div>
    </div>
</div>
<h3><?php echo wordwrap($showtitle, 30, "<br/>") ?></h3>
<div class="box<?php echo $box_id ?>">
    <img alt="" class="image_reponsive" src="https://img.youtube.com/vi/<?php echo $youtube_id ?>/maxresdefault.jpg"/></a> <!-- width="100%" responsive maxresdefault.jpg hqdefault.jpg -->
    <div class="overlay">
        <div class="text">
            <a href="" data-toggle="modal" data-target="#videoModal<?php echo $id ?>">
                <span class="yt-icon"><i class="fa fa-play-circle-o" aria-hidden="true"></i></span>
            </a>
        </div>
    </div>
</div>    
<div class="modal fade is_youtube" id="videoModal<?php echo $id ?>" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <div class="close-modal" data-dismiss="modal">
                <i class="fa fa-times" aria-hidden="true"></i>
            </div>
            <h2 class="modal-title" style="text-align:left;padding:<?php echo ($modaltitle) ? '30px 0px' : '20px 0px' ?>"><?php echo wordwrap($modaltitle, 30, "<br/>") ?></h2>
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
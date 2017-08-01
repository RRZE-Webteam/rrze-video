jQuery(document).ready(function($) {
$('.player')
    .mediaelementplayer({
        alwaysShowControls: true,
        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
    });
    
    $('body').on('hidden.bs.modal', '.modal', function() {
        $('video').trigger('pause');
    });
    
    $("[id*=videoModal]").on('hidden.bs.modal', function (e) {
        var $this = $(this); 
        var $frame = $this.find('iframe'); 
        $frame.attr("src", $frame.attr("src"));
    });
    
    $("[class^=box-widget]").click(function() {
        $(window).scrollTop(0);
    });
    
    $("[class^=rrze-video-container]").click(function() {
        $(window).scrollTop(0);
    });
});
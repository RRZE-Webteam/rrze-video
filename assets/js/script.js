jQuery(document).ready(function($) {
$('.player')
    .mediaelementplayer({
        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen']
    });
    
    $('body').on('hidden.bs.modal', '.modal', function() {
        $('video').trigger('pause');
    });
    
    $("[id*=videoModal]").on('hidden.bs.modal', function (e) {
        var $this = $(this); 
        var $frame = $this.find('iframe'); 
        $frame.attr("src", $frame.attr("src"));
    });
    
    $(".yt-icon-widget").click(function() {
        $(window).scrollTop(0);
    });
});

$(document).keyup(function(e) { 
    if (e.keyCode == 27) { 
       $('.modal-overlay').hide();
       $('.modal').hide();
    } 
});
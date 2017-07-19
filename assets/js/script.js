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
    
    $("[class^=box]").click(function() {
        $(window).scrollTop(0);
    });
    
    $("[class^=container]").click(function() {
        $(window).scrollTop(0);
    });
    
    $(document).keydown(function (e) {
        if (e.keyCode == 38) {
            $('.modal-overlay').hide();
            $('.modal').hide();
            $('video').trigger('pause');
            player = new YT.Player('ytplayer');
            stopVideo($('#ytplayer'));
           
        }
    });
    
    var stopVideo = function(player) {
    var vidSrc = player.prop('src');
    player.prop('src', '');
    player.prop('src', vidSrc);
  };
});
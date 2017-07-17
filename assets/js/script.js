jQuery(document).ready(function($) {
$('.player')
    .mediaelementplayer({
        alwaysShowControls: true,
        features: ['playpause','stop','current','progress','duration','volume','tracks','fullscreen'],
        iPadUseNativeControls: true,
        iPhoneUseNativeControls: true,
        AndroidUseNativeControls: true
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
        if (e.keyCode > 36 && e.keyCode < 41) {
            $('.modal-overlay').hide();
            $('.modal').hide();
            $('video').trigger('pause');
        }
    });
});
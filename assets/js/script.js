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
    
    $("[class^=box]").click(function() {
        $(window).scrollTop(0);
    });
    
    $(document).keyup(function(e) { 
        if (e.keyCode == 27) { 
           $('.modal-overlay').hide();
           $('.modal').hide();
        } 
    });


    /*function autoPlayYouTubeModal() {
         var trigger = $("body").find('[data-toggle="modal"]');
         trigger.click(function () {
             var theModal = $(this).data("target"),
                 videoSRC = $(this).attr("data-theVideo"),
                 videoSRCauto = videoSRC + "?rel=0";
             $(theModal + ' iframe').attr('src', videoSRCauto);
             $(theModal + ' button.close').click(function () {
                 $(theModal + ' iframe').attr('src', videoSRC);
             });
         });
     }
    autoPlayYouTubeModal();*/

});

/*$(document).keyup(function(e) { 
    if (e.keyCode == 27) { 
       $('.modal-overlay').hide();
       $('.modal').hide();
    } 
});*/
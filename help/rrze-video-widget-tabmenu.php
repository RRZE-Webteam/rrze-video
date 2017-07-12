<?php

namespace RRZE\PostVideo;

add_action( "load-widgets.php", 'RRZE\PostVideo\rrze_video_widget_help_tab' , 20 );

function rrze_video_widget_help_tab () {
    
    $current_screen = get_current_screen();
    
    if( $current_screen->id === "widgets" ) {
        $current_screen->add_help_tab( array(
        'id'            => 'rrze_video_widget_help',
        'title'         => __('Video Widget Help', 'rrze-video'),
        'content'	=> '<p><strong>' . __( 'Video Widget' ) . '</strong></p><p>' . __( 'Das Video-Widget ist Bestandteil des RRZE-Video-Plugin und bietet die Möglichkeit sowohl Videos aus dem FAU-Videoportal als auch Youtube-Videos auf'
                . ' Ihrer Wordpress-Website einzubinden. Nachdem Sie das Video-Plugin installiert haben, erscheint in der Rubrik <strong>Verfügbare Widgets</strong>'
                . ' ein Balken mit dem Titel RRZE Video Widget. Dies können Sie einem Bereich hinzufügen. Um ein Video aus dem FAU-Video-Portal auszugeben, fügen Sie'
                . ' entweder die ID aus dem Videodatensatz oder die URL hinzu. Möchten Sie ein Video aus Youtube einbinden, so tragen Sie z.b. die URL des Videos ein.'
                . ' Das Video-Widget bietet darüber hinaus die Möglichkeit ein Zufallsvideo auszugeben. Hierzu wählen Sie ein Genre, welches Sie vorher Ihrem Videodatensatz hinzugefügt haben, aus. '
                , 'rrze-video' ) . '</p>',
        ) 
    );
    }
}
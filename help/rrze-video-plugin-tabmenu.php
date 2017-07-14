<?php

namespace RRZE\PostVideo;

add_action( "load-plugins.php", 'RRZE\PostVideo\rrze_video_plugin_help_tab' , 20 );

function rrze_video_plugin_help_tab () {
    $current_screen = get_current_screen();
    
    if( $current_screen->id === "plugins" ) {

    $current_screen->add_help_tab( array(
        'id'            => 'rrze_video_plugin_help',
        'title'         => __('Video Plugin Help'),
        'content'	=> '<p><strong>' . __( 'Video Plugin', 'rrze-video' ) . '</strong></p><p>' . __( 'Mit dem RRZE Video Plugin können Sie sowohl Videos aus dem FAU-Videoportal als auch Youtube Videos auf Ihrer Seite ausgeben. '
                . 'Nach der Aktivierung des Plugins steht das Plugin in der linken'
                . ' Menüleiste unter dem Begriff Video zur Verfügung. Für die Ausgabe eines Videos müssen Sie lediglich einen Video-Datensatz anlegen.'
                . ' Hierfür benötigen Sie einen Video-Titel, die Video-Url sowie eine Beschreibung zum Video. Soll auf Ihrer Seite ein eigenes Vorschaubild angezeigt werden,'
                . ' so fügen Sie dem Datensatz ein eigenes Beitragsbild hinzu. Dieses sollte die Größe 640x360px (Format 16x9) haben. Ohne ein eigenes Beitragsbild wird automatisch das im FAU Videoportal '
                . ' hinterlegte Vorschaubild auf Ihrer Seite angezeigt. Wollen Sie Videos eines Genres zufällig anzeigen, so müssen Sie dem'
                . ' Video-Datensatz ein Genre zuordnen und dieses im Widget auswählen.') .'</p>',
    ) );
    }
}
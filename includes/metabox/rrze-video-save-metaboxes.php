<?php

namespace RRZE\PostVideo;

function url_callback( $post) {

    wp_nonce_field( '_url_nonce', 'url_nonce' );

    $value = get_post_meta( $post->ID, 'url', true );
    echo '
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="url">' . __('URL zum Video') . '</label>
                </th>
                <td>
                    <input type="text" class="regular-text code" name="url" id="url" value="'.  esc_attr( $value ) . '"/><br>' . PHP_EOL . '
                    <em>' . __('z.B. http://www.video.uni-erlangen.de/webplayer/id/13953') . '</em>
                </td>
            </tr>
        </table>' . PHP_EOL;

}

function url_meta_box_save( $post_id, $post, $update ) {

    $post_type = get_post_type($post_id);

    if ( "video" != $post_type ) return;

    if ( isset( $_POST['url'] )  ) {
        $url = sanitize_text_field( $_POST['url'] );
        update_post_meta( $post_id, 'url', $url );
    } else {
        update_post_meta( $post_id, 'url', FALSE );
    }
}

add_action( 'save_post', 'RRZE\PostVideo\url_meta_box_save', 10, 3 );

function description_callback( $post) {

    wp_nonce_field( '_description_nonce', 'description_nonce' );

    $value = get_post_meta( $post->ID, 'description', true );
    wp_editor(
        htmlspecialchars_decode($value),
        'description',
        $settings = array(
            'textarea_name'=>'description',
            'wpautop' => false,
            'media_buttons' => false,
            'tinymce' => false,
            'quicktags' => array('buttons' => 'em,strong,link')
        )
    );
}

function description_meta_box_save( $post_id, $post, $update ) {

    $post_type = get_post_type($post_id);

    if ( "video" != $post_type ) return;

    if ( isset( $_POST['description'] ) ) {
        $description = wp_kses_post( $_POST['description'] );
        update_post_meta( $post_id, 'description', $description );
    } else {
        update_post_meta( $post_id, 'description', FALSE );
    }
}

add_action( 'save_post', 'RRZE\PostVideo\description_meta_box_save', 10, 3 );

function video_admin_notice() {
    $error = get_transient( 'video_id_failure' );
    delete_transient( 'video_id_failure' );

    if ( $error ) {
        $class = 'notice notice-error';
        $message1 = __(
            'Bitte geben Sie eine Video ID mit genau 5 Ziffern ein!',
            'rrze-test' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message1 ) );

    }
}

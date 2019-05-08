<?php
    get_header();
?>
    <div id="hero"></div>
    <div id="content">
        <div class="container">
            <div class="row">
                <main>
                    <h1 class="screen-reader-text"><?php _e('Videos aus der Mediathek'); ?></h1>
                    <div class="rrze-video-archive">
                    <?php
                        while ( have_posts() ) : the_post();
                            $url  = esc_url(get_post_meta($post->ID, 'url', true));
                            $desc = get_post_meta($post->ID, 'description', true);
                    ?>
                            <div <?php post_class(); ?>>
                                <?php the_title('<h2>','</h2>'); ?>
                    <?php
                            echo do_shortcode('[fauvideo id="' . $post->ID . '" width="100%"  showtitle="0" showinfo="1"]');
                            if( $url != '' ){
                                echo sprintf('<p class="rrze-video__url">%s</p>',$url);
                            }
                    ?>
                            </div>
                    <?php endwhile; ?>
                    </div>
                </main>
            </div>
        </div>
    </div>

<?php
    get_footer();
?>
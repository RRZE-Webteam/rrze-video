<?php

    /*
     * FAU Themes use a breadcrumb.
     */
    $helpers = new RRZE\PostVideo\RRZE_Video_Functions();
    $is_fau_theme = $helpers->is_fau_theme();
    if ( $is_fau_theme ) {
        // we need a breadcrumb.
        if(function_exists('fau_initoptions')) {
            $options = fau_initoptions();
        } else {
            $options = array();
        }
        $breadcrumb = '';
        if (isset($options['breadcrumb_root'])) {
            if ($options['breadcrumb_withtitle']) {
                $breadcrumb .= '<h3 class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo('title').'</h3>' . PHP_EOL;
            }
            $breadcrumb .= '<nav aria-labelledby="bc-title" class="breadcrumbs">';
            $breadcrumb .= '<h4 class="screen-reader-text" id="bc-title">'.__('Sie befinden sich hier:','fau').'</h4>';
            $breadcrumb .= '<a data-wpel-link="internal" href="' . site_url('/') . '">' . $options['breadcrumb_root'] . '</a>';
            $breadcrumb .= '<span>/</span>';
            $breadcrumb .= '<span class="active" aria-current="page">' . __('Videos','rrze-video') . '</span>';
            $breadcrumb .= '</nav>';
        }
    }

    get_header();
?>
    <section id="hero" class="hero-small">
        <div class="container">
            <?php if( isset($breadcrumb) && ! empty($breadcrumb) ) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <?php echo $breadcrumb; ?>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-xs-12">
                    <h1><?php _e('Ihre Videos aus der Mediathek', 'rrze-video'); ?></h1>
                </div>
            </div>
        </div>
    </section>
    <div id="content">
        <div class="container">
            <div class="row">
                <main>
                    <div class="rrze-video-archive">
                    <?php
                        while ( have_posts() ) : the_post();
                            $url  = esc_url(get_post_meta($post->ID, 'url', true));
                            $desc = get_post_meta($post->ID, 'description', true);
                    ?>
                            <div <?php post_class(); ?>>
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php
                            echo do_shortcode('[fauvideo id="' . $post->ID . '" width="100%"  showtitle="0" showinfo="1"]');
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

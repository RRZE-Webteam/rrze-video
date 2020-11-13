<?php

/* Quit */
defined('ABSPATH') || exit;

if(function_exists('fau_initoptions')) {
    $options = fau_initoptions();
} else {
    $options = array();
}

$breadcrumb = '';
if (isset($options['breadcrumb_root'])) {
    if ($options['breadcrumb_withtitle']) {
        $breadcrumb .= '<h3 class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo('title').'</h3>';
        $breadcrumb .= "\n";
    }
    $breadcrumb .= '<nav aria-labelledby="bc-title" class="breadcrumbs">'; 
    $breadcrumb .= '<h4 class="screen-reader-text" id="bc-title">'.__('Sie befinden sich hier:','fau').'</h4>';
    $breadcrumb .= '<a data-wpel-link="internal" href="' . site_url('/') . '">' . $options['breadcrumb_root'] . '</a>';
}

global $post;

$args = array( 'post_type' => 'video' );

$loop = new WP_Query( $args );

get_header(); ?>

    <section id="hero" class="hero-small">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php echo $breadcrumb; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h1>Ihre Videos aus der Videothek</h1>
                </div>
            </div>
        </div>
    </section>
    <div id="content">
        <div class="container">
            <div class="row">
                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <div class="col-xs-12 col-lg-6">
                    <div class="rrze-video">
                        <?php $url = get_post_meta($post->ID, 'url', true);
                        echo do_shortcode('[fauvideo url="'. $url . '" width="480"  showtitle="1" showinfo="1" titletag="h4"]');
                    ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>

<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\Capabilities;

/**
 * Class CPT - Custom Post Type
 * @package RRZE\Video
 */
class CPT
{
    const POST_TYPE = 'video';

    const TAX_CATEGORY = 'genre';

    public function __construct()
    {
        add_action('init', [$this, 'set']);
        add_action('admin_init', [$this, 'register']);
        // Set thumbnail size
        add_image_size('rrze_video_featured_image', 60, 60, false);
    }

    public function set()
    {
        $labels = [
            'name'                  => _x('Videothek', 'Post Type General Name', 'rrze-video'),
            'singular_name'         => _x('Video', 'Post Type Singular Name', 'rrze-video'),
            'menu_name'             => __('Videothek', 'rrze-video'),
        ];

        $video_args = array(
            'label'                 => __('Video', 'rrze-video'),
            'description'           => __('Videosammlung erstellen', 'rrze-video'),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail'),
            'taxonomies'            => array('Genre'),
            'menu_icon'             => 'dashicons-format-video',

            'public'                => false,
            'publicly_queryable'    => false,
            'exclude_from_search'   => true,
            'show_ui'               => true,
            'query_var'             => false,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'show_in_admin_bar'     => false,
            'menu_icon'             => false,
            'rewrite'               => false,
            'has_archive'           => false,
            'show_in_rest'          => false,

            'capability_type'       => Capabilities::getCptCapabilityType('video'),
            'capabilities'          => (array) Capabilities::getCptCaps('video'),
            'map_meta_cap'          => Capabilities::getCptMapMetaCap('video')
        );

        register_post_type(self::POST_TYPE, $video_args);

        register_taxonomy(
            self::TAX_CATEGORY,
            self::POST_TYPE,
            [
                'hierarchical'                => true,
                'public'                      => true,
                'show_ui'                     => true,
                'show_admin_column'           => true,
                'show_in_nav_menus'           => true,
                'capabilities' => [
                    'manage_terms' => 'edit_videos',
                    'edit_terms' => 'edit_videos',
                    'delete_terms' => 'edit_videos',
                    'assign_terms' => 'edit_videos'
                ]
            ]
        );
    }

    public function register()
    {
        register_taxonomy_for_object_type(self::TAX_CATEGORY, self::POST_TYPE);
        add_action('restrict_manage_posts', [$this, 'filter_by_category']);
        add_filter('parse_query', [$this, 'taxonomy_filter_post_type_request']);
        add_filter('manage_video_posts_columns', array($this, 'show_video_columns'));
        add_action('manage_video_posts_custom_column', array($this, 'show_video_columns'), 10, 2);
        add_filter('manage_edit-video_sortable_columns', array($this, 'video_sortable_columns'));
        add_filter('manage_edit-video_columns', array($this, 'video_columns'));
        // List Table Stuff.
        add_filter('bulk_actions-edit-video', array($this, 'removeBulkActions'), 10, 2);
        add_filter('post_row_actions', [$this, 'removeQuickEdit'], 10, 2);
        add_filter('months_dropdown_results', [$this, 'removeMonthsDropdown'], 10, 2);
    }

    public function taxonomy_filter_post_type_request($query)
    {
        global $pagenow, $typenow;
        if ($typenow == self::POST_TYPE) {
            if ('edit.php' == $pagenow) {
                $filters = get_object_taxonomies(self::POST_TYPE);

                foreach ($filters as $tax_slug) {
                    $var = &$query->query_vars[$tax_slug];
                    if (isset($var)) {
                        $term = get_term_by('id', $var, $tax_slug);
                        if (!empty($term))      $var = $term->slug;
                    }
                }
            }
        }
    }

    public function filter_by_category()
    {
        global $typenow;
        if ($typenow == self::POST_TYPE) {

            $filters = get_object_taxonomies(self::POST_TYPE);
            foreach ($filters as $tax_slug) {
                $tax_obj = get_taxonomy($tax_slug);
                wp_dropdown_categories(array(
                    'show_option_all' => sprintf(__('Alle %s anzeigen', 'rrze-video'), $tax_obj->label),
                    'taxonomy' => $tax_slug,
                    'name' => $tax_obj->name,
                    'orderby' => 'name',
                    'selected' => isset($_GET[$tax_slug]) ? $_GET[$tax_slug] : '',
                    'hierarchical' => $tax_obj->hierarchical,
                    'show_count' => true,
                    'hide_if_empty' => true
                ));
            }
        }
    }

    public function video_columns($columns)
    {
        $columns = [
            'cb'                => '<input type="checkbox" />',
            'title'             => __('Titel', 'rrze-video'),
            self::TAX_CATEGORY  => __('Kategorie', 'rrze-video'),
            'id'                => __('ID', 'rrze-video'),
            'url'               => __('URL', 'rrze-video'),
            'thumbnail'         => __('Vorschaubild', 'rrze-video'),
        ];
        return $columns;
    }

    public function show_video_columns($column_name)
    {
        global $post;
        switch ($column_name) {
            case 'title':
                $title = get_post_meta($post->ID, 'title', true);
                echo $title;
                break;
            case 'id':
                $id = get_the_ID();
                echo $id;
                break;
            case 'url':
                $video = get_post_meta($post->ID, 'url', true);
                echo $video;
                break;

            case 'thumbnail':
                if (has_post_thumbnail()) {
                    echo the_post_thumbnail('rrze_video_featured_image');
                } else {
                    echo '&mdash;';
                }
                break;
            case 'genre':
                $genre = get_the_term_list($post->ID,  self::TAX_CATEGORY);
                echo $genre;
                break;
        }
    }

    public function video_sortable_columns()
    {
        return [
            self::TAX_CATEGORY => self::TAX_CATEGORY,
        ];
    }

    public function removeBulkActions($action)
    {
        unset($action['edit']);
        return $action;
    }

    public function removeQuickEdit($actions)
    {
        if (self::POST_TYPE === get_post_type()) {
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }

    public function removeMonthsDropdown($months, $postType)
    {
        if ($postType == self::POST_TYPE) {
            $months = [];
        }
        return $months;
    }
}

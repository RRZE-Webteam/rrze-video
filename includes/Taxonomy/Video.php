<?php

namespace RRZE\Video\Taxonomy;
use function RRZE\Video\Config\get_rrze_video_capabilities;

defined('ABSPATH') || exit;

/**
 * Posttype video
 */
class Video extends Taxonomy {

    protected $postType = 'video';
    protected $taxonomy = 'genre';

    protected $pluginFile;
    private $settings = '';
    
    
    public function __construct($pluginFile, $settings) {
        $this->pluginFile = $pluginFile;
        $this->settings = $settings;
    }
    public function onLoaded() {
	add_action('init', [$this, 'set']);
	add_action('admin_init', [$this, 'register']);
	
    }

    
    public function set() {
	 $labels = [
	    'name'                  => _x( 'Videothek', 'Post Type General Name', 'rrze-video' ),
	    'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'rrze-video' ),
	    'menu_name'             => __( 'Videothek', 'rrze-video' ),
        ];
	
	$caps = get_rrze_video_capabilities();
	$video_args = array(
	    'label'                 => __( 'Video', 'rrze-video' ),
	    'description'           => __( 'Videosammlung erstellen', 'rrze-video' ),
	    'labels'                => $labels,
	    'supports'              => array( 'title', 'thumbnail' ),
	    'taxonomies'            => array( 'Genre' ),
	    'menu_icon'             => 'dashicons-format-video',
	    'hierarchical'          => false,
	    'public'                => true,
	    'publicly_queryable'    => true,
	    'show_ui'               => true, 
	    'show_in_menu'          => true,
	    'menu_position'         => 5,
	    'has_archive'           => false,		
	    'exclude_from_search'   => true,
	    'query_var'		=>  $this->postType,
	    'rewrite'		=> [
		'slug'	    => $this->postType,
		'with_front' => true,
		'pages'	    => true,
		'feeds'	    => true,
	    ],
	    'capability_type' => $this->postType,
	    'capabilities' => $caps,
	    'map_meta_cap' => true
	);

	register_post_type($this->postType, $video_args);	
	
	      
         register_taxonomy(
            $this->taxonomy,
            $this->postType,
            [
		'hierarchical'                => true,
		'public'                      => true,
		'show_ui'                     => true,
		'show_admin_column'           => true,
		'show_in_nav_menus'           => true,
            ]
        );
    }
   
    public function register() {
	register_taxonomy_for_object_type($this->taxonomy, $this->postType);
	add_action('restrict_manage_posts', [ $this, 'filter_by_category' ] );
	add_filter('parse_query', [$this, 'taxonomy_filter_post_type_request']);
	add_filter('manage_video_posts_columns', array( $this, 'show_video_columns' ));
	add_action('manage_video_posts_custom_column', array( $this, 'show_video_columns'), 10, 2 ); 	
	add_filter('manage_edit-video_sortable_columns', array( $this, 'video_sortable_columns' ));
	add_filter('manage_edit-video_columns', array( $this, 'video_columns')) ;
	
	// add_filter('post_row_actions', array( $this, 'remove_quickedit'), 10, 2 ); 
	add_filter('bulk_actions-edit-video', array( $this, 'remove_bulkactions'), 10, 2 ); 	

    }

    public function remove_quickedit ( $action, $post ) {
	 if ($post->post_type == 'video') {
	// Remove "Quick Edit"
	    unset($actions['inline hide-if-no-js']);
	}
	return $actions;
    }
    public function remove_bulkactions ( $action ) {
	
	// Remove "Quick Edit"
	    unset($actions['edit']);

	return $actions;
    }
    
    
    
    
    public function taxonomy_filter_post_type_request( $query ) {
	global $pagenow, $typenow;
	if ($typenow == $this->postType) {
	    if ( 'edit.php' == $pagenow ) {
		$filters = get_object_taxonomies( $this->postType );

		foreach ( $filters as $tax_slug ) {
		    $var = &$query->query_vars[$tax_slug];
		    if ( isset( $var ) ) {
			$term = get_term_by( 'id', $var, $tax_slug );
			if ( !empty( $term ) )      $var = $term->slug;
		    }
		}
	    }
	}
    }
    public function filter_by_category() {
        global $typenow;	
	if ($typenow == $this->postType) {
	
	    $filters = get_object_taxonomies($this->postType);
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
     
    public function video_columns( $columns ) {
	$columns = array(
     //       'cb'            => '<input type="checkbox" />',
	     'id'            => __( 'ID', 'rrze-video'),
            'title'         => __( 'Titel', 'rrze-video' ),
           
            'url'           => __( 'URL', 'rrze-video' ),
            'thumbnail'     => __( 'Vorschaubild', 'rrze-video' ),
             $this->taxonomy  => __( 'Kategorie', 'rrze-video' ),
	);

	return $columns;
    }

    public function show_video_columns($column_name) {
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
		$thumbnail = get_the_post_thumbnail($post->ID,  'medium');
		echo $thumbnail;
		break;
	     case 'genre':
		$genre = get_the_term_list($post->ID,  $this->taxonomy);
		echo $genre;
		break;
	}
    }

    public function video_sortable_columns() {
      return array(
	 $this->taxonomy   =>  $this->taxonomy,
      );
    }


   
}





    
    
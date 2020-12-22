<?php

namespace RRZE\Video;
defined('ABSPATH') || exit;

use RRZE\Video\OEmbed;
use RRZE\Video\IFrames;
use RRZE\Video\Plugin;

use RRZE\Video\Helper;

class Player {
    private static $counter = 1;

    public function __construct() {
      self::$counter++;
    }
    
    
    static function get_player($arguments) {
	$content = '';	
	
	if (!isset($arguments)) {
	    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
	    $content .= __('Fehler bei der Anzeige des Videoplayers: Es wurden keine ausreichenden Daten übergeben.','rrze-video');
	    $content .= '</div>';
	    return $content;
	}
	if (empty($arguments['url'])) {
	    // Try to get URL by ID or by Random
	    if (($arguments['id']) && (intval($arguments['id'])>0)) {
		    $post = get_post($arguments['id']);
                    if ($post && $post->post_type == 'video') {
			
			$posterdata = wp_get_attachment_image_src(get_post_thumbnail_id($arguments['id']), 'full');
			if (isset($posterdata) && is_array($posterdata)) {
			    $arguments['poster'] = $posterdata[0];
			}
			
			$url = get_post_meta($arguments['id'], 'url', true);
			if (isset($url)) {
			   $arguments['url'] = esc_url_raw($url);
			}
		    }		
	    } elseif (isset($arguments['rand']) && (!empty($arguments['rand']))) {
		
		$term = get_term_by('slug', $arguments['rand'], 'genre');
		if ($term) {
		     $argumentsTaxonomy = array(
			    'post_type'		=> 'video',
			    'post_status'	=> array( 'published' ),
			    'posts_per_page'	=> 1,
			    'orderby'		=>  'rand',
			    'tax_query' => array(
				array(
				    'taxonomy'      => $term->taxonomy,
				    'field'         => 'term_id', 
				    'terms'         => $term->term_id,
				),
			    ),
			);
		     $random_query = new \WP_Query( $argumentsTaxonomy );
		   
		     if ($random_query->have_posts()) {
			while ($random_query->have_posts()) {
			     $random_query->the_post();
			     $randvideoid = get_the_ID() ;
			   
			    $posterdata = wp_get_attachment_image_src(get_post_thumbnail_id($randvideoid), 'full');
			    if (isset($posterdata) && is_array($posterdata)) {
				$arguments['poster'] = $posterdata[0];
			    }

			    $url = get_post_meta($randvideoid, 'url', true);
			    if (isset($url)) {
			       $arguments['url'] = esc_url_raw($url);
			    }
			}
		    }
		    wp_reset_postdata();
		} 
		
	    }
	}
	if ($arguments['url']) {
	    // check for oembed
	    $isoembed = OEmbed::is_oembed_provider($arguments['url']);
	    
	    if (empty($isoembed)) {
		
		// Ok no fancy oEmbed... so lets look if its a stupid iframe-provider...
		
		if (IFrames::is_iframe_provider($arguments['url'])) {
		    
		    
		    $framedata = IFrames::get_iframe($arguments['url']);

		    if (isset($framedata['error']) && (!empty($framedata['error']))) {

			$content .= '<div class="rrze-video alert clearfix clear alert-danger">';
			$content .= '<strong>';
			$content .= __('Fehler beim Abruf des Videos','rrze-video');
			$content .= '</strong><br>';
			$content .= $framedata['error'];
			$content .= '</div>';

		    } elseif (!isset($framedata['video']) || (!$framedata['video'])) {
			 $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
			$content .= '<strong>';
			$content .= __('Fehler beim Abruf des Videos','rrze-video');
			$content .= '</strong><br>';
			$content .= __('Videodaten konnten nicht abgerufen werden.','rrze-video');
			$content .= '</div>';

		    } else {
			$arguments['video'] = $framedata['video'];

			$content .= '<div class="rrze-video">';
			$content .= '<div class="iframecontainer '.$framedata['video']['provider'].'">';
			$content .= $arguments['video']['html'];
			$content .= '</div>';
			if ((isset($arguments['show']) && preg_match('/link/', $arguments['show']))) {
			    $content .= '<p class="link">'.__('Link','rrze-video').': <a href="'.$arguments['url'].'">'.$arguments['url'].'</a></p>';
			}
			if (isset($arguments['video']['provider_name']) && (!empty($arguments['video']['provider_name']))) {
			    $content .= '<p>'.__('Quelle','rrze-video').': <a href="'.$arguments['video']['provider_url'].'">'.$arguments['video']['provider_name'].'</a></p>';
			}
			$content .= '</div>';
			Main::enqueueFrontendStyles(false);  
		    }

		} else {

		    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
		    $content .= '<strong>';
		    $content .= __('Unbekannte Videoquelle','rrze-video');
		    $content .= '</strong><br>';
		    $content .= __('Der folgenden Adresse konnte keinem bekannten Videoprovider zugeordnet werden oder dieser verfügt nicht über eine geeignete Schnittstelle zum Abruf von Videos.','rrze-video');
		    $content .= ' '.__('Bitte rufen Sie das Video daher auf, indem Sie direkt den folgenden Link folgen:','rrze-video');
		    $content .= ' <a href="'.$arguments['url'].'" rel="nofollow">'.$arguments['url'].'</a>';
		    $content .= '</div>';
		
		}
		
	    } else {
	//	$content .= "Oembed: ".$isoembed;
		$oembeddata = OEmbed::get_oembed_data($isoembed,$arguments['url']);

		if (isset($oembeddata['error']) && (!empty($oembeddata['error']))) {

		    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
		    $content .= '<strong>';
		    $content .= __('Fehler beim Abruf des Videos','rrze-video');
		    $content .= '</strong><br>';
		    $content .= $oembeddata['error'];
		    $content .= '</div>';
		    
		//    $content .= Helper::get_html_var_dump($oembeddata);
		     
		} elseif (!isset($oembeddata['video']) || (!$oembeddata['video'])) {
		     $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
		    $content .= '<strong>';
		    $content .= __('Fehler beim Abruf des Videos','rrze-video');
		    $content .= '</strong><br>';
		    $content .= __('Videodaten konnten nicht abgerufen werden.','rrze-video');
		    $content .= '</div>';
		    
		 //   $content .= Helper::get_html_var_dump($oembeddata);
		} else {
		    $arguments['video'] = $oembeddata['video'];
		    $arguments['oembed_api_url'] = $oembeddata['oembed_api_url'];
		    $arguments['oembed_api_error'] = $oembeddata['error'];
		    		    
//		    $content .= Helper::get_html_var_dump($arguments);
		    $content .= self::get_player_html($isoembed, $arguments);

		    Main::enqueueFrontendStyles(true);  


		}
	    }
	} else {
	    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
	    $content .= '<strong>';
	    $content .= __('Fehler beim Abruf des Videos','rrze-video');
	    $content .= '</strong><br>';
	    if ((isset($arguments['id'])) && ($arguments['id']>0)) {
		$content .= __('Die verwendete Id des Videos ist ungültig oder konnte keinem Video zugeordnet werden.','rrze-video');
	    } elseif ($arguments['rand']) {
		$content .= __('Es konnte kein Video aus der angegebenen Kategorie gefunden werden','rrze-video');
		$content .= ': "'.$arguments['rand'].'"';
	    } else {
		$content .= __('Es wurde weder eine gültige Id noch eine keine gültige URL für ein Video angegeben.','rrze-video');
	    }
	//     $content .= Helper::get_html_var_dump($arguments);
	    $content .= '</div>';
	}
	return $content;
    }

    static function get_player_html($provider, $data, $id = '') {
	$res = '';
	$providerlist = OEmbed::get_known_provider();
	if ((!isset($provider)) || (!isset($providerlist[$provider]))) {	 
	    $res .= '<div class="rrze-video alert clearfix clear alert-danger">';
	    $res .= __('Es wurde kein gültiger Videoprovider gefunden. Das Video kann daher nicht abgespielt werden oder konnte nicht erkannt werden.', 'rrze-video');
	    $res .= '</div>';	    
	    
	    return $res;
	}
	
	if (!empty($data['error'])) {
	     $res .= '<div class="rrze-video alert clearfix clear alert-danger">';
	     $res .= '<strong>';
	     $res .= __('Fehler beim Abruf des Videos','rrze-video');
	     $res .= ':</strong><br>';
	    $res .= $data['error'];
	    $res .= '</div>';
	    return $res;
	}
	$showvals = explode(',', $data['show']);
	$showtitle = $showmeta =  $showdesc = $showlink = false;
	
	foreach ($showvals as $value) {
	    $key = esc_attr(trim($value));
	    switch($key) {
		case 'title':
		    $showtitle = true;
		    break;
		case 'info':
		    $showmeta = true;
		    $showlink = true;
		    $showdesc = true;
		    break;
		case 'meta':
		    $showmeta = true;
		     break;
		case 'desc':
		    $showdesc = true;
		     break;
		case 'link':
		    $showlink = true;
		     break;
	    }
	    
	}


	if (isset($data['poster']) && (!empty($data['poster']))) {
	    $poster = $data['poster'];
	} elseif (isset($data['video']['preview_image']) && (!empty($data['video']['preview_image']))) {
	    $poster = $data['video']['preview_image'];    
	} elseif (isset($data['video']['thumbnail_url']) && (!empty($data['video']['thumbnail_url']))) {
	    $poster = $data['video']['thumbnail_url'];    
	}
	  $lang = $hreflang = '';
	
	    if (isset($data['inLanguage'])) {
		$lang = $data['inLanguage'];
		$hreflang = explode("-",$lang)[0];
	    } elseif (isset($data['language'])) {
		$lang = $data['language'];
		$hreflang = explode("-",$lang)[0];
	    }
	
	
	$res .= '<div class="rrze-video';
	
	if (isset($data['class']) && (!empty($data['class']))) {
	    $res .= ' '.$data['class'];
	}
	$res .= '">';
	
	$beforetag = '<h2>'; 
	$aftertag = '</h2>';
	
	if (isset($data['widgetargs'])) {
	    if ((isset($data['widgetargs']['before'])) && (!empty($data['widgetargs']['before']))) {
		$beforetag = $data['widgetargs']['before'];
	    }
	     if ((isset($data['widgetargs']['after'])) && (!empty($data['widgetargs']['after']))) {
		$aftertag = $data['widgetargs']['after'];
	    }
	} elseif ($data['titletag']) {
	   $beforetag = '<'.$data['titletag'].'>';
	   $aftertag = '</'.$data['titletag'].'>';
	}
	
	if ($showtitle) {
	    $res .= $beforetag.$data['video']['title'].$aftertag;
	} elseif (isset($data['widgetargs']) && isset($data['widgetargs']['title']) && (!empty($data['widgetargs']['title']))) {
	    $res .= $beforetag.$data['widgetargs']['title'].$aftertag;
	}
	
	
	if ($id == '') {
		    // create Random number to make a uniq class name
		    // This is need to display more as one video embed in the same page
		    $id = self::$counter++;
		}
	$classname = 'plyr-instance plyr-videonum-'.$id;
	
	
	if ($provider == 'youtube') {
	    
	    $classname = 'plyr-videonum-'.$id;
	    $res .= '<div class="youtube-video '.$classname.'"';
	    $res .= ' itemscope itemtype="https://schema.org/Movie"';
	    $res .= '>';
	    $res .= self::get_html_structuredmeta($data);
	    $res .= '<div class="plyr-instance" data-plyr-provider="youtube" data-plyr-embed-id="'.$data['video']['v'].'"';
	    
	    $res .= ' data-plyr-config=\'{';
	    $res .= ' "youtube": "{ noCookie: true }"';
	    if ($data['video']['title']) {
		$res .= ', "title": "'.$data['video']['title'].'"';
	    } 
	    if ($poster) {
		$res .= ', "poster": "'.$poster.'"';
	    } 
	    $res .= '}\'';
	    $res .= '></div>';
	    $res .= '</div>';
	} elseif ($provider == 'vimeo') {    
	    $classname = 'plyr-videonum-'.$id;
	    $res .= '<div class="vimeo-video '.$classname.'"';
	    $res .= ' itemscope itemtype="https://schema.org/Movie"';
	    $res .= '>';
	    $res .= self::get_html_structuredmeta($data);
	    $res .= '<div class="plyr-instance" data-plyr-provider="vimeo" data-plyr-embed-id="'.$data['video']['video_id'].'"';
	    if ($data['video']['title']) {
		$res .= ' data-plyr-config=\'{"title": "'.$data['video']['title'].'"}\'';
	    } 
	    $res .= '></div>';
	    $res .= '</div>';
	} elseif ($provider == 'fau') {
	    $classname = 'plyr-instance plyr-videonum-'.$id;
	    $res .= '<video class="'.$classname.'" playsinline controls crossorigin="anonymous"';
	    
	    $plyrconfig = ' data-plyr-config=\'{ ';
	    $plyrconfig .= ' "iconUrl": "';
	    
	    $plyrconfig .= plugins_url('/../img/plyr.svg', plugin_basename(__FILE__));
	    $plyrconfig .= '"';
	     
	    if ($data['video']['title']) {
		if (!empty($plyrconfig)) {
		    $plyrconfig .= ',';
		}
		$plyrconfig .= '"title": "'.$data['video']['title'].'"';
	    } 
	    $plyrconfig .= '}\'';
	    $res .= $plyrconfig;
	    
	    
	    
	    if ($poster) {
		$res .= ' poster="'.$poster.'" data-poster="'.$poster.'"';
	    }
	    if (isset($data['video']['width'])) {
		$res .= ' width="'.$data['video']['width'].'"';
	    }
	     if (isset($data['video']['height'])) {
		$res .= ' height="'.$data['video']['height'].'"';
	    }
	    
	    $res .= ' itemscope itemtype="https://schema.org/Movie"';
	    $res .= '>';
	    
	    $res .= self::get_html_structuredmeta($data);
	    
	    $path = parse_url($data['video']['file'], PHP_URL_PATH);
	    $ext = pathinfo($path, PATHINFO_EXTENSION);
	   
	    $res .= '<source src="'.$data['video']['file'].'" type="video/'.$ext.'">';
	    if ($ext == 'm4v') {
		// add also mp4 definiton for the same path due to old browsers
		$res .= '<source src="'.$data['video']['file'].'" type="video/mp4">';
	    }

	    if (isset($data['video']['alternative_Video_size_large']) && isset($data['video']['alternative_Video_size_large_url'])) {
		$path = parse_url($data['video']['alternative_Video_size_large_url'], PHP_URL_PATH);
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$res .= '<source src="'.$data['video']['alternative_Video_size_large_url'].'" type="video/'.$ext.'" size="'.$data['video']['alternative_Video_size_large_width'].'">';
	    }
	    if (isset($data['video']['alternative_Video_size_medium']) && isset($data['video']['alternative_Video_size_medium_url'])) {
		$path = parse_url($data['video']['alternative_Video_size_medium_url'], PHP_URL_PATH);
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$res .= '<source src="'.$data['video']['alternative_Video_size_medium_url'].'" type="video/'.$ext.'" size="'.$data['video']['alternative_Video_size_medium_width'].'">';
	    }
	    
	    if (isset($data['video']['transcript']) && (!empty($data['video']['transcript']))) {
		  $res .= '<track kind="captions" label="'.__('Audiotranskription','rrze-video').'" src="'.$data['video']['transcript'].'" default';
		  if ($hreflang) {
		      $res .= ' hreflang="'.$hreflang.'"';
		  }
		  $res .= '>';
	    }
	    $res .= __('Ihr Browser unterstützt leider keine HTML5 Videoformate. ', 'rrze-video');

	    if (isset($data['url'])) {
		$res .= 'Rufen Sie daher das Video <a href="'.$data['url'].'">'.$data['video']['title'].'</a> im Videoportal auf.';
	    } else {
		$res .= 'Rufen Sie die Videodatei <a href="'.$data['video']['file'].'">'.$data['video']['title'].'</a> direkt auf.';
	    }
	    

	    $res .= '</video>';
	} else {
	    $res .= '<div class="alert clearfix clear alert-danger">';
	    $res .= __('Videoprovider fehlerhaft definiert.', 'rrze-video');
	    $res .= '</div>';	
	    return $res;
	}
	
	if (($showdesc) && isset($data['video']['description']) && (!empty($data['video']['description']))) {
	    $res .= '<p class="desc">'.$data['video']['description'].'</p>';
	}
	if ($showmeta) {
	    $meta = '';
	
	    if (isset($data['video']['author_name']) && (!empty($data['video']['author_name']))) {
		$meta .= '<dt>'.__('Autor','rrze-video').'</dt><dd>';
		
		
		
		 if (isset($data['video']['author_url_0']) && (!empty($data['video']['author_url_0']))) {
		      $meta .= '<a href="'.$data['video']['author_url_0'].'">';
		 }
		 $meta .= $data['video']['author_name'];
		 if (isset($data['video']['author_url_0']) && (!empty($data['video']['author_url_0']))) {
		      $meta .= '</a>';
		 }
		$meta .=  '</dd>';    
	    }
	    if (isset($data['url']) && (!empty($data['url']))) {
		$meta .= '<dt>'.__('Quelle','rrze-video').'</dt><dd><a href="'.$data['url'].'">'.$data['url'].'</a></dd>';    
	    }
	//    if (isset($data['video']['provider_videoindex_url']) && (!empty($data['video']['provider_videoindex_url'])) && ($data['video']['provider_videoindex_url'] !== $data['url'])) {
	//	$meta .= '<dt>'.__('Video-Sammlung','rrze-video').'</dt><dd><a href="'.$data['video']['provider_videoindex_url'].'">'.$data['video']['provider_videoindex_url'].'</a></dd>';    
	//    }
	    if (isset($data['video']['alternative_VideoFolien_size_large']) && (!empty($data['video']['alternative_VideoFolien_size_large'])) && ($data['video']['alternative_VideoFolien_size_large'] !== $data['url'])) {
		$meta .= '<dt>'.__('Video mit Vortragsfolien','rrze-video').'</dt><dd><a href="'.$data['video']['alternative_VideoFolien_size_large'].'">'.$data['video']['alternative_VideoFolien_size_large'].'</a></dd>';    
	    }
	    if (isset($data['video']['alternative_Audio']) && (!empty($data['video']['alternative_Audio'])) && ($data['video']['alternative_Audio'] !== $data['url'])) {
		$meta .= '<dt>'.__('Audio-Format','rrze-video').'</dt><dd><a href="'.$data['video']['alternative_Audio'].'">'.$data['video']['alternative_Audio'].'</a></dd>';    
	    }

	    
	    if (isset($data['video']['provider_name']) && (!empty($data['video']['provider_name']))) {
		$meta .= '<dt>'.__('Provider','rrze-video').'</dt><dd>';
		if (isset($data['video']['provider_url']) && (!empty($data['video']['provider_url']))) {
		    $meta .= '<a href="'.$data['video']['provider_url'].'">';
		}
		$meta .= $data['video']['provider_name'];
		
		if (isset($data['video']['provider_url']) && (!empty($data['video']['provider_url']))) {
		    $meta .= '</a>';
		}
		$meta .= '</dd>';    
	    }
	    if (!empty($meta)) {
		$res .= '<dl class="meta">'.$meta.'</dl>';
	    }
	} elseif ($showlink && isset($data['url']) && (!empty($data['url']))) {
	    $res .= '<p class="link">'.__('Quelle','rrze-video').': <a href="'.$data['url'].'">'.$data['url'].'</a>';
	    
	     if (isset($data['video']['provider_videoindex_url']) && (!empty($data['video']['provider_videoindex_url']))) {
		$res .= '<br>'.__('Dieses Video ist Teil einer Video-Sammlung','rrze-video').': <a href="'.$data['video']['provider_videoindex_url'].'">'.$data['video']['provider_videoindex_url'].'</a>';    
	    }
	    $res .= '</p>';    
	}

	
	 $res .= '</div>';
	  return $res;
    }

    
    static function get_html_structuredmeta($data) {
	    if (isset($data['video']['title'])) {
		 $res = '<meta itemprop="name" content="'.$data['video']['title'].'">';
	    }
	    $poster = '';
	   if (isset($data['poster']) && (!empty($data['poster']))) {
		$poster = $data['poster'];
	    } elseif (isset($data['video']['preview_image']) && (!empty($data['video']['preview_image']))) {
		$poster = $data['video']['preview_image'];    
	    } elseif (isset($data['video']['thumbnail_url']) && (!empty($data['video']['thumbnail_url']))) {
		$poster = $data['video']['thumbnail_url'];    
	    }
	    $lang = $hreflang = '';
	
	    if (isset($data['inLanguage'])) {
		$lang = $data['inLanguage'];
		$hreflang = explode("-",$lang)[0];
	    } elseif (isset($data['language'])) {
		$lang = $data['language'];
		$hreflang = explode("-",$lang)[0];
	    }
	
	    
	    $res .= '<meta itemprop="image" content="'.$poster.'">';
	    if (isset($data['video']['upload_date'])) {
		 $res .= '<meta itemprop="dateCreated" content="'.$data['video']['upload_date'].'">';
	    }
	    if (isset($data['video']['author_name']) && (!empty($data['video']['author_name']))) {
		 $res .= '<meta itemprop="director" content="'.$data['video']['author_name'].'">';
	    }
	    if ($hreflang) {
		$res .= '<meta itemprop="inLanguage" content="'.$hreflang.'">';
	    }
	   if (isset($data['video']['provider_name']) && (!empty($data['video']['provider_name']))) {
		$res .= '<meta itemprop="provider" content="'.$data['video']['provider_name'].'">';
	    }
	    if (isset($data['video']['thumbnail_url']) && ($data['video']['thumbnail_url'] != $poster) ) {
		$res .= '<meta itemprop="thumbnailUrl" content="'.$data['video']['thumbnail_url'].'">';
	    }
	    if (isset($data['video']['duration'])) {
		$res .= '<meta itemprop="duration" content="'.$data['video']['duration'].'">';
	    }
	    if (isset($data['video']['version'])) {
		$res .= '<meta itemprop="version" content="'.$data['video']['version'].'">';
	    }
	    if (isset($data['video']['description']) && (!empty($data['video']['description']))) {
		$res .= '<meta itemprop="abstract" content="'.$data['video']['description'].'">';
	    }
	    return $res;
    }
    
}
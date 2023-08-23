<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\OEmbed;
use RRZE\Video\IFrames;
use RRZE\Video\Helper;

class Player
{
    /**
     * Singleton instance.
     * @var mixed
     */
    private static $instance = null;

    private $counter = 1;
    private $id = '';

    /**
     * Singleton
     * @return object
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getRenderID()
    {
        return self::instance()->counter++;
    }

    private function __construct()
    {
        $this->counter++;
    }

    /**
     * Fetches and processes the video player.
     * 
     * This function tries to retrieve the video from the provided URL, ID, or
     * random genre. The video content can be either an oEmbed or an iFrame.
     *
     * @param array $arguments Associative array containing one of the keys: 
     *                         'url' (the video URL), 'id' (video post ID), 
     *                         'rand' (slug of the genre for random video fetching).
     * @return string HTML content of the video player or an error message.
     */
    public function get_player($arguments)
    {
        $id = $this->getRenderID();
        $content = '';

        if (empty($arguments)) {
            return $this->handleError(__('Error when displaying the video player: Insufficient data was transferred.', 'rrze-video'));
        }

        if (empty($arguments['url'])) {
            $this->getUrlByIdOrRandom($arguments);
        }

        if (!empty($arguments['url'])) {
            if ($isoembed = OEmbed::is_oembed_provider($arguments['url'])) {
                $content .= $this->processOEmbed($isoembed, $arguments, $id);
                $this->enqueueFrontendStyles(true, [], $id);
            } else if (IFrames::is_iframe_provider($arguments['url'])) {
                $content .= $this->processIFrame($arguments, $id);
            } else {
                $content .= $this->handleError(__('Unknown video source', 'rrze-video'));
            }
        } else {
            $content .= $this->handleNoVideoError($arguments, $id);
        }

        return $content;
    }

    /**
     * Returns an error message wrapped in an HTML div element.
     * 
     * @param string $message The error message to display.
     * @return string Error message wrapped in an HTML div with error styling.
     */
    private function handleError($message)
    {
        return '<div class="rrze-video alert clearfix clear alert-danger">' . $message . '</div>';
    }

    /**
     * Generates an error message with a header, wrapped in an HTML div element.
     * 
     * @param mixed $id      The video or container ID.
     * @param string $header The header/title of the error message.
     * @param string $message The detailed error message.
     * @return string Error message with a header wrapped in an HTML div with error styling.
     */
    private function generateErrorContent($id, $header, $message)
    {
        return '<div class="rrze-video rrze-video-container-' . $id . ' alert clearfix clear alert-danger">' .
            '<strong>' . $header . '</strong><br>' . $message . '</div>';
    }

    /**
     * Determines the URL for the video based on an ID or fetches a random video URL.
     * 
     * @param array $arguments Associative array containing 'id' or 'rand' key for fetching.
     */
    private function getUrlByIdOrRandom(&$arguments)
    {
        if (!empty($arguments['id']) && (intval($arguments['id']) > 0)) {
            $this->getUrlById($arguments);
        } elseif (!empty($arguments['rand'])) {
            $this->getUrlRandomly($arguments);
        }
    }

    /**
     * Fetches the URL of a video by its post ID.
     * 
     * If the post with the provided ID is of type 'video', it retrieves its URL 
     * and optional poster image.
     *
     * @param array $arguments Associative array containing 'id' key to fetch the URL.
     */
    private function getUrlById(&$arguments)
    {
        $post = get_post($arguments['id']);
        if ($post && $post->post_type == 'video') {
            $posterdata = wp_get_attachment_image_src(get_post_thumbnail_id($arguments['id']), 'full');
            if (!empty($posterdata[0])) {
                $arguments['poster'] = $posterdata[0];
            }

            $url = get_post_meta($arguments['id'], 'url', true);
            if (!empty($url)) {
                $arguments['url'] = esc_url_raw($url);
            }
        }
    }

    /**
     * Fetches a random video URL from a specified genre.
     * 
     * This function tries to fetch a random video of a specific genre, based on the slug.
     * If found, it retrieves its URL and optional poster image.
     *
     * @param array $arguments Associative array containing 'rand' key to fetch the URL.
     */
    private function getUrlRandomly(&$arguments)
    {
        $term = get_term_by('slug', $arguments['rand'], 'genre');
        if ($term) {
            $argumentsTaxonomy = [
                'post_type'         => 'video',
                'post_status'       => ['published'],
                'posts_per_page'    => 1,
                'orderby'           =>  'rand',
                'tax_query'         => [
                    [
                        'taxonomy'  => $term->taxonomy,
                        'field'     => 'term_id',
                        'terms'     => $term->term_id,
                    ],
                ],
            ];
            $random_query = new \WP_Query($argumentsTaxonomy);

            if ($random_query->have_posts()) {
                while ($random_query->have_posts()) {
                    $random_query->the_post();
                    $randvideoid = get_the_ID();

                    $posterdata = wp_get_attachment_image_src(get_post_thumbnail_id($randvideoid), 'full');
                    if (!empty($posterdata[0])) {
                        $arguments['poster'] = $posterdata[0];
                    }

                    $url = get_post_meta($randvideoid, 'url', true);
                    if (!empty($url)) {
                        $arguments['url'] = esc_url_raw($url);
                    }
                }
            }
            wp_reset_postdata();
        }
    }

    /**
     * Processes an oEmbed video source.
     * 
     * This function tries to retrieve video content using an oEmbed provider.
     * If successful, it returns the video player's HTML. Otherwise, it returns an error message.
     *
     * @param mixed $isoembed  The oEmbed provider or configuration.
     * @param array $arguments Associative array containing video details.
     * @param mixed $id        The video or container ID.
     * @return string HTML content of the video player or an error message.
     */
    private function processOEmbed($isoembed, $arguments, $id)
    {
        $oembeddata = OEmbed::get_oembed_data($isoembed, $arguments['url']);
        if (!empty($oembeddata['error'])) {
            return $this->handleError(__('Error getting the video', 'rrze-video') . '<br>' . $oembeddata['error']);
        } elseif (empty($oembeddata['video'])) {
            return $this->handleError(__('Error getting the video', 'rrze-video') . '<br>' . __('Video data could not be obtained.', 'rrze-video'));
        } else {
            $arguments['video'] = $oembeddata['video'];
            $arguments['oembed_api_url'] = $oembeddata['oembed_api_url'] ?? '';
            $arguments['oembed_api_error'] = $oembeddata['error'] ?? '';
            return $this->get_player_html($isoembed, $arguments, $id);
        }
    }

    /**
     * Processes an iFrame video source.
     * 
     * This function tries to retrieve video content using an iFrame provider.
     * If successful, it returns the video player's HTML. Otherwise, it returns an error message.
     *
     * @param array $arguments Associative array containing video details.
     * @param mixed $id        The video or container ID.
     * @return string HTML content of the video player or an error message.
     */
    private function processIFrame($arguments, $id)
    {
        $content = '';

        if (IFrames::is_iframe_provider($arguments['url'])) {
            $framedata = IFrames::get_iframe($arguments['url']);

            if (!empty($framedata['error'])) {
                $content .= $this->generateErrorContent($id, __('Error getting the video', 'rrze-video'), $framedata['error']);
            } elseif (empty($framedata['video'])) {
                $content .= $this->generateErrorContent($id, __('Error getting the video', 'rrze-video'), __('Video data could not be obtained.', 'rrze-video'));
            } else {
                $arguments['video'] = $framedata['video'];

                $content .= '<div class="rrze-video rrze-video-container-' . $id . '">';
                $content .= '<div class="iframecontainer ' . $framedata['video']['provider'] . '">';
                $content .= $arguments['video']['html'];
                $content .= '</div>';

                if (!empty($arguments['show']) && preg_match('/link/', $arguments['show'])) {
                    $content .= '<p class="link">' . __('Link', 'rrze-video') . ': <a href="' . $arguments['url'] . '">' . $arguments['url'] . '</a></p>';
                }
                if (!empty($arguments['video']['provider_name'])) {
                    $content .= '<p>' . __('Source', 'rrze-video') . ': <a href="' . $arguments['video']['provider_url'] . '">' . $arguments['video']['provider_name'] . '</a></p>';
                }
                $content .= '</div>';

                $aspectratioArgs = !empty($arguments['aspectratio']) ? $arguments : [];
                $this->enqueueFrontendStyles(false, $aspectratioArgs, $id);
            }
        } else {
            $content .= $this->generateErrorContent($id, __('Unknown video source', 'rrze-video'), __('The following address could not be assigned to a known video provider or it does not have a suitable interface for retrieving videos.', 'rrze-video') . ' ' . __('So please call up the video by directly following the link below:', 'rrze-video') . ' <a href="' . $arguments['url'] . '" rel="nofollow">' . $arguments['url'] . '</a>');
        }

        return $content;
    }

    /**
     * Handles errors related to the absence of video content.
     * 
     * This function generates an error message based on the absence of a valid video ID or URL.
     *
     * @param array $arguments Associative array containing video details.
     * @param mixed $id        The video or container ID.
     * @return string Error message wrapped in an HTML div with error styling.
     */
    private function handleNoVideoError($arguments, $id)
    {
        $content = '<div class="rrze-video rrze-video-container-' . $id . ' alert clearfix clear alert-danger">';
        $content .= '<strong>';
        $content .= __('Error getting the video', 'rrze-video');
        $content .= '</strong><br>';
        if (empty($arguments['id'])) {
            $content .= __('The video ID used is invalid or could not be assigned to a video.', 'rrze-video');
        } elseif ($arguments['rand']) {
            $content .= __('No video from the specified category could be found.', 'rrze-video');
            $content .= ': "' . $arguments['rand'] . '"';
        } else {
            $content .= __('Neither a valid ID nor a valid URL was specified for a video.', 'rrze-video');
        }
        $content .= '</div>';

        return $content;
    }

    public function get_player_html($provider, $data, $id = '')
    {
        //Helper::debug($data);
        if ($id == '') {
            $id = $this->getRenderID();
        }

        $res = '';
        $providerlist = OEmbed::get_known_provider();
        if (empty($provider) || empty($providerlist[$provider])) {
            $res .= '<div class="rrze-video rrze-video-container-' . $id . ' alert clearfix clear alert-danger">';
            $res .= __('No valid video provider was found. As a result, the video cannot be played or could not be recognized.', 'rrze-video');
            $res .= '</div>';

            return $res;
        }

        if (!empty($data['error'])) {
            $res .= '<div class="rrze-video rrze-video-container-' . $id . ' alert clearfix clear alert-danger">';
            $res .= '<strong>';
            $res .= __('Error getting the video', 'rrze-video');
            $res .= ':</strong><br>';
            $res .= $data['error'];
            $res .= '</div>';
            return $res;
        }
        $showvals = explode(',', $data['show']);
        $showtitle = $showmeta =  $showdesc = $showlink = false;

        foreach ($showvals as $value) {
            $key = esc_attr(trim($value));
            switch ($key) {
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

        if (!empty($data['poster'])) {
            $poster = $data['poster'];
        } elseif (!empty($data['video']['preview_image'])) {
            $poster = $data['video']['preview_image'];
        } elseif (!empty($data['video']['thumbnail_url'])) {
            $poster = $data['video']['thumbnail_url'];
        }
        $lang = $hreflang = '';

        if (!empty($data['inLanguage'])) {
            $lang = $data['inLanguage'];
            $hreflang = explode("-", $lang)[0];
        } elseif (!empty($data['language'])) {
            $lang = $data['language'];
            $hreflang = explode("-", $lang)[0];
        }


        $res .= '<div class="rrze-video rrze-video-container-' . $id;

        if (!empty($data['class'])) {
            $res .= ' ' . $data['class'];
        }
        $res .= '">';

        $beforetag = '<h2>';
        $aftertag = '</h2>';

        if (!empty($data['widgetargs'])) {
            if (!empty($data['widgetargs']['before'])) {
                $beforetag = $data['widgetargs']['before'];
            }
            if (!empty($data['widgetargs']['after'])) {
                $aftertag = $data['widgetargs']['after'];
            }
        } elseif ($data['titletag']) {
            $beforetag = '<' . $data['titletag'] . '>';
            $aftertag = '</' . $data['titletag'] . '>';
        }

        if ($showtitle) {
            $res .= $beforetag . $data['video']['title'] . $aftertag;
        } elseif (!empty($data['widgetargs']['title'])) {
            $res .= $beforetag . $data['widgetargs']['title'] . $aftertag;
        }


        $classname = 'plyr-instance plyr-videonum-' . $id;


        if ($provider == 'youtube') {
            $classname = 'plyr-videonum-' . $id;
            $res .= '<div class="youtube-video ' . $classname . '"';
            $res .= ' itemscope itemtype="https://schema.org/Movie"';
            $res .= '>';
            $res .= $this->get_html_structuredmeta($data);

            /*
            $res .= '<div class="plyr-instance" data-plyr-provider="youtube" data-plyr-embed-id="' . $data['video']['v'] . '"';

            $res .= ' data-plyr-config=\'{';
            $res .= ' "preload": "none", ';
            $res .= ' "youtube": "{ noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 }"';
            if (!empty($data['video']['title'])) {
                $res .= ', "title": "' . $data['video']['title'] . '"';
            }
            if ($poster) {
                $res .= ', "poster": "' . $poster . '"';
            }
            $res .= '}\'';

            $res .= '></div>';
            */

            //
            $res .= '<div class="plyr__video-embed">';
            $res .= '<iframe';
            if (!empty($data['video']['title'])) {
                $res .= ' title="' . esc_html($data['video']['title']) . '"';
            }
            $res .= '  src="https://www.youtube-nocookie.com/embed/' . $data['video']['v'] . '?rel=0&showinfo=0&iv_load_policy=3&modestbranding=1"';
            $res .= '  allowfullscreen';
            $res .= '  allowtransparency';
            $res .= '  allow="autoplay"';
            $res .= '></iframe>';
            $res .= '</div>';
            //
            $res .= '</div>';
        } elseif ($provider == 'vimeo') {
            $classname = 'plyr-videonum-' . $id;
            $res .= '<div class="vimeo-video ' . $classname . '"';
            $res .= ' itemscope itemtype="https://schema.org/Movie"';
            $res .= '>';
            $res .= $this->get_html_structuredmeta($data);

            /*
            $res .= '<div class="plyr-instance" data-plyr-provider="vimeo" data-plyr-embed-id="' . $data['video']['video_id'] . '"';
            if ($data['video']['title']) {
                $res .= ' data-plyr-config=\'{ "preload": "none",  "title": "' . $data['video']['title'] . '" }\'';
            }
            $res .= '></div>';
            */

            $res .= '<div class="plyr__video-embed">';
            $res .= '<iframe';
            if (!empty($data['video']['title'])) {
                $res .= ' title="' . esc_html($data['video']['title']) . '"';
            }
            $res .= '  src="https://player.vimeo.com/video/' . $data['video']['video_id'] . '?autoplay=0&loop=0&title=0&byline=0&portrait=0"';
            $res .= '  allowfullscreen';
            $res .= '  allowtransparency';
            $res .= '  allow="autoplay"';
            $res .= '></iframe>';
            $res .= '</div>';

            $res .= '</div>';
        } elseif ($provider == 'fau') {
            if (isset($data['video']['type']) && $data['video']['type'] == 'audio' && isset($data['video']['file'])) {
                $res .= '<audio preload="none" class="' . $classname . '" controls crossorigin="anonymous">'
                    . '<source src="' . $data['video']['file'] . '" type="audio/mp3" />'
                    . '</audio>';
            } else {
                $classname = 'plyr-instance plyr-videonum-' . $id . ' ' . Self::get_aspectratio_class($data);
                $res       .= '<video preload="none" class="' . $classname . '" playsinline controls crossorigin="anonymous"';
                $res .= ' data-video-title-id="' . $id . '"';  // Pass the id to the player

                $plyrconfig = ' data-plyr-config=\'{ ';
                $plyrconfig .= '"preload": "none", ';
                $plyrconfig .= '"loadSprite": "false", ';
                $plyrconfig .= ' "iconUrl": "' . plugin()->getUrl('assets/plyr') . 'plyr.svg", ';
                $plyrconfig .= ' "blankVideo": "' . plugin()->getUrl('assets/plyr') . 'blank.mp4"';

                if (!empty($data['video']['title'])) {
                    $plyrconfig .= ', "title": "' . $data['video']['title'] . '"';
                }
                $plyrconfig .= ' }\'';
                $res        .= $plyrconfig;

                if ($poster) {
                    $res .= ' poster="' . $poster . '" data-poster="' . $poster . '"';
                }

                if (!empty($data['video']['width'])) {
                    $res .= ' width="' . $data['video']['width'] . '"';
                }

                if (!empty($data['video']['height'])) {
                    $res .= ' height="' . $data['video']['height'] . '"';
                }

                $res .= ' itemscope itemtype="https://schema.org/Movie"';
                $res .= '>';

                $res .= $this->get_html_structuredmeta($data);


                $path = parse_url($data['video']['file'], PHP_URL_PATH);
                $ext  = pathinfo($path, PATHINFO_EXTENSION);

                $res .= '<source src="' . $data['video']['file'] . '" type="video/' . $ext . '">';
                if ($ext == 'm4v') {
                    $res .= '<source src="' . $data['video']['file'] . '" type="video/mp4">';
                }

                if (!empty($data['video']['alternative_Video_size_large']) && !empty($data['video']['alternative_Video_size_large_url'])) {
                    $path = parse_url($data['video']['alternative_Video_size_large_url'], PHP_URL_PATH);
                    $ext  = pathinfo($path, PATHINFO_EXTENSION);
                    $res  .= '<source src="' . $data['video']['alternative_Video_size_large_url'] . '" type="video/' . $ext . '" size="' . $data['video']['alternative_Video_size_large_width'] . '">';
                }

                if (!empty($data['video']['alternative_Video_size_medium']) && !empty($data['video']['alternative_Video_size_medium_url'])) {
                    $path = parse_url($data['video']['alternative_Video_size_medium_url'], PHP_URL_PATH);
                    $ext  = pathinfo($path, PATHINFO_EXTENSION);
                    $res  .= '<source src="' . $data['video']['alternative_Video_size_medium_url'] . '" type="video/' . $ext . '" size="' . $data['video']['alternative_Video_size_medium_width'] . '">';
                }

                if (!empty($data['video']['transcript'])) {
                    $transcriptHtml = Self::get_fauvideo_transcript_tracks($data);
                    $res .= $transcriptHtml;
                }

                $res   .= __('Unfortunately, your browser does not support HTML5 video formats.', 'rrze-video');
                $res   .= ' ';
                $url   = !empty($data['url']) ? esc_url($data['url']) : '';
                $file  = !empty($data['video']['file']) ? esc_url($data['video']['file']) : '';
                $title = $data['video']['title'] ?? '';
                if ($url) {
                    $res .= sprintf(
                        /* translators: %s: URL of the video. */
                        __('Therefore call up the video %s from the FAU video portal.', 'rrze-video'),
                        '<a href="' . $url . '">' . $title . '</a>'
                    );
                } elseif ($file) {
                    $res .= 'Call the video file  directly.';
                    $res .= sprintf(
                        /* translators: %s: File name of the video. */
                        __('Call the video file %s directly.', 'rrze-video'),
                        '<a href="' . $file . '">' . $title . '</a>'
                    );
                }

                $res .= '</video>';

                if (!$showtitle) {
                    //Adds the visible title for the overlay
                    if (!empty($title)) {
                        $res .= '<p class="rrze-video-title rrze-video-hide" id="rrze-video-title-' . $id . '">' . $title . '</p>';
                    }
                }
            }
        } else {
            $res .= '<div class="alert clearfix clear alert-danger">';
            $res .= __('Video provider incorrectly defined.', 'rrze-video');
            $res .= '</div>';
            return $res;
        }

        if (($showdesc) && !empty($data['video']['description'])) {
            $res .= '<p class="desc">' . $data['video']['description'] . '</p>';
        }

        if ($showmeta) {
            $meta = '';

            if (!empty($data['video']['author_name'])) {
                $meta .= '<dt>' . __('Author', 'rrze-video') . '</dt><dd>';

                if (!empty($data['video']['author_url_0'])) {
                    $meta .= '<a href="' . $data['video']['author_url_0'] . '">';
                }

                $meta .= $data['video']['author_name'];
                if (!empty($data['video']['author_url_0'])) {
                    $meta .= '</a>';
                }
                $meta .=  '</dd>';
            }

            $url = !empty($data['url']) ? esc_url($data['url']) : '';
            $altVideofolienUrl = !empty($data['video']['alternative_VideoFolien_size_large']) ? esc_url($data['video']['alternative_VideoFolien_size_large']) : '';
            $altAudioUrl = !empty($data['video']['alternative_Audio']) ? esc_url($data['video']['alternative_Audio']) : '';

            if ($url) {
                $meta .= '<dt>' . __('Source', 'rrze-video') . '</dt><dd><a href="' . $url . '">' . $url . '</a></dd>';
            }

            if ($altVideofolienUrl && $altVideofolienUrl !== $url) {
                $meta .= '<dt>' . __('Video with presentation slides', 'rrze-video') . '</dt><dd><a href="' . $altVideofolienUrl . '">' . $altVideofolienUrl . '</a></dd>';
            }

            if ($altAudioUrl && $altAudioUrl !== $url) {
                $meta .= '<dt>' . __('Audio Format', 'rrze-video') . '</dt><dd><a href="' . $altAudioUrl . '">' . $altAudioUrl . '</a></dd>';
            }

            if (!empty($data['video']['provider_name'])) {
                $meta .= '<dt>' . __('Provider', 'rrze-video') . '</dt><dd>';
                if (!empty($data['video']['provider_url'])) {
                    $meta .= '<a href="' . $data['video']['provider_url'] . '">';
                }
                $meta .= $data['video']['provider_name'];

                if (!empty($data['video']['provider_url'])) {
                    $meta .= '</a>';
                }
                $meta .= '</dd>';
            }

            if (!empty($meta)) {
                $res .= '<dl class="meta">' . $meta . '</dl>';
            }
        } elseif ($showlink && $url) {
            $res .= '<p class="link">' . __('Source', 'rrze-video') . ': <a href="' . $url . '">' . $url . '</a>';

            if (!empty($data['video']['provider_videoindex_url'])) {
                $res .= '<br>' . __('This video is part of a video collection', 'rrze-video') . ': <a href="' . $data['video']['provider_videoindex_url'] . '">' . $data['video']['provider_videoindex_url'] . '</a>';
            }
            $res .= '</p>';
        }

        $res .= '</div>';
        return $res;
    }

    public function get_html_structuredmeta($data)
    {
        if (!empty($data['video']['title'])) {
            $res = '<meta itemprop="name" content="' . $data['video']['title'] . '">';
        }
        $poster = '';
        if (!empty($data['poster'])) {
            $poster = $data['poster'];
        } elseif (!empty($data['video']['preview_image'])) {
            $poster = $data['video']['preview_image'];
        } elseif (!empty($data['video']['thumbnail_url'])) {
            $poster = $data['video']['thumbnail_url'];
        }
        $lang = $hreflang = '';

        if (!empty($data['inLanguage'])) {
            $lang = $data['inLanguage'];
            $hreflang = explode("-", $lang)[0];
        } elseif (!empty($data['language'])) {
            $lang = $data['language'];
            $hreflang = explode("-", $lang)[0];
        }

        $res = empty($res) ? '' : $res;

        $res .= '<meta itemprop="image" content="' . $poster . '">';
        if (!empty($data['video']['upload_date'])) {
            $res .= '<meta itemprop="dateCreated" content="' . $data['video']['upload_date'] . '">';
        }
        if (!empty($data['video']['author_name'])) {
            $res .= '<meta itemprop="director" content="' . $data['video']['author_name'] . '">';
        }
        if ($hreflang) {
            $res .= '<meta itemprop="inLanguage" content="' . $hreflang . '">';
        }
        if (!empty($data['video']['provider_name'])) {
            $res .= '<meta itemprop="provider" content="' . $data['video']['provider_name'] . '">';
        }
        if (!empty($data['video']['thumbnail_url']) && ($data['video']['thumbnail_url'] != $poster)) {
            $res .= '<meta itemprop="thumbnailUrl" content="' . $data['video']['thumbnail_url'] . '">';
        }
        if (!empty($data['video']['duration'])) {
            $res .= '<meta itemprop="duration" content="' . $data['video']['duration'] . '">';
        }
        if (!empty($data['video']['version'])) {
            $res .= '<meta itemprop="version" content="' . $data['video']['version'] . '">';
        }
        if (!empty($data['video']['description']) && (!empty($data['video']['description']))) {
            $res .= '<meta itemprop="abstract" content="' . $data['video']['description'] . '">';
        }
        return $res;
    }

    /**
     * Enqueue scripts and styles.
     * @param boolean $plyr
     */
    public function enqueueFrontendStyles($plyr = true, $args = [], $id = '')
    {
        if ($id == '') {
            $id = $this->getRenderID();
        }
        wp_enqueue_style('rrze-video-plyr');
        if ($plyr) {
            wp_enqueue_script('rrze-video-plyr');
        }
    }

    /**
     * Iterates through available transcripts from FAUVideo oEmbed response and returns the needed track html response. The function assumes that the default track is German.
     * @param array $data
     * @return string
     * @since 3.4.5
     */
    public function get_fauvideo_transcript_tracks($data)
    {
        $transcriptKeys = ['transcript', 'transcript_en', 'transcript_de'];
        $outputTemp = '';
        $langKeys = [
            'de' => 'Deutsch',
            'en' => 'English',
            'es' => 'Español',
            'ut' => 'Unknown',
        ];

        if (empty($data['video'])) {
            return $outputTemp;
        }

        foreach ($transcriptKeys as $key) {
            if (isset($data['video'][$key]) && !empty($data['video'][$key]) && strpos($data['video'][$key], '.vtt')) {
                $langEvaluate = str_replace('transcript', '', $key); //Extract the language shorthand from the key
                if ($langEvaluate == '') {
                    if (isset($data['video']['inLanguage']) && !empty($data['video']['inLanguage'])) {
                        $langEvaluate = substr($data['video']['inLanguage'], 0, 2);
                    } else {
                        $langEvaluate = 'ut';
                    }

                    $lang = $langEvaluate;
                } else {
                    $lang = str_replace('_', '', $langEvaluate);
                }

                //Get the full language label
                $labelEvaluate = 'Deutsch';
                if (isset($langKeys[$lang])) {
                    $labelEvaluate = $langKeys[$lang];
                } else {
                    $lang = 'ut';
                    $labelEvaluate = $langKeys[$lang];
                }

                $url = $data['video'][$key];

                if (empty($outputTemp)) {
                    //Set the first track always as default
                    $outputTemp .= '<track kind="captions" src="' . $url . '" srclang="' . $lang . '" label="' . $labelEvaluate . '" default>';
                } else {
                    $trackTemp = '<track kind="captions" src="' . $url . '" srclang="' . $lang . '" label="' . $labelEvaluate . '">';

                    if (strpos($outputTemp, $url) === false) {
                        $outputTemp .= $trackTemp;
                    }
                }
            } else {
                //Helper::debug('RRZE Video: No or invalid transcript file found for key: ' . $key);
            }
        }

        return $outputTemp;
    }

    /**
     * Retrieves the right AspectRatio Class for FAU Video Embeds by checking user input
     * @param array $arguments 
     * @return String $class
     * @since 3.5.1
     */
    public function get_aspectratio_class($arguments)
    {
        if (empty($arguments['aspectratio'])) {
            return 'ar-16-9';
        } else {
            switch ($arguments['aspectratio']) {
                case ('4/3'):
                    return 'ar-4-3';
                    break;
                case ('21/9'):
                    return 'ar-21-9';
                    break;
                case ('1/1'):
                    return 'ar-1-1';
                    break;
                case ('2.35/1'):
                    return 'ar-234-1';
                    break;
                case ('2.40/1'):
                    return 'ar-240-1';
                    break;
                case ('9/16'):
                    return 'ar-9-16';
                    break;
                default:
                    return 'ar-16-9';
            }
        }
    }
}

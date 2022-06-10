<?php

namespace RRZE\Video;

defined('ABSPATH') || exit;

use RRZE\Video\OEmbed;
use RRZE\Video\IFrames;

class Player
{
    /**
     * Singleton instance.
     * @var mixed
     */
    private static $instance = null;

    private $counter = 1;

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

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->counter++;
    }

    public function get_player($arguments)
    {
        $content = '';
        if (empty($arguments)) {
            $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
            $content .= __('Error when displaying the video player: Insufficient data was transferred.', 'rrze-video');
            $content .= '</div>';
            return $content;
        }

        if (empty($arguments['url'])) {
            // Try to get URL by ID or by Random
            if (!empty($arguments['id']) && (intval($arguments['id']) > 0)) {
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
            } elseif (!empty($arguments['rand'])) {
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
        }

        if (!empty($arguments['url'])) {
            // check for oEmbed
            $isoembed = OEmbed::is_oembed_provider($arguments['url']);
            if (empty($isoembed)) {
                // OK, no fancy oEmbed... so let's see if its a boring iframe-provider...
                if (IFrames::is_iframe_provider($arguments['url'])) {
                    $framedata = IFrames::get_iframe($arguments['url']);

                    if (!empty($framedata['error'])) {
                        $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
                        $content .= '<strong>';
                        $content .= __('Error getting the video', 'rrze-video');
                        $content .= '</strong><br>';
                        $content .= $framedata['error'];
                        $content .= '</div>';
                    } elseif (empty($framedata['video'])) {
                        $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
                        $content .= '<strong>';
                        $content .= __('Error getting the video', 'rrze-video');
                        $content .= '</strong><br>';
                        $content .= __('Video data could not be obtained.', 'rrze-video');
                        $content .= '</div>';
                    } else {
                        $arguments['video'] = $framedata['video'];

                        $content .= '<div class="rrze-video">';
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
                        $this->enqueueFrontendStyles(false);
                    }
                } else {
                    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
                    $content .= '<strong>';
                    $content .= __('Unknown video source', 'rrze-video');
                    $content .= '</strong><br>';
                    $content .= __('The following address could not be assigned to a known video provider or it does not have a suitable interface for retrieving videos.', 'rrze-video');
                    $content .= ' ' . __('So please call up the video by directly following the link below:', 'rrze-video');
                    $content .= ' <a href="' . $arguments['url'] . '" rel="nofollow">' . $arguments['url'] . '</a>';
                    $content .= '</div>';
                }
            } else {
                $oembeddata = OEmbed::get_oembed_data($isoembed, $arguments['url']);

                if (!empty($oembeddata['error'])) {
                    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
                    $content .= '<strong>';
                    $content .= __('Error getting the video', 'rrze-video');
                    $content .= '</strong><br>';
                    $content .= $oembeddata['error'];
                    $content .= '</div>';
                } elseif (empty($oembeddata['video'])) {
                    $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
                    $content .= '<strong>';
                    $content .= __('Error getting the video', 'rrze-video');
                    $content .= '</strong><br>';
                    $content .= __('Video data could not be obtained.', 'rrze-video');
                    $content .= '</div>';
                } else {
                    $arguments['video'] = $oembeddata['video'];
                    $arguments['oembed_api_url'] = $oembeddata['oembed_api_url'] ?? '';
                    $arguments['oembed_api_error'] = $oembeddata['error'] ?? '';
                    $content .= $this->get_player_html($isoembed, $arguments);

                    $this->enqueueFrontendStyles();
                }
            }
        } else {
            $content .= '<div class="rrze-video alert clearfix clear alert-danger">';
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
        }
        return $content;
    }

    public function get_player_html($provider, $data, $id = '')
    {
        $res = '';
        $providerlist = OEmbed::get_known_provider();
        if (empty($provider) || empty($providerlist[$provider])) {
            $res .= '<div class="rrze-video alert clearfix clear alert-danger">';
            $res .= __('No valid video provider was found. As a result, the video cannot be played or could not be recognized.', 'rrze-video');
            $res .= '</div>';

            return $res;
        }

        if (!empty($data['error'])) {
            $res .= '<div class="rrze-video alert clearfix clear alert-danger">';
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


        $res .= '<div class="rrze-video';

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


        if ($id == '') {
            // create Random number to make a uniq class name
            // This is need to display more as one video embed in the same page
            $id = $this->counter++;
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
            $res .= '  src="https://player.vimeo.com/video/' . $data['video']['video_id'] . '?autoplay=0&loop=0&title=0&byline=0&portrait=0"';
            $res .= '  allowfullscreen';
            $res .= '  allowtransparency';
            $res .= '  allow="autoplay"';
            $res .= '></iframe>';
            $res .= '</div>';

            $res .= '</div>';
        } elseif ($provider == 'fau') {
            $classname = 'plyr-instance plyr-videonum-' . $id;
            $res .= '<video class="' . $classname . '" playsinline controls crossorigin="anonymous"';

            $plyrconfig = ' data-plyr-config=\'{ ';
            $plyrconfig .= '"preload": "none", ';
            $plyrconfig .= '"loadSprite": "false", ';
            $plyrconfig .= ' "iconUrl": "' . plugin()->getUrl('assets/plyr') . 'plyr.svg", ';
            $plyrconfig .= ' "blankVideo": "' . plugin()->getUrl('assets/plyr') . 'blank.mp4"';

            if (!empty($data['video']['title'])) {
                $plyrconfig .= ', "title": "' . $data['video']['title'] . '"';
            }
            $plyrconfig .= ' }\'';
            $res .= $plyrconfig;

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
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $res .= '<source src="' . $data['video']['file'] . '" type="video/' . $ext . '">';
            if ($ext == 'm4v') {
                $res .= '<source src="' . $data['video']['file'] . '" type="video/mp4">';
            }

            if (!empty($data['video']['alternative_Video_size_large']) && !empty($data['video']['alternative_Video_size_large_url'])) {
                $path = parse_url($data['video']['alternative_Video_size_large_url'], PHP_URL_PATH);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $res .= '<source src="' . $data['video']['alternative_Video_size_large_url'] . '" type="video/' . $ext . '" size="' . $data['video']['alternative_Video_size_large_width'] . '">';
            }

            if (!empty($data['video']['alternative_Video_size_medium']) && !empty($data['video']['alternative_Video_size_medium_url'])) {
                $path = parse_url($data['video']['alternative_Video_size_medium_url'], PHP_URL_PATH);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $res .= '<source src="' . $data['video']['alternative_Video_size_medium_url'] . '" type="video/' . $ext . '" size="' . $data['video']['alternative_Video_size_medium_width'] . '">';
            }

            if (!empty($data['video']['transcript'])) {
                $res .= '<track kind="captions" label="' . __('Audio transcription', 'rrze-video') . '" src="' . $data['video']['transcript'] . '" default';
                if ($hreflang) {
                    $res .= ' hreflang="' . $hreflang . '"';
                }
                $res .= '>';
            }

            $res .= __('Unfortunately, your browser does not support HTML5 video formats.', 'rrze-video');
            $res .= ' ';
            $url = $data['url'] ?? '';
            $file = $data['video']['file'] ?? '';
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

            $url = $data['url'] ?? '';
            if ($url) {
                $meta .= '<dt>' . __('Source', 'rrze-video') . '</dt><dd><a href="' . $url . '">' . $url . '</a></dd>';
            }

            if (!empty($data['video']['alternative_VideoFolien_size_large']) && ($data['video']['alternative_VideoFolien_size_large'] !== $data['url'])) {
                $meta .= '<dt>' . __('Video with presentation slides', 'rrze-video') . '</dt><dd><a href="' . $data['video']['alternative_VideoFolien_size_large'] . '">' . $data['video']['alternative_VideoFolien_size_large'] . '</a></dd>';
            }

            if (!empty($data['video']['alternative_Audio']) && ($data['video']['alternative_Audio'] !== $url)) {
                $meta .= '<dt>' . __('Audio Format', 'rrze-video') . '</dt><dd><a href="' . $data['video']['alternative_Audio'] . '">' . $data['video']['alternative_Audio'] . '</a></dd>';
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
    public function enqueueFrontendStyles($plyr = true)
    {
        wp_enqueue_style('rrze-video-plyr');
        if ($plyr) {
            wp_enqueue_script('rrze-video-plyr');
        }
    }
}

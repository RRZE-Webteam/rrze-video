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

        // Helper::debug($arguments);

        if (empty($arguments)) {
            return $this->handleError(__('Error when displaying the video player: Insufficient data was transferred.', 'rrze-video'));
        }

        if (!empty($arguments['secureclipid'])) {
            $content .= $this->processAPIEmbed($arguments, $id);
            $this->enqueueFrontendStyles(true, [], $id);
            return $content;
        }

        if (empty($arguments['url'])) {
            $this->getUrlByIdOrRandom($arguments);
        }

        if (!empty($arguments['url']) && empty($arguments['secureclipid'])) {
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

    private function processAPIEmbed($arguments, $id)
    {
        $token = get_option('rrze_video_api_key');
        if (empty($token)) {
            return $this->handleError(__('Error getting the video', 'rrze-video') . '<br>' . __('No API is stored inside the Video Plugin settings.', 'rrze-video'));
        }
        $clipId = $arguments['secureclipid'];
        $videoData = API::getStreamingURI($clipId);
        $vtt = $videoData['vtt'];
        $language = $videoData['language'];
        $title = $videoData['title'];
        $desc = $videoData['description'];

        $streamUrl = '';
        if (isset($videoData['url'])) {
            $streamUrl = $videoData['url'];
        }
        if (!empty($streamUrl)) {
            $arguments['url'] = $streamUrl;
            $arguments['vtt'] = $vtt;
            $arguments['language'] = $language;
            $arguments['video']['title'] = $title;
            $arguments['video']['description'] = $desc;

            $this->enqueueFrontendStyles(true, [], $id);

            return $this->get_player_html('fauApi', $arguments, $id);
        } else {
            return $this->handleError(__('Error getting the video', 'rrze-video') . '<br>' . __('Video data could not be obtained.', 'rrze-video'));
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

    /**
     * Evaluates and returns the appropriate poster image URL for a video.
     *
     * This function determines the correct poster image URL based on a prioritized list:
     * 1. Direct 'poster' attribute in the `$data` array.
     * 2. Preview image associated with the video.
     * 3. Thumbnail URL of the video.
     *
     * If none of these are available, the function may return `null` (as there's no default return value defined).
     *
     * @param array $data Array containing the video details and potential poster images.
     *      [
     *          'poster' => 'direct_poster_url',                        // Optional: Direct poster URL.
     *          'video' => [
     *              'preview_image' => 'preview_image_url',             // Optional: Preview image of the video.
     *              'thumbnail_url' => 'video_thumbnail_url'           // Optional: Thumbnail URL of the video.
     *          ]
     *      ]
     *
     * @param int|string $id Unique identifier for the video. (Currently not used within the function but passed as an argument.)
     *
     * @return string|null Returns the appropriate poster image URL or `null` if none is found.
     */
    private function evaluatePoster($data, $id)
    {
        if (!empty($data['poster'])) {
            $poster = $data['poster'];
        } elseif (!empty($data['video']['preview_image'])) {
            $poster = $data['video']['preview_image'];
        } elseif (!empty($data['video']['thumbnail_url'])) {
            $poster = $data['video']['thumbnail_url'];
        }
        return $poster;
    }

    /**
     * Evaluates and returns the escaped URL from the provided data array.
     *
     * This function checks for the presence of a 'url' key in the `$data` array.
     * If found, it escapes the URL using the `esc_url()` function, ensuring it's safe to use in output.
     * If the 'url' key is not present or is empty, it returns an empty string.
     *
     * @param array $data Array containing potential URLs.
     *      [
     *          'url' => 'http://example.com'   // Optional: The URL to be checked and escaped.
     *      ]
     *
     * @param int|string $id Unique identifier for the data. (Currently not used within the function but passed as an argument.)
     *
     * @return string Returns the escaped URL if present; otherwise, returns an empty string.
     */
    private function evaluateUrl($data, $id)
    {
        return !empty($data['url']) ? esc_url($data['url']) : '';
    }

    /**
     * Generates the HTML output for a video player based on the provided video data and provider.
     * 
     * This function constructs and returns an HTML string which comprises the video player, the title, 
     * description, and related metadata of the video. It first identifies the provider (e.g., YouTube, 
     * Vimeo, FAU, etc.) of the video and then delegates the HTML generation to the appropriate helper 
     * function. In addition to the video content, if specified in the `$data`, this function can render 
     * a title, a description, and a list of related metadata (like author, source, alternative formats, 
     * etc.).
     * 
     * @param string $provider  The video provider (e.g., "youtube", "vimeo", "fau").
     * @param array  $data      Associative array containing video details and other settings.
     * @param string $id        An optional ID to be used for this instance. If empty, a render ID is 
     *                          generated internally.
     * 
     * @return string           Returns the generated HTML string for the video player and its associated 
     *                          details.
     */
    private function get_player_html($provider, $data, $id = '')
    {
        $classname = 'plyr-instance plyr-videonum-' . $id;
        if ($id == '') {
            $id = $this->getRenderID();
        }
        $res = [];
        $res[] = '<div class="rrze-video rrze-video-container-' . $id;

        if (!empty($data['class'])) {
            $res[] = ' ' . $data['class'];
        }

        if (!empty($data['textAlign'])) {
            $res[] = ' ' . $data['textAlign'];
        }
        $res[] = '">';

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

        if ($this->evaluateShowValues($data, $id)['showtitle']) {
            $res[] = $beforetag . $data['video']['title'] . $aftertag;
        } elseif (!empty($data['widgetargs']['title'])) {
            $res[] = $beforetag . $data['widgetargs']['title'] . $aftertag;
        }

        if ($provider !== 'fauApi') {
            $providerList = OEmbed::get_known_provider();
            if (empty($provider) || empty($providerList[$provider])) {
                return $this->generateErrorContent($id, __('No valid video provider was found. As a result, the video cannot be played or could not be recognized.', 'rrze-video'), '');
            }
        }

        switch ($provider) {
            case 'youtube':
                $res[] = $this->generate_youtube_html($data, $id);
                break;
            case 'vimeo':
                $res[] = $this->generate_vimeo_html($data, $id);
                break;
            case 'fau':
                $res[] = $this->generate_fau_html($data, $id);
                break;
            case 'fauApi':
                $res[] = $this->generate_fauApi_html($data, $id);
                break;
            default:
                $res[] = $this->generateErrorContent($id, __('Video provider incorrectly defined.', 'rrze-video'), '');
        }

        if ($this->evaluateShowValues($data, $id)['showdesc'] && !empty($data['video']['description'])) {
            $res[] = '<p class="desc">' . $data['video']['description'] . '</p>';
        }

        if ($this->evaluateShowValues($data, $id)['showmeta']) {
            $meta = [];

            if (!empty($data['video']['author_name'])) {
                $meta[] = '<dt>' . __('Author', 'rrze-video') . '</dt><dd>';

                if (!empty($data['video']['author_url_0'])) {
                    $meta[] = '<a href="' . $data['video']['author_url_0'] . '">';
                }

                $meta[] = $data['video']['author_name'];
                if (!empty($data['video']['author_url_0'])) {
                    $meta[] = '</a>';
                }
                $meta[] =  '</dd>';
            }

            $url = !empty($data['url']) ? esc_url($data['url']) : '';
            $altVideofolienUrl = !empty($data['video']['alternative_VideoFolien_size_large']) ? esc_url($data['video']['alternative_VideoFolien_size_large']) : '';
            $altAudioUrl = !empty($data['video']['alternative_Audio']) ? esc_url($data['video']['alternative_Audio']) : '';

            if ($url) {
                $meta[] = '<dt>' . __('Source', 'rrze-video') . '</dt><dd><a href="' . $url . '">' . $url . '</a></dd>';
            }

            if ($altVideofolienUrl && $altVideofolienUrl !== $url) {
                $meta[] = '<dt>' . __('Video with presentation slides', 'rrze-video') . '</dt><dd><a href="' . $altVideofolienUrl . '">' . $altVideofolienUrl . '</a></dd>';
            }

            if ($altAudioUrl && $altAudioUrl !== $url) {
                $meta[] = '<dt>' . __('Audio Format', 'rrze-video') . '</dt><dd><a href="' . $altAudioUrl . '">' . $altAudioUrl . '</a></dd>';
            }

            if (!empty($data['video']['provider_name'])) {
                $meta[] = '<dt>' . __('Provider', 'rrze-video') . '</dt><dd>';
                if (!empty($data['video']['provider_url'])) {
                    $meta[] = '<a href="' . $data['video']['provider_url'] . '">';
                }
                $meta[] = $data['video']['provider_name'];

                if (!empty($data['video']['provider_url'])) {
                    $meta[] = '</a>';
                }
                $meta[] = '</dd>';
            }

            if (!empty($meta)) {
                $res[] = '<dl class="meta">' . implode("\n", $meta) . '</dl>';
            }
        } elseif ($this->evaluateShowValues($data, $id)['showlink'] && $this->evaluateUrl($data, $id)) {
            $res[] = '<p class="link">' . __('Source', 'rrze-video') . ': <a href="' . $this->evaluateUrl($data, $id) . '">' . $this->evaluateUrl($data, $id) . '</a>';

            if (!empty($data['video']['provider_videoindex_url'])) {
                $res[] = '<br>' . __('This video is part of a video collection', 'rrze-video') . ': <a href="' . $data['video']['provider_videoindex_url'] . '">' . $data['video']['provider_videoindex_url'] . '</a>';
            }
            $res[] = '</p>';
        }

        $res[] = '</div>';
        return implode("\n", $res);
    }

    /**
     * Generates the HTML for Vimeo embeds.
     *
     * This function will construct the necessary HTML markup to embed a Vimeo video
     * using structured data and the given video data. It also handles optional video
     * title information and creates unique class names based on the video ID.
     *
     * @param array $data Array containing the video details.
     *      [
     *          'video' => [
     *              'title' => 'The video title',       // Optional: Title of the video.
     *              'video_id' => '12345678'            // Required: The Vimeo video ID.
     *          ]
     *      ]
     *
     * @param int|string $id Unique identifier to append to class name.
     *
     * @return string The generated HTML markup for the Vimeo embed.
     */
    private function generate_vimeo_html($data, $id)
    {
        $classname = 'plyr-videonum-' . $id;
        $res = [];
        $res[] = '<div class="vimeo-video ' . $classname . '"';
        $res[] = ' itemscope itemtype="https://schema.org/Movie"';
        $res[] = '>';
        $res[] = $this->get_html_structuredmeta($data);
        $res[] = '<div class="plyr__video-embed">';
        $res[] = '<iframe';
        if (!empty($data['video']['title'])) {
            $res[] = ' title="' . esc_html($data['video']['title']) . '"';
        }
        $res[] = '  src="https://player.vimeo.com/video/' . $data['video']['video_id'] . '?autoplay=0&loop=0&title=0&byline=0&portrait=0"';
        $res[] = '  allowfullscreen';
        $res[] = '  allowtransparency';
        $res[] = '  allow="autoplay"';
        $res[] = '></iframe>';
        $res[] = '</div>';
        $res[] = '</div>';
        return implode("\n", $res);
    }

    /**
     * Generates the HTML for YouTube embeds.
     *
     * This function constructs the necessary HTML markup to embed a YouTube video
     * using structured data and the provided video data. The function handles optional 
     * video title information and creates unique class names based on the given video ID.
     * The generated embed link is privacy-enhanced using the "youtube-nocookie" domain.
     *
     * @param array $data Array containing the video details.
     *      [
     *          'video' => [
     *              'title' => 'The video title',       // Optional: Title of the video.
     *              'v' => 'abcdefg'                    // Required: The YouTube video ID (part after "v=" in YouTube URLs).
     *          ]
     *      ]
     *
     * @param int|string $id Unique identifier used to generate a distinct class name for the video.
     *
     * @return string The generated HTML markup for the YouTube embed.
     */
    private function generate_youtube_html($data, $id)
    {
        $res = [];
        $aspectRatio = isset($data['aspectratio']) ? $data['aspectratio'] : '16/9';
        $classname = 'plyr-videonum-' . $id;

        if ($aspectRatio !== '9/16') {
            $res[] = '<div class="youtube-video ' . $classname . '"';
            $res[] = ' itemscope itemtype="https://schema.org/Movie"';
            $res[] = '>';
        }
        $res[] = $this->get_html_structuredmeta($data);
        if ($aspectRatio !== '9/16') {
            $res[] = '<div class="plyr__video-embed">';
        }
        $res[] = '<iframe';
        if ($aspectRatio === '9/16') {
            $res[] = 'width="315"';
            $res[] = 'height="560"';
        }
        if (!empty($data['video']['title'])) {
            $res[] = ' title="' . esc_html($data['video']['title']) . '"';
        }
        $res[] = '  src="https://www.youtube-nocookie.com/embed/' . $data['video']['v'] . '?rel=0&showinfo=0&iv_load_policy=3&modestbranding=1"';
        $res[] = ' frameborder="0"';
        $res[] = '  allowfullscreen';
        $res[] = '  allowtransparency';
        $res[] = '  allow="autoplay"';
        $res[] = '></iframe>';
        if ($aspectRatio !== '9/16') {
            $res[] = '</div>';
            $res[] = '</div>';
        }

        return implode("\n", $res);
    }

    /**
     * Generates HTML for FAU video or audio players based on the provided data.
     *
     * This function dynamically creates either an audio or video player's HTML.
     * It checks the type of media ('audio' or 'video') from the provided `$data` and structures
     * the HTML accordingly. The function also handles various video properties like poster images,
     * multiple video sources, and transcripts. If the video or audio is not supported by the browser,
     * a fallback message is provided.
     *
     * @param array $data Contains information required for generating the HTML, which can include:
     *      - 'video': {
     *            'type': 'audio'|'video',
     *            'file': 'URL of the media',
     *            'width': 'Width of the video',
     *            'height': 'Height of the video',
     *            'title': 'Title of the media',
     *            ... (other video-related keys)
     *        }
     *      - 'url': 'Direct URL of the video or audio',
     *      - 'inLanguage' or 'language': 'Language of the media, e.g., "en-US"',
     *      ... (other potential keys)
     *
     * @param int|string $id Unique identifier for the media data. Used for creating specific class names and HTML attributes.
     *
     * @return string Returns the generated HTML for the FAU video or audio player.
     */
    private function generate_fauApi_html($data, $id)
    {
        $res = [];
        // $poster = $this->evaluatePoster($data, $id);
        $path = $data['url'];
        $lang = $hreflang = '';


        $classname = 'plyr-instance plyr-videonum-' . $id . ' ' . Self::get_aspectratio_class($data);
        $res[] = '<video preload="none" class="' . $classname . '" playsinline controls crossorigin="anonymous"';
        $res[] = ' data-video-title-id="' . $id . '"';  // Pass the id to the player
        $res[] = $this->generatePlayerConfig($data, $id);

        // if ($poster) {
        //     $res[] = ' poster="' . $poster . '" data-poster="' . $poster . '"';
        // }

        if (!empty($data['video']['width'])) {
            $res[] = ' width="' . $data['video']['width'] . '"';
        }

        if (!empty($data['video']['height'])) {
            $res[] = ' height="' . $data['video']['height'] . '"';
        }

        $res[] = ' itemscope itemtype="https://schema.org/Movie"';
        $res[] = '>';

        $res[] = $this->get_html_structuredmeta($data);

        // $res[] = '<source src="' . $data['url'] . '" type="video/mp4>"';
        $res[] = '<source src="' . $data['url'] . '" type="video/mp4" size="576">';

        if (!empty($data['vtt'])) {
            $res[] = '<track kind="captions" src="' . $data['vtt'] . '" srclang="' . $data['language'] . '" label="'. __("Untertitel") .'" default>';
        }

        $res[] = __('Unfortunately, your browser does not support HTML5 video formats.', 'rrze-video');
        $res[] = ' ';
        $url = !empty($data['url']) ? esc_url($data['url']) : '';
        $file = !empty($data['video']['file']) ? esc_url($data['video']['file']) : '';
        $title = $data['video']['title'] ?? '';
        if ($url) {
            $res[] = sprintf(
                /* translators: %s: URL of the video. */
                __('Therefore call up the video %s from the FAU video portal.', 'rrze-video'),
                '<a href="' . $url . '">' . $title . '</a>'
            );
        } elseif ($file) {
            $res[] = 'Call the video file  directly.';
            $res[] = sprintf(
                /* translators: %s: File name of the video. */
                __('Call the video file %s directly.', 'rrze-video'),
                '<a href="' . $file . '">' . $title . '</a>'
            );
        }

        $res[] = '</video>';

        /*------ Adds the visible title for the overlay ------*/
        if (!$this->evaluateShowValues($data, $id)['showtitle']) {
            if (!empty($title)) {
                $res[] = '<p class="rrze-video-title rrze-video-hide" id="rrze-video-title-' . $id . '">' . $title . '</p>';
            }
        }
        $res[] = '<p>'.__('Dieses Video wurde bereitgestellt durch <a href="https://www.fau.tv" rel="nofollow">das Videoportal der FAU</a>.').'</p>';
        return implode("\n", $res);
    }


    /**
     * Generates HTML for FAU video or audio players based on the provided data.
     *
     * This function dynamically creates either an audio or video player's HTML.
     * It checks the type of media ('audio' or 'video') from the provided `$data` and structures
     * the HTML accordingly. The function also handles various video properties like poster images,
     * multiple video sources, and transcripts. If the video or audio is not supported by the browser,
     * a fallback message is provided.
     *
     * @param array $data Contains information required for generating the HTML, which can include:
     *      - 'video': {
     *            'type': 'audio'|'video',
     *            'file': 'URL of the media',
     *            'width': 'Width of the video',
     *            'height': 'Height of the video',
     *            'title': 'Title of the media',
     *            ... (other video-related keys)
     *        }
     *      - 'url': 'Direct URL of the video or audio',
     *      - 'inLanguage' or 'language': 'Language of the media, e.g., "en-US"',
     *      ... (other potential keys)
     *
     * @param int|string $id Unique identifier for the media data. Used for creating specific class names and HTML attributes.
     *
     * @return string Returns the generated HTML for the FAU video or audio player.
     */
    private function generate_fau_html($data, $id)
    {
        $res = [];
        $poster = $this->evaluatePoster($data, $id);
        $path = parse_url($data['video']['file'], PHP_URL_PATH);
        $ext  = pathinfo($path, PATHINFO_EXTENSION);
        $lang = $hreflang = '';

        if (isset($data['video']['type']) && $data['video']['type'] == 'audio' && isset($data['video']['file'])) {
            $classname = 'plyr-instance plyr-videonum-' . $id;
            $res[] = '<audio preload="none" class="' . $classname . '" controls crossorigin="anonymous">';
            $res[] = '<source src="' . $data['video']['file'] . '" type="audio/mp3" />';
            $res[] = '</audio>';
        } else {
            $classname = 'plyr-instance plyr-videonum-' . $id . ' ' . Self::get_aspectratio_class($data);
            $res[] = '<video preload="none" class="' . $classname . '" playsinline controls crossorigin="anonymous"';
            $res[] = ' data-video-title-id="' . $id . '"';  // Pass the id to the player
            $res[] = $this->generatePlayerConfig($data, $id);

            if ($poster) {
                $res[] = ' poster="' . $poster . '" data-poster="' . $poster . '"';
            }

            if (!empty($data['video']['width'])) {
                $res[] = ' width="' . $data['video']['width'] . '"';
            }

            if (!empty($data['video']['height'])) {
                $res[] = ' height="' . $data['video']['height'] . '"';
            }

            $res[] = ' itemscope itemtype="https://schema.org/Movie"';
            $res[] = '>';

            $res[] = $this->get_html_structuredmeta($data);

            $res[] = '<source src="' . $data['video']['file'] . '" type="video/' . $ext . '">';
            if ($ext == 'm4v') {
                $res[] = '<source src="' . $data['video']['file'] . '" type="video/mp4">';
            }

            /*--------- Add the alternative Video Sources if thex exist ----------*/
            if (!empty($data['video']['alternative_Video_size_large']) && !empty($data['video']['alternative_Video_size_large_url'])) {
                $path = parse_url($data['video']['alternative_Video_size_large_url'], PHP_URL_PATH);
                $ext  = pathinfo($path, PATHINFO_EXTENSION);
                $res[] = '<source src="' . $data['video']['alternative_Video_size_large_url'] . '" type="video/' . $ext . '" size="' . $data['video']['alternative_Video_size_large_width'] . '">';
            }

            if (!empty($data['video']['alternative_Video_size_medium']) && !empty($data['video']['alternative_Video_size_medium_url'])) {
                $path = parse_url($data['video']['alternative_Video_size_medium_url'], PHP_URL_PATH);
                $ext  = pathinfo($path, PATHINFO_EXTENSION);
                $res[] = '<source src="' . $data['video']['alternative_Video_size_medium_url'] . '" type="video/' . $ext . '" size="' . $data['video']['alternative_Video_size_medium_width'] . '">';
            }

            if (!empty($data['video']['transcript'])) {
                $transcriptHtml = Self::get_fauvideo_transcript_tracks($data);
                $res[] = $transcriptHtml;
            }

            $res[] = __('Unfortunately, your browser does not support HTML5 video formats.', 'rrze-video');
            $res[] = ' ';
            $url = !empty($data['url']) ? esc_url($data['url']) : '';
            $file = !empty($data['video']['file']) ? esc_url($data['video']['file']) : '';
            $title = $data['video']['title'] ?? '';
            if ($url) {
                $res[] = sprintf(
                    /* translators: %s: URL of the video. */
                    __('Therefore call up the video %s from the FAU video portal.', 'rrze-video'),
                    '<a href="' . $url . '">' . $title . '</a>'
                );
            } elseif ($file) {
                $res[] = 'Call the video file  directly.';
                $res[] = sprintf(
                    /* translators: %s: File name of the video. */
                    __('Call the video file %s directly.', 'rrze-video'),
                    '<a href="' . $file . '">' . $title . '</a>'
                );
            }

            $res[] = '</video>';

            /*------ Adds the visible title for the overlay ------*/
            if (!$this->evaluateShowValues($data, $id)['showtitle']) {
                if (!empty($title)) {
                    $res[] = '<p class="rrze-video-title rrze-video-hide" id="rrze-video-title-' . $id . '">' . $title . '</p>';
                }
            }
        }
        return implode("\n", $res);
    }

    /**
     * Evaluates the display preferences for a given media item based on the provided data.
     *
     * The function processes the 'show' key in `$data`, which contains a comma-separated list of
     * values indicating which aspects of the media item should be displayed. The possible values 
     * are 'title', 'info', 'meta', 'desc', and 'link'.
     *
     * - 'title' indicates whether to show the title.
     * - 'info' indicates whether to show meta, link, and description.
     * - 'meta' indicates whether to show the metadata.
     * - 'desc' indicates whether to show the description.
     * - 'link' indicates whether to show the link.
     * 
     * The function returns an associative array with keys 'showtitle', 'showmeta', 'showdesc', and 'showlink'
     * indicating the user's preferences.
     *
     * @param array $data Contains the display preferences and other data related to the media item.
     *      - 'show': A comma-separated string specifying which elements to display.
     *
     * @param int|string $id Unique identifier for the media item. Not directly used in this function but might be used in extended or future implementations.
     *
     * @return array Returns an associative array indicating which elements to display:
     *      - 'showtitle': bool,
     *      - 'showmeta': bool,
     *      - 'showdesc': bool,
     *      - 'showlink': bool
     */
    private function evaluateShowValues($data, $id)
    {
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
        return [
            'showtitle' => $showtitle,
            'showmeta' => $showmeta,
            'showdesc' => $showdesc,
            'showlink' => $showlink
        ];
    }

    /**
     * Generates the configuration for the Plyr video player based on the provided data.
     *
     * This function constructs a Plyr configuration string in a JSON-like format.
     * It sets default values such as 'preload' and 'loadSprite', and also fetches URLs
     * for the 'iconUrl' and 'blankVideo' from the plugin assets.
     *
     * If a video title exists in the `$data`, it will be included in the configuration.
     *
     * The function returns a string which can be directly added as a `data-plyr-config` attribute
     * in an HTML tag for Plyr player instantiation.
     *
     * @param array $data Contains information related to the media item, including potential video title.
     *      - 'video': array Contains video details.
     *          - 'title': string (optional) The title of the video.
     *
     * @param int|string $id Unique identifier for the media item. Not directly used in this function but might be used in extended or future implementations.
     *
     * @return string Returns the Plyr configuration string to be used as a data attribute.
     */
    private function generatePlayerConfig($data, $id)
    {
        $plyrconfig = [];
        $plyrconfig[] = ' data-plyr-config=\'{ ';
        $plyrconfig[] = '"preload": "none", ';
        $plyrconfig[] = '"loadSprite": "false", ';
        $plyrconfig[] = ' "iconUrl": "' . plugin()->getUrl('assets/plyr') . 'plyr.svg", ';
        $plyrconfig[] = ' "blankVideo": "' . plugin()->getUrl('assets/plyr') . 'blank.mp4"';

        if (!empty($data['video']['title'])) {
            $plyrconfig[] = ', "title": "' . $data['video']['title'] . '"';
        }
        $plyrconfig[] .= ' }\'';

        return implode("\n", $plyrconfig);
    }

    /**
     * Generates a series of meta tags containing structured data for a video.
     *
     * This function constructs a set of HTML meta tags that encapsulate structured data 
     * about a video, based on the provided `$data`. The structured data is defined using 
     * the `itemprop` attribute and includes details such as the video's title, poster image, 
     * creation date, author, and other metadata.
     *
     * @param array $data Contains details related to the video. Key details include:
     *      - 'video': array Contains video metadata.
     *          - 'title': string (optional) The title of the video.
     *          - 'preview_image': string (optional) The preview image URL for the video.
     *          - 'thumbnail_url': string (optional) The thumbnail URL for the video.
     *          - 'upload_date': string (optional) The date when the video was uploaded.
     *          - 'author_name': string (optional) The name of the video's author.
     *          - 'provider_name': string (optional) The name of the video's provider.
     *          - 'duration': string (optional) The duration of the video.
     *          - 'version': string (optional) The version of the video.
     *          - 'description': string (optional) A description of the video.
     *      - 'poster': string (optional) URL of the video's poster image.
     *      - 'inLanguage': string (optional) The language of the video content.
     *      - 'language': string (optional) The language of the video content. Used as fallback if 'inLanguage' is not set.
     *
     * @return string Returns a string containing meta tags with structured data for the video.
     */
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
     * Enqueues the necessary styles and scripts for the frontend video player.
     *
     * This function is responsible for adding the required CSS and JavaScript resources 
     * for rendering the video player on the frontend. It uses the WordPress `wp_enqueue_style` 
     * and `wp_enqueue_script` functions to load the relevant files. 
     *
     * @param bool   $plyr   Determines whether the Plyr script should be enqueued. Default is true.
     * @param array  $args   An optional array of arguments. Currently unused but can be expanded for future functionality.
     * @param string $id     An optional unique identifier for the render process. If not provided, a default will be fetched using `getRenderID()`.
     *
     * @return void
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
     * Generates the HTML <track> elements for video transcripts.
     * 
     * This function processes the provided data to produce the corresponding 
     * <track> elements that represent the video's transcript files. These tracks
     * are usually used for subtitles or captions in HTML5 video players. The 
     * function supports multiple transcript files for different languages, and 
     * can produce multiple <track> elements.
     *
     * @param array $data Array of video data, which may contain one or more transcript files.
     *
     * @return string A concatenated string of the generated <track> elements, or an empty string if no valid transcripts are found.
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
                // Helper::debug('RRZE Video: No or invalid transcript file found for key: ' . $key);
            }
        }

        return $outputTemp;
    }

    /**
     * Retrieves the appropriate aspect ratio class for FAU video embeds.
     * 
     * This function determines the correct CSS class to apply based on 
     * the aspect ratio provided in the arguments. If no aspect ratio or an 
     * unrecognized aspect ratio is provided, it defaults to 'ar-16-9'.
     *
     * Available aspect ratios and their corresponding CSS classes:
     * - 4/3     -> ar-4-3
     * - 21/9    -> ar-21-9
     * - 1/1     -> ar-1-1
     * - 2.35/1  -> ar-234-1
     * - 2.40/1  -> ar-240-1
     * - 9/16    -> ar-9-16
     * 
     * @param array $arguments Associative array with the 'aspectratio' key potentially set to a string representing the desired aspect ratio.
     * @return string Returns the corresponding CSS class string based on the provided aspect ratio.
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

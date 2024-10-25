<?php

namespace RRZE\Video\Player;

defined('ABSPATH') || exit;

use RRZE\Video\Player\IFrames;
use RRZE\Video\Utils\Helper;
use RRZE\Video\Utils;
use RRZE\Video\Providers;

class Player
{
    /**
     * Singleton instance.
     * @var mixed
     */
    private static $instance = null;

    private $counter = 0;
    private $id = '';

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
            Helper::debug('Error when displaying the video player: Insufficient data was transferred.');
            Helper::debug($arguments);
            return Utils\Error::handleError(__('Error when displaying the video player: Insufficient data was transferred.', 'rrze-video'));
        }

        if (!empty($arguments['secureclipid'])) {
            $content .= $this->processAPIEmbed($arguments, $id);
            $this->enqueueFrontendStyles(true, [], $id);
            return $content;
        }

        if (empty($arguments['url'])) {
            Utils\Utils::getUrlByIdOrRandom($arguments);
        }

        if (!empty($arguments['url']) && empty($arguments['secureclipid'])) {
            if ($isoembed = OEmbed::is_oembed_provider($arguments['url'])) {
                $content .= $this->processOEmbed($isoembed, $arguments, $id);
                $this->enqueueFrontendStyles(true, [], $id);
            } else if (IFrames::is_iframe_provider($arguments['url'])) {
                $content .= $this->processIFrame($arguments, $id);
            } else {
                $content .= Utils\Error::handleError(__('Unknown video source', 'rrze-video'));
            }
        } else {
            $content .= Utils\Error::handleNoVideoError($arguments, $id);
        }

        return $content;
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
            return Utils\Error::handleError(__('Error getting the video', 'rrze-video') . '<br>' . $oembeddata['error']);
        } elseif (empty($oembeddata['video'])) {
            return Utils\Error::handleError(__('Error getting the video', 'rrze-video') . '<br>' . __('Video data could not be obtained.', 'rrze-video'));
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
            return Utils\Error::handleError(
                __('Error getting the video', 'rrze-video') . '<br>' .
                __('No API Key is stored inside the Video Plugin settings.', 'rrze-video')
            );
        }
    
        $clipId = $arguments['secureclipid'];
        $videoData = Providers\FAUAPI::getStreamingURI($clipId);
    
        // Check if $videoData is null
        if ($videoData === null) {
            return Utils\Error::handleError(
                __('Error getting the video', 'rrze-video') . '<br>' .
                __('The video is not accessible from outside the FAU Network. Please use the FAU VPN for video access.', 'rrze-video')
            );
        }
    
        // Now it's safe to access $videoData elements
        $vtt = $videoData['vtt'];
        $language = $videoData['language'];
        $title = $videoData['title'];
        $desc = $videoData['description'];
        $poster = $videoData['poster'];
    
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
            $arguments['poster'] = $poster;
    
            $this->enqueueFrontendStyles(true, [], $id);
            return $this->get_player_html('fauApi', $arguments, $id);
        } else {
            return Utils\Error::handleError(
                __('Error getting the video', 'rrze-video') . '<br>' .
                __('Video data could not be obtained.', 'rrze-video')
            );
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
                $content .= Utils\Error::generateErrorContent($id, __('Error getting the video', 'rrze-video'), $framedata['error']);
            } elseif (empty($framedata['video'])) {
                $content .= Utils\Error::generateErrorContent($id, __('Error getting the video', 'rrze-video'), __('Video data could not be obtained.', 'rrze-video'));
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
            $content .= Utils\Error::generateErrorContent($id, __('Unknown video source', 'rrze-video'), __('The following address could not be assigned to a known video provider or it does not have a suitable interface for retrieving videos.', 'rrze-video') . ' ' . __('So please call up the video by directly following the link below:', 'rrze-video') . ' <a href="' . $arguments['url'] . '" rel="nofollow">' . $arguments['url'] . '</a>');
        }

        return $content;
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

        if (Utils\Utils::evaluateShowValues($data, $id)['showtitle']) {
            $res[] = $beforetag . $data['video']['title'] . $aftertag;
        } elseif (!empty($data['widgetargs']['title'])) {
            $res[] = $beforetag . $data['widgetargs']['title'] . $aftertag;
        }

        if ($provider !== 'fauApi') {
            $providerList = OEmbed::get_known_provider();
            if (empty($provider) || empty($providerList[$provider])) {
                return Utils\Error::generateErrorContent($id, __('No valid video provider was found. As a result, the video cannot be played or could not be recognized.', 'rrze-video'), '');
            }
        }

        switch ($provider) {
            case 'youtube':
                $res[] = Providers\YouTube::generate_html($data, $id);
                break;
            case 'vimeo':
                $res[] = Providers\Vimeo::generate_vimeo_html($data, $id);
                break;
            case 'fau':
                $res[] = Providers\FAUTV::generate_fau_html($data, $id);
                break;
            case 'fauApi':
                $res[] = Providers\FAUTV::generate_fauApi_html($data, $id);
                break;
            default:
                $res[] = Utils\Error::generateErrorContent($id, __('Video provider incorrectly defined.', 'rrze-video'), '');
        }

        if (Utils\Utils::evaluateShowValues($data, $id)['showdesc'] && !empty($data['video']['description'])) {
            $res[] = '<p class="desc">' . $data['video']['description'] . '</p>';
        }

        if (Utils\Utils::evaluateShowValues($data, $id)['showmeta']) {
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

            $url = isset( $data['url'] ) ? esc_url( $data['url'] ) : '';
            $altVideofolienUrl = isset( $data['video']['alternative_VideoFolien_size_large'] ) && is_string( $data['video']['alternative_VideoFolien_size_large'] ) ? esc_url( $data['video']['alternative_VideoFolien_size_large'] ) : '';
            $altAudioUrl = isset( $data['video']['alternative_Audio'] ) && is_string( $data['video']['alternative_Audio'] ) ? esc_url( $data['video']['alternative_Audio'] ) : '';

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
        } elseif (Utils\Utils::evaluateShowValues($data, $id)['showlink'] && Utils\Utils::evaluateUrl($data, $id)) {
            $res[] = '<p class="link">' . __('Source', 'rrze-video') . ': <a href="' . Utils\Utils::evaluateUrl($data, $id) . '">' . Utils\Utils::evaluateUrl($data, $id) . '</a>';

            if (!empty($data['video']['provider_videoindex_url'])) {
                $res[] = '<br>' . __('This video is part of a video collection', 'rrze-video') . ': <a href="' . $data['video']['provider_videoindex_url'] . '">' . $data['video']['provider_videoindex_url'] . '</a>';
            }
            $res[] = '</p>';
        }

        $res[] = '</div>';
        return implode("\n", $res);
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
            wp_enqueue_script('rrze-video-front-js');
        }
    }
}

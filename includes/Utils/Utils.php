<?php

namespace RRZE\Video\Utils;

defined('ABSPATH') || exit;
/**
 * Utility functions to declutter the Player class
 */
class Utils
{
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
    public static function get_aspectratio_class($arguments)
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
    public static function get_fauvideo_transcript_tracks($data)
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
                    $outputTemp .= '<track src="' . $url . '" kind="subtitles" label="' . $labelEvaluate . '" srclang="' . $lang . '" default data-type="vtt" />';
                } else {
                    $trackTemp = '<track src="' . $url . '" kind="subtitles" label="' . $labelEvaluate . '" srclang="' . $lang . '" data-type="vtt" />';

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

    // Helper function to convert duration to ISO 8601 format
    public static function format_duration_iso8601($duration)
    {
        $parts = explode(':', $duration);
        return sprintf('PT%uM%uS', $parts[1], $parts[2]);
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
    public static function evaluateShowValues($data, $id)
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
    public static function evaluatePoster($data, $id)
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
    public static function evaluateUrl($data, $id)
    {
        return !empty($data['url']) ? esc_url($data['url']) : '';
    }

    /**
     * Fetches the URL of a video by its post ID.
     * 
     * If the post with the provided ID is of type 'video', it retrieves its URL 
     * and optional poster image.
     *
     * @param array $arguments Associative array containing 'id' key to fetch the URL.
     */
    public static function getUrlById(&$arguments)
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
    public static function getUrlRandomly(&$arguments)
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
     * Determines the URL for the video based on an ID or fetches a random video URL.
     * 
     * @param array $arguments Associative array containing 'id' or 'rand' key for fetching.
     */
    public static function getUrlByIdOrRandom(&$arguments)
    {
        if (!empty($arguments['id']) && (intval($arguments['id']) > 0)) {
            Utils::getUrlById($arguments);
        } elseif (!empty($arguments['rand'])) {
            Utils::getUrlRandomly($arguments);
        }
    }
}

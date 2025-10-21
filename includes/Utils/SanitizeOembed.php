<?php
namespace RRZE\Video\Utils;
defined( 'ABSPATH' ) || exit;

/**
 * Sanitize oEmbed Payloads
 */
class SanitizeOembed {

    private const DEFAULT_POLICY = 'drop';

    private const URL_HOST_WHITELIST = [
        'cdn.video.uni-erlangen.de',
        'vp-cdn-balance.rrze.uni-erlangen.de',
        'vp-cdn-balance.rrze.de',
        'www.fau.tv',
        'api.video.uni-erlangen.de',
    ];

    private static array $URL_KEYS = [
        'file',
        'preview_image',
        'poster',
        'thumbnail_url',
        'transcript',
        'transcript_en',
        'transcript_de',
        'provider_url',
        'provider_videoindex_url',
        'alternative_Video_size_small',
        'alternative_Video_size_small_url',
        'alternative_Video_size_medium',
        'alternative_Video_size_medium_url',
        'alternative_Video_size_large',
        'alternative_Video_size_large_url',
        'alternative_Audio',
    ];

    private static array $TEXT_KEYS = [
        'type',
        'version',
        'title',
        'author_name',
        'inLanguage',
        'provider_name',
        'name',
    ];

    private static array $TEXTAREA_KEYS = [
        'description',
    ];

    private static array $INT_KEYS = [
        'width',
        'height',
        'thumbnail_width',
        'thumbnail_height',
        'alternative_Video_size_small_width',
        'alternative_Video_size_small_height',
        'alternative_Video_size_medium_width',
        'alternative_Video_size_medium_height',
        'alternative_Video_size_large_width',
        'alternative_Video_size_large_height',
    ];

    private static array $DATE_KEYS = [
        'upload_date',
    ];

    private static array $DURATION_KEYS = [
        'duration',
    ];

    public static function sanitize_oembed_data( $data, $keep_unknown_keys = false ) {
        Helper::debug('ok');
        if ( ! is_array( $data ) ) {
            return [];
        }

        if ( array_key_exists( 'html', $data ) ) {
            unset( $data['html'] );
        }

        $result = [];

        foreach ( $data as $key => $value ) {

            if ( in_array( $key, self::$URL_KEYS, true ) ) {
                $result[ $key ] = self::sanitize_url_nullable( $value );
                continue;
            }

            if ( in_array( $key, self::$TEXT_KEYS, true ) ) {
                $result[ $key ] = self::sanitize_text_nullable( $value );
                continue;
            }

            if ( in_array( $key, self::$TEXTAREA_KEYS, true ) ) {
                $result[ $key ] = self::sanitize_textarea_nullable( $value );
                continue;
            }

            if ( in_array( $key, self::$INT_KEYS, true ) ) {
                $result[ $key ] = self::sanitize_int_nullable( $value );
                continue;
            }

            if ( in_array( $key, self::$DATE_KEYS, true ) ) {
                $result[ $key ] = self::sanitize_datetime_nullable( $value );
                continue;
            }

            if ( in_array( $key, self::$DURATION_KEYS, true ) ) {
                $result[ $key ] = self::sanitize_duration_hms( $value );
                continue;
            }

            if ( 'error' === $key ) {
                $result[ $key ] = is_string( $value ) ? wp_kses_post( $value ) : '';
                continue;
            }

            if ( $keep_unknown_keys || self::DEFAULT_POLICY !== 'drop' ) {
                $result[ $key ] = self::sanitize_unknown( $value );
            }
        }

        return $result;
    }

    /* =========================
     *   Helper
     * ========================= */

    private static function sanitize_url_nullable( $value ): string {
        if ( empty( $value ) || ! is_string( $value ) ) {
            return '';
        }

        $value = trim( $value );
        $value = preg_replace( '#^(https?://[^/]+)//+#i', '$1/', $value );

        // Only allow https
        $url = esc_url_raw( $value, [ 'http', 'https' ] );
        if ( ! is_string( $url ) || $url === '' ) {
            return '';
        }

        $url = preg_replace( '#^http://#i', 'https://', $url );

        $host = wp_parse_url( $url, PHP_URL_HOST );
        if ( empty( $host ) ) {
            return '';
        }
        $host = strtolower( $host );
        if ( ! in_array( $host, self::URL_HOST_WHITELIST, true ) ) {
            return '';
        }

        return $url;
    }

    private static function sanitize_text_nullable( $value ): string {
        if ( empty( $value ) || ! is_string( $value ) ) {
            return '';
        }
        return sanitize_text_field( trim( $value ) );
    }

    private static function sanitize_textarea_nullable( $value ): string {
        if ( empty( $value ) || ! is_string( $value ) ) {
            return '';
        }
        return sanitize_textarea_field( trim( $value ) );
    }

    private static function sanitize_int_nullable( $value ): int {
        if ( is_numeric( $value ) ) {
            $i = (int) $value;
            return max( 0, $i );
        }
        return 0;
    }

    private static function sanitize_datetime_nullable( $value ): string {
        if ( empty( $value ) || ! is_string( $value ) ) {
            return '';
        }
        $ts = strtotime( $value );
        if ( false === $ts ) {
            return '';
        }
        return gmdate( 'Y-m-d H:i:s', $ts );
    }

    private static function sanitize_duration_hms( $value ): string {
        if ( empty( $value ) ) {
            return '';
        }
        if ( is_string( $value ) && preg_match( '/^\d{2}:\d{2}:\d{2}$/', $value ) ) {
            return $value;
        }
        if ( is_numeric( $value ) ) {
            $secs = max( 0, (int) $value );
            $h = floor( $secs / 3600 );
            $m = floor( ($secs % 3600) / 60 );
            $s = $secs % 60;
            return sprintf( '%02d:%02d:%02d', $h, $m, $s );
        }
        return '';
    }

    private static function duration_to_seconds( $hms ): int {
        if ( is_string( $hms ) && preg_match( '/^(\d{2}):(\d{2}):(\d{2})$/', $hms, $m ) ) {
            return ( (int) $m[1] ) * 3600 + ( (int) $m[2] ) * 60 + ( (int) $m[3] );
        }
        return 0;
    }

    private static function sanitize_unknown( $value ) {
        switch ( self::DEFAULT_POLICY ) {
            case 'string':
                return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
            case 'raw':
                return $value;
            case 'drop':
            default:
                return null;
        }
    }
}

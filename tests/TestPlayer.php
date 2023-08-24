<?php
use RRZE\Video\Player;
use RRZE\Video\Helper;
use WP_UnitTestCase;
use PHPUnit\Framework\Assert;
require_once dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';
require_once dirname( dirname( __FILE__ ) ) . '/tests/bootstrap.php';

/**
 * Class SampleTest
 *
 * @package rrze_Video
 */
class TestPlayer extends WP_UnitTestCase {

    private $player;

    public function setUp(): void {
        parent::setUp();
        $this->player = RRZE\Video\Player::instance();
    }    

    public function tearDown(): void {
        parent::tearDown();
        $this->player = null;
    }

   /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_only_en_transcript() {
        Helper::debug($this->player);

        $data = [
            'video' => [
                'transcript' => 'https://cdn2.fau.tv/symlinks/fdfe6990-3654-4d45-aabf-79c47b0d4bae.vtt',
                'inLanguage' => 'en-US',
                'transcript_de' => '',
                'transcript_en' => '',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        Helper::debug($result);
        $expected = '<track kind="captions" src="https://cdn2.fau.tv/symlinks/fdfe6990-3654-4d45-aabf-79c47b0d4bae.vtt" srclang="en" label="English" default>';

        Assert::assertEquals($expected, $result);
    }

    /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_no_transcript() {

        $data = [
            'video' => [
                'inLanguage' => 'en-US',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '';

        Assert::assertEquals($expected, $result);
    }

       /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_only_de_transcript() {
        $data = [
            'video' => [
                'transcript' => 'https://cdn2.fau.tv/symlinks/fdfe6990-3654-4d45-aabf-79c47b0d4bae.vtt',
                'inLanguage' => 'de-DE',
                'transcript_de' => '',
                'transcript_en' => '',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '<track kind="captions" src="https://cdn2.fau.tv/symlinks/fdfe6990-3654-4d45-aabf-79c47b0d4bae.vtt" srclang="de" label="Deutsch" default>';

        Assert::assertEquals($expected, $result);
    }

    /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_mixed_transcript() {
        $data = [
            'video' => [
                'transcript' => 'englisch.vtt',
                'inLanguage' => 'en-US',
                'transcript_de' => 'deutsch.vtt',
                'transcript_en' => 'englisch.vtt',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '<track kind="captions" src="englisch.vtt" srclang="en" label="English" default>';
        $expected .= '<track kind="captions" src="deutsch.vtt" srclang="de" label="Deutsch">';

        Assert::assertEquals($expected, $result);
    }

        /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_ut_transcript() {
        $data = [
            'video' => [
                'transcript' => 'englisch.ut',
                'inLanguage' => 'en-US',
                'transcript_de' => 'deutsch.ut',
                'transcript_en' => 'englisch.ut',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '';

        Assert::assertEquals($expected, $result);
    }


        /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_unknown_CountryCode() {
        $data = [
            'video' => [
                'transcript' => 'englisch.vtt',
                'inLanguage' => 'zy-ZY',
                'transcript_de' => 'deutsch.vtt',
                'transcript_en' => 'englisch.vtt',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '<track kind="captions" src="englisch.vtt" srclang="ut" label="Unknown" default><track kind="captions" src="deutsch.vtt" srclang="de" label="Deutsch">';

        Assert::assertEquals($expected, $result);
    }

            /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_missing_data() {
        $data = [
            'video' => [
                'transcript' => 'englisch.vtt',
                'inLanguage' => 'en-US',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '<track kind="captions" src="englisch.vtt" srclang="en" label="English" default>';

        Assert::assertEquals($expected, $result);
    }

            /**
     *
     * @test
     */
    public function test_get_fauvideo_transcript_tracks_with_further_missing_data() {
        $data = [
            'video' => [
                'transcript_en' => '',
            ],
        ];

        $result = $this->player->get_fauvideo_transcript_tracks($data);
        $expected = '';

        Assert::assertEquals($expected, $result);
    }
    
}
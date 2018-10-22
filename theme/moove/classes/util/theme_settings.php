<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Mustache helper to load a theme configuration.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\util;

use theme_config;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper to load a theme configuration.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_settings {

    /**
     * Get config theme footer itens
     *
     * @return array
     */
    public function footer_items() {
        $theme = theme_config::load('moove');

        $templatecontext = [];

        $footersettings = [
            'facebook', 'twitter', 'googleplus', 'linkedin', 'youtube', 'instagram', 'getintouchcontent',
            'gettitlestayintouch', 'website', 'mobile', 'mail', 'address'
        ];

        foreach ($footersettings as $setting) {
            if (!empty($theme->settings->$setting)) {
                $templatecontext[$setting] = $theme->settings->$setting;
            }
        }

        $footericons = [
            'facebook_icon', 'twitter_icon', 'youtube_icon'
        ];

        foreach ($footericons as $icon) {
            if (!empty($theme->settings->$setting)) {
                $templatecontext[$icon] = $theme->setting_file_url($icon, $icon);
            }
        }

        $templatecontext['disablebottomfooter'] = false;
        if (!empty($theme->settings->disablebottomfooter)) {
            $templatecontext['disablebottomfooter'] = true;
        }

        return $templatecontext;
    }

    /**
     * Get config theme slideshow
     *
     * @return array
     */
    public function slideshow() {
        global $OUTPUT;

        $theme = theme_config::load('moove');

        $templatecontext['sliderenabled'] = $theme->settings->sliderenabled;

        if (empty($templatecontext['sliderenabled'])) {
            return $templatecontext;
        }

        $slidercount = $theme->settings->slidercount;

        for ($i = 1, $j = 0; $i <= $slidercount; $i++, $j++) {
            $sliderimage = "sliderimage{$i}";
            $slidertitle = "slidertitle{$i}";
            $slidercap = "slidercap{$i}";

            $templatecontext['slides'][$j]['key'] = $j;
            $templatecontext['slides'][$j]['active'] = false;

            $image = $theme->setting_file_url($sliderimage, $sliderimage);
            if (empty($image)) {
                $image = $OUTPUT->image_url('slide_default', 'theme');
            }
            $templatecontext['slides'][$j]['image'] = $image;
            $templatecontext['slides'][$j]['title'] = $theme->settings->$slidertitle;
            $templatecontext['slides'][$j]['caption'] = $theme->settings->$slidercap;

            if ($i === 1) {
                $templatecontext['slides'][$j]['active'] = true;
            }
        }

        return $templatecontext;
    }

    /**
     * Get config theme marketing itens
     *
     * @return array
     */
    public function marketing_items() {
        global $OUTPUT;

        $theme = theme_config::load('moove');

        $templatecontext = [];

        for ($i = 1; $i < 5; $i++) {
            $marketingicon = 'marketing' . $i . 'icon';
            $marketingheading = 'marketing' . $i . 'heading';
            $marketingsubheading = 'marketing' . $i . 'subheading';
            $marketingcontent = 'marketing' . $i . 'content';
            $marketingurl = 'marketing' . $i . 'url';

            $templatecontext[$marketingicon] = $OUTPUT->image_url('icon_default', 'theme');
            if (!empty($theme->settings->$marketingicon)) {
                $templatecontext[$marketingicon] = $theme->setting_file_url($marketingicon, $marketingicon);
            }

            $templatecontext[$marketingheading] = '';
            if (!empty($theme->settings->$marketingheading)) {
                $templatecontext[$marketingheading] = theme_moove_get_setting($marketingheading, true);
            }

            $templatecontext[$marketingsubheading] = '';
            if (!empty($theme->settings->$marketingsubheading)) {
                $templatecontext[$marketingsubheading] = theme_moove_get_setting($marketingsubheading, true);
            }

            $templatecontext[$marketingcontent] = '';
            if (!empty($theme->settings->$marketingcontent)) {
                $templatecontext[$marketingcontent] = theme_moove_get_setting($marketingcontent, 'format_html');
            }

            $templatecontext[$marketingurl] = '';
            if (!empty($theme->settings->$marketingurl)) {
                $templatecontext[$marketingurl] = $theme->settings->$marketingurl;
            }
        }

        return $templatecontext;
    }

    /**
     * Get the frontpage numbers
     *
     * @return array
     */
    public function numbers() {
        global $DB;

        $templatecontext['numberusers'] = $DB->count_records('user', array('deleted' => 0, 'suspended' => 0)) - 1;
        $templatecontext['numbercourses'] = $DB->count_records('course', array('visible' => 1)) - 1;
        $templatecontext['numberactivities'] = $DB->count_records('course_modules');

        return $templatecontext;
    }

    /**
     * Get config theme sponsors logos and urls
     *
     * @return array
     */
    public function sponsors() {
        $theme = theme_config::load('moove');

        $templatecontext['sponsorstitle'] = $theme->settings->sponsorstitle;
        $templatecontext['sponsorssubtitle'] = $theme->settings->sponsorssubtitle;

        $sponsorscount = $theme->settings->sponsorscount;

        for ($i = 1, $j = 0; $i <= $sponsorscount; $i++, $j++) {
            $sponsorsimage = "sponsorsimage{$i}";
            $sponsorsurl = "sponsorsurl{$i}";

            $image = $theme->setting_file_url($sponsorsimage, $sponsorsimage);
            if (empty($image)) {
                continue;
            }

            $templatecontext['sponsors'][$j]['image'] = $image;
            $templatecontext['sponsors'][$j]['url'] = $theme->settings->$sponsorsurl;

        }

        return $templatecontext;
    }

    /**
     * Get config theme clients logos and urls
     *
     * @return array
     */
    public function clients() {
        $theme = theme_config::load('moove');

        $templatecontext['clientstitle'] = $theme->settings->clientstitle;
        $templatecontext['clientssubtitle'] = $theme->settings->clientssubtitle;

        $clientscount = $theme->settings->clientscount;

        for ($i = 1, $j = 0; $i <= $clientscount; $i++, $j++) {
            $clientsimage = "clientsimage{$i}";
            $clientsurl = "clientsurl{$i}";

            $image = $theme->setting_file_url($clientsimage, $clientsimage);
            if (empty($image)) {
                continue;
            }

            $templatecontext['clients'][$j]['image'] = $image;
            $templatecontext['clients'][$j]['url'] = $theme->settings->$clientsurl;

        }

        return $templatecontext;
    }

    /**
     * Get config theme slideshow for foursection
     *
     * @return array
     */
    public function slide_foursection_show(){
        global $OUTPUT;

        $theme = theme_config::load('moove');

        $templatecontext['slider_foursection_enabled'] = $theme->settings->slider_foursection_enabled;

        if (empty($templatecontext['slider_foursection_enabled'])) {
            return $templatecontext;
        }

        $slider_foursection_count = $theme->settings->slider_foursection_count;

        for ($i = 1, $j = 0; $i <= $slider_foursection_count; $i++, $j++) {
            $sliderimage = "slider_foursection_image{$i}";
            $slidertitle = "slider_foursection_title{$i}";
            $slidercap = "slider_foursection_cap{$i}";
            $imgurl = "slider_foursection_imageurl{$i}";

            $templatecontext['slides_foursection'][$j]['key'] = $j;
            $templatecontext['slides_foursection'][$j]['active'] = false;

            $image = $theme->setting_file_url($sliderimage, $sliderimage);
            if (empty($image)) {
                $image = $OUTPUT->image_url('slide_default', 'theme');
            }
            $templatecontext['slides_foursection'][$j]['image'] = $image;
            $templatecontext['slides_foursection'][$j]['title'] = $theme->settings->$slider_foursection_title;
            $templatecontext['slides_foursection'][$j]['caption'] = $theme->settings->$slider_foursection_cap;
            $templatecontext['slides_foursection'][$j]['imgurl'] = $theme->settings->$imgurl;

            if ($i === 1) {
                $templatecontext['slides_foursection'][$j]['active'] = true;
            }
        }

        return $templatecontext;

    }
}

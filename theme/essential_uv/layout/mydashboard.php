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
 * A two column layout for the moove theme.
 *
 * @package   theme_essential_uv
 * @copyright 2018 Iader Garcia
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// require_once($CFG->root . '/theme/essential_uv/');
require_once(\theme_essential_uv\toolbox::get_tile_file('pagesettings'));

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');
} else {
    $navdraweropen = false;
    $draweropenright = false;
}

// Se incluyen las preferencias y configuraciones visuales
require_once(\theme_essential_uv\toolbox::get_tile_file('analytics'));

$path_site = preg_replace("(https?:)", "", $CFG->wwwroot);
$path_logo = \theme_essential_uv\toolbox::get_setting('logo', 'format_file_url');

// Iconos para ios
$iphone_icon_path = $OUTPUT->pix_url('homeicon/iphone', 'theme');
$iphone_icon_link = "<link rel='apple-touch-icon' sizes='57x57' href='".$iphone_icon_path."'/>";
$ipad_icon_path = $OUTPUT->pix_url('homeicon/ipad', 'theme');
$ipad_icon_link = "<link rel='apple-touch-icon' sizes='72x72' href='".$ipad_icon_path."'/>";
$iphone_retina_icon_path = $OUTPUT->pix_url('homeicon/iphone_retina', 'theme');
$iphone_retina_icon_link = "<link rel='apple-touch-icon' sizes='114x114' href='".$iphone_retina_icon_path."'/>";
$ipad_retina_icon_path = $OUTPUT->pix_url('homeicon/ipad_retina', 'theme');
$ipad_retina_icon_link = "<link rel='apple-touch-icon' sizes='114x114' href='".$ipad_retina_icon_path."'/>";

if (\theme_essential_uv\toolbox::get_setting('analyticsenabled')) {
    $analytics = \theme_essential_uv\toolbox::get_setting('analytics');
    if ($analytics === "piwik") {
        $tracking = require_once(\theme_essential_uv\toolbox::get_tile_file('piwik'));
    } else if ($analytics === "guniversal") {
        $tracking = require_once(\theme_essential_uv\toolbox::get_tile_file('guniversal'));
    }
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

$bodyattributes = $OUTPUT->body_attributes($bodyclasses);
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

// Título del curso
$coursetitleposition = \theme_essential_uv\toolbox::get_setting('coursetitleposition');
$course_title = '';

if (empty($coursetitleposition)) {
    $coursetitleposition = 'within';
}
if ($coursetitleposition == 'above') {
    $course_title = $OUTPUT->course_title(false);
}

// Footer del curso
if (empty($PAGE->layout_options['nocoursefooter'])) {
    $course_content_footer = $OUTPUT->course_content_footer();
}else{
    $course_content_footer = '';
}

// Región inferior de la página
$pagebottomregion = \theme_essential_uv\toolbox::has_page_bottom_region();

// Settings navbar
$oldnavbar = \theme_essential_uv\toolbox::get_setting('oldnavbar');

// Order courses

// if(!is_siteadmin()){
    $OUTPUT->main_content(' asdf');
// }

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'hasdrawertoggle' => true,
    'navdraweropen' => $navdraweropen,
    'draweropenright' => $draweropenright,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'is_siteadmin' => is_siteadmin(),

    'title_nav_bar' => $OUTPUT->get_title('navbar'),
    'custom_menu_goto_bottom' => $OUTPUT->custom_menu_goto_bottom(),
    'iphone_icon_link' => $iphone_icon_link,
    'ipad_icon_link' => $ipad_icon_link,
    'iphone_retina_icon_link' => $iphone_retina_icon_link,
    'ipad_retina_icon_link' => $ipad_retina_icon_link,
    'tracking_analitycs' => $tracking,
    'path_site' => $path_site,
    'path_logo' => $path_logo,
    'google_plus_icon_path' => $OUTPUT->render_social_network('googleplus'),
    'twitter_icon_path' => $OUTPUT->render_social_network('twitter'),
    'facebook_icon_path' => $OUTPUT->render_social_network('facebook'),
    'linkedin_icon_path' => $OUTPUT->render_social_network('linkedin'),
    'youtube_icon_path' => $OUTPUT->render_social_network('youtube'),
    'flickr_icon_path' => $OUTPUT->render_social_network('flickr'),
    'pinterest_icon_path' => $OUTPUT->render_social_network('pinterest'),
    'instagram_icon_path' => $OUTPUT->render_social_network('instagram'),
    'vk_icon_path' => $OUTPUT->render_social_network('vk'),
    'skype_icon_path' => $OUTPUT->render_social_network('skype'),
    'website_icon_path' => $OUTPUT->render_social_network('website'),
    'social_networks_string' => get_string('socialnetworks', 'theme_essential_uv'),

    'left_nav_bar' => $OUTPUT->navbar(),
    'page_top_header' => $OUTPUT->page_top_header(),
    'page_heading_button' => $OUTPUT->page_heading_button(),
    'essential_uv_blocks_1' => $OUTPUT->essential_uv_blocks('header', 'row-fluid', 'aside', 'headerblocksperrow'),
    'essential_uv_blocks_2' => $OUTPUT->essential_uv_blocks('page-top', 'row-fluid', 'aside', 'pagetopblocksperrow'),
    'essential_uv_blocks_3' => $OUTPUT->essential_uv_blocks('side-pre', 'span3 desktop-first-column'),
    'essential_uv_blocks_4' => $OUTPUT->essential_uv_blocks('side-pre', 'span3'),

    'essential_uv_edit_button' => $OUTPUT->essential_uv_edit_button('footer'),
    'blocks_footer_left' => $OUTPUT->essential_uv_blocks('footer-left'),
    'blocks_footer_middle' => $OUTPUT->essential_uv_blocks('footer-middle'),
    'blocks_footer_right' => $OUTPUT->essential_uv_blocks('footer-right'),
    'string_back_to_top' => get_string('backtotop', 'theme_essential_uv'),

    'pagebottomregion' => $pagebottomregion,
    'hasboringlayout' => $hasboringlayout,
    'left' => $left,

    'course_title' => $course_title,
    'course_content_header' => $OUTPUT->course_content_header(),

    'oldnavbar' => $oldnavbar

];

$PAGE->requires->js_call_amd('theme_essential_uv/header', 'init');
$PAGE->requires->js_call_amd('theme_essential_uv/footer', 'init');

if (\theme_essential_uv\toolbox::not_lte_ie9()) {
    
    $PAGE->requires->js_call_amd('theme_essential_uv/navbar', 'init', array('data' => array('oldnavbar' => $oldnavbar)));
    if ($oldnavbar) {
        // Only need this to change the classes when scrolling when the navbar is in the old position.
        $PAGE->requires->js_call_amd('theme_essential_uv/affix', 'init');
    }
}

echo $OUTPUT->render_from_template('theme_essential_uv/mydashboard', $templatecontext);

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
 * Overriden theme boost core renderer.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\output;

use html_writer;
use custom_menu_item;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use action_menu;
use pix_icon;
use theme_config;
use core_text;
use help_icon;
use context_system;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Renders the custom menu
     *
     * @param custom_menu $menu
     * @return mixed
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;

        if (!$menu->has_children()) {
            return '';
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /**
     * Renders the lang menu
     *
     * @return mixed
     */
    public function render_lang_menu() {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu;

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }

            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }

            if (isset($context)) {
                return $this->render_from_template('theme_moove/lang_menu', $context);
            }
        }
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function mydashboard_admin_header() {
        global $PAGE;

        $html = html_writer::start_div('row');
        $html .= html_writer::start_div('col-xs-12 p-a-1');

        $pageheadingbutton = $this->page_heading_button();
        if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= html_writer::start_div('clearfix w-100 pull-xs-left', array('id' => 'page-navbar'));
            $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button');
            $html .= html_writer::end_div();
        } else if ($pageheadingbutton) {
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button nonavbar pull-xs-right m-r-1');
        }
        $html .= html_writer::end_div(); // End .row.
        $html .= html_writer::end_div(); // End .col-xs-12.

        return $html;
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $SITE;

        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        $context->errorformatted = $this->error_text($context->error);

        $context->logourl = $this->get_logo();
        $context->sitename = format_string($SITE->fullname, true, array('context' => \context_course::instance(SITEID)));

        return $this->render_from_template('core/login', $context);
    }

    /**
     * Gets the logo to be rendered.
     *
     * The priority of get log is: 1st try to get the theme logo, 2st try to get the theme logo
     * If no logo was found return false
     *
     * @return mixed
     */
    public function get_logo() {
        if ($this->should_display_theme_logo()) {
            return $this->get_theme_logo_url();
        }

        $url = $this->get_logo_url();
        if ($url) {
            return $url->out(false);
        }

        return false;
    }

    /**
     * Outputs the pix url base
     *
     * @return string an URL.
     */
    public function get_pix_image_url_base() {
        global $CFG;

        return $CFG->wwwroot . "/theme/moove/pix";
    }

    /**
     * Whether we should display the main theme logo in the navbar.
     *
     * @return bool
     */
    public function should_display_theme_logo() {
        $logo = $this->get_theme_logo_url();

        return !empty($logo);
    }

    /**
     * Outputs the favicon urlbase.
     *
     * @return string an url
     */
    public function favicon() {
        $theme = theme_config::load('moove');

        $favicon = $theme->setting_file_url('favicon', 'favicon');

        if (!empty(($favicon))) {
            return $favicon;
        }

        return parent::favicon();
    }

    /**
     * Get the main logo URL.
     *
     * @return string
     */
    public function get_theme_logo_url() {
        $theme = theme_config::load('moove');

        return $theme->setting_file_url('logo', 'logo');
    }

    /**
     * Return the site identity providers
     *
     * @return mixed
     */
    public function get_identity_providers() {
        global $CFG;

        $authsequence = get_enabled_auth_plugins(true);

        require_once($CFG->libdir . '/authlib.php');

        $identityproviders = \auth_plugin_base::get_identity_providers($authsequence);

        return $identityproviders;
    }

    /**
     * Verify whether the site has identity providers
     *
     * @return mixed
     */
    public function has_identity_providers() {
        global $CFG;

        $authsequence = get_enabled_auth_plugins(true);

        require_once($CFG->libdir . '/authlib.php');

        $identityproviders = \auth_plugin_base::get_identity_providers($authsequence);

        return !empty($identityproviders);
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = '';
            if (!$loginpage) {
                $returnstr .= "<a class='btn btn-login-top d-lg-none' href=\"$loginurl\">" . get_string('login') . '</a>';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = '';

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;

            // Adds username to the first item of usermanu.
            $userinfo = new stdClass();
            $userinfo->itemtype = 'text';
            $userinfo->title = $user->firstname . ' ' . $user->lastname;
            $userinfo->url = new moodle_url('/user/profile.php', array('id' => $user->id));
            $userinfo->pix = 'i/user';

            array_unshift($opts->navitems, $userinfo);

            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'text':
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall')),
                            $value->title,
                            array('class' => 'text-username')
                        );

                        $am->add($al);
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::tag(
            'li',
            $this->render($am),
            array('class' => $usermenuclasses)
        );
    }

    /**
     * Secure login info.
     *
     * @return string
     */
    public function secure_login_info() {
        return $this->login_info(false);
    }

    /**
     * Implementation of user image rendering.
     *
     * @param help_icon $helpicon A help icon instance
     * @return string HTML fragment
     */
    public function render_help_icon(help_icon $helpicon) {
        $context = $helpicon->export_for_template($this);
        // Solving the issue - "Your progress" help tooltip in course home page displays in outside the screen display.
        // Check issue https://github.com/willianmano/moodle-theme_moove/issues/5.
        if ($helpicon->identifier === 'completionicons' && $helpicon->component === 'completion') {
            $context->ltr = right_to_left();
        }

        return $this->render_from_template('core/help_icon', $context);
    }

    /**
     * Returns a search box.
     *
     * @param  string $identifier The search box wrapper div id, defaults to an autogenerated one.
     * @return string HTML with the search form hidden by default.
     */
    public function search_box($identifier = false) {
        global $CFG;

        // Accessing $CFG directly as using \core_search::is_global_search_enabled would
        // result in an extra included file for each site, even the ones where global search
        // is disabled.
        if (empty($CFG->enableglobalsearch) || !has_capability('moodle/search:query', context_system::instance())) {
            return '';
        }

        if ($identifier == false) {
            $identifier = uniqid();
        } else {
            // Needs to be cleaned, we use it for the input id.
            $identifier = clean_param($identifier, PARAM_ALPHANUMEXT);
        }

        // JS to animate the form.
        $this->page->requires->js_call_amd('core/search-input', 'init', array($identifier));

        $iconattrs = array(
                        'class' => 'icon-magnifier',
                        'title' => get_string('search', 'search'),
                        'aria-label' => get_string('search', 'search'),
                        'aria-hidden' => 'true');
        $searchicon = html_writer::tag('i', '', $iconattrs);

        $formattrs = array('class' => 'search-input-form', 'action' => $CFG->wwwroot . '/search/index.php');
        $inputattrs = array('type' => 'text', 'name' => 'q', 'placeholder' => get_string('search', 'search'),
            'size' => 13, 'tabindex' => -1, 'id' => 'id_q_' . $identifier, 'class' => 'form-control');

        $contents = html_writer::tag('label', get_string('enteryoursearchquery', 'search'),
            array('for' => 'id_q_' . $identifier, 'class' => 'accesshide')) . html_writer::tag('input', '', $inputattrs);

        $btnclose = '<a class="close-search"><i class="fa fa-times"></i></a>';

        $searchinput = html_writer::tag('form', $contents . $btnclose, $formattrs);

        return html_writer::tag('div',
                                $searchicon . $searchinput,
                                array('class' => 'moove-search-input nav-link', 'id' => $identifier));
    }

    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        $output = parent::standard_head_html();

        // Add google analytics code.
        $googleanalyticscode = "<script>
                                    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};
                                    ga.l=+new Date;ga('create', 'GOOGLE-ANALYTICS-CODE', 'auto');
                                    ga('send', 'pageview');
                                </script>
                                <script async src='https://www.google-analytics.com/analytics.js'></script>";

        $theme = theme_config::load('moove');

        if (!empty($theme->settings->googleanalytics)) {
            $output .= str_replace("GOOGLE-ANALYTICS-CODE", trim($theme->settings->googleanalytics), $googleanalyticscode);
        }

        return $output;
    }

    /**
     * Try to return the first image on course summary files, otherwise returns a default image.
     *
     * @return string HTML fragment.
     */
    public function courseheaderimage() {
        global $CFG, $COURSE, $DB;

        $course = $DB->get_record('course', ['id' => $COURSE->id]);

        require_once($CFG->libdir. '/coursecatlib.php');

        $course = new \course_in_list($course);

        $courseimage = '';
        $imageindex = 1;
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();

            $url = new moodle_url("$CFG->wwwroot/pluginfile.php" . '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), ['forcedownload' => !$isimage]);

            if ($isimage) {
                $courseimage = $url;
            }

            if ($imageindex == 2) {
                break;
            }

            $imageindex++;
        }

        if (empty($courseimage)) {
            $courseimage = $this->get_pix_image_url_base() . "/default_coursesummary.jpg";
        }

        // Create html for header.
        $html = html_writer::start_div('headerbkg');

        $html .= html_writer::start_div('withimage', array(
            'style' => 'background-image: url("' . $courseimage . '"); background-size: cover; background-position:center;
            width: 100%; height: 100%;'
        ));
        $html .= html_writer::end_div(); // End withimage inline style div.

        $html .= html_writer::end_div();

        return $html;
    }

    /**
     * The standard tags (typically performance information and validation links,
     * if we are in developer debug mode) that should be output in the footer area
     * of the page. Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_footer_html() {
        global $CFG, $SCRIPT;

        $output = '<div class="plugins_standard_footer_html">';
        if (during_initial_install()) {
            return $output;
        }

        $pluginswithfunction = get_plugins_with_function('standard_footer_html', 'lib.php');
        foreach ($pluginswithfunction as $plugins) {
            foreach ($plugins as $function) {
                if ($function === 'tool_mobile_standard_footer_html') {
                    $output .= $this->get_mobileappurl();

                    continue;
                }

                $output .= $function();
            }
        }

        $output .= $this->unique_performance_info_token;
        if ($this->page->devicetypeinuse == 'legacy') {
            // The legacy theme is in use print the notification.
            $output .= html_writer::tag('div', get_string('legacythemeinuse'), array('class' => 'legacythemeinuse'));
        }

        // Get links to switch device types (only shown for users not on a default device)
        $output .= $this->theme_switch_links();

        if (!empty($CFG->debugpageinfo)) {
            $output .= '<div class="performanceinfo pageinfo">This page is: ' . $this->page->debug_summary() . '</div>';
        }

        if (debugging(null, DEBUG_DEVELOPER) and has_capability('moodle/site:config', context_system::instance())) {
            // Add link to profiling report if necessary.
            if (function_exists('profiling_is_running') && profiling_is_running()) {
                $txt = get_string('profiledscript', 'admin');
                $title = get_string('profiledscriptview', 'admin');
                $url = $CFG->wwwroot . '/admin/tool/profiling/index.php?script=' . urlencode($SCRIPT);
                $link = '<a title="' . $title . '" href="' . $url . '">' . $txt . '</a>';
                $output .= '<div class="profilingfooter">' . $link . '</div>';
            }
            $purgeurl = new moodle_url('/admin/purgecaches.php', array('confirm' => 1,
                'sesskey' => sesskey(), 'returnurl' => $this->page->url->out_as_local_url(false)));
            $output .= '<div class="purgecaches">' .
                html_writer::link($purgeurl, get_string('purgecaches', 'admin')) . '</div>';
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Returns the mobile app url
     *
     * @return string
     *
     * @throws \coding_exception
     */
    private function get_mobileappurl() {
        global $CFG;
        $output = '';
        if (!empty($CFG->enablemobilewebservice) && $url = tool_mobile_create_app_download_url()) {
            $url = html_writer::link($url,
                                "<i class='icon-screen-smartphone'></i> ".get_string('getmoodleonyourmobile', 'tool_mobile'),
                                     ['class' => 'btn btn-primary']);

            //$output .= html_writer::div($url, 'mobilefooter mb-2');
        }

        return $output;
    }

    /**
     * Wrapper for header elements.
     *
     * Author: Diego Fdo ruiz
     * 
     * @return string HTML to display the main header.
     * 
     */
    public function mydashboard_user_header(){
      global $CFG;
      $img_path = $CFG->wwwroot . '/theme/moove/pix/dintev/';
      $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'row'));
      $html .= html_writer::start_tag('div', array('class' => 'col-12 pt-3 pb-3'));
      $html .= html_writer::start_tag('div', array('class' => 'card'));
      $html .= html_writer::start_tag('div', array('class' => 'card-body'));

      $html .= html_writer::start_tag('div', array('class' => 'd-flex'));
      $html .= html_writer::start_tag('div', array('class' => 'mr-auto'));
      $html .= $this->context_header();
      //$html .= $this->page_heading_button();
      $html .= html_writer::end_tag('div');//Fin de tag div.mr-auto
      $html .= html_writer::end_tag('div');//Fin de tag div.d-flex

      //Bloque para links de dashboard de los usuarios
      $html .= html_writer::start_tag('div', array('class' => 'row'));
      //Biblioteca
      $html .= html_writer::start_tag('div', array('class' => 'col-md-3'));
      $html .= html_writer::start_tag('a', array('href' => 'http://biblioteca.univalle.edu.co/', 'target' => '_blank', 'class'=>'btn btn-header-user-uv'));
      $html .= 'Biblioteca';
      $html .= html_writer::start_tag('img', array('src'=>$img_path.'iconos_bloquesuperior_biblioteca.png', 'class'=>'img-user-header'));
      $html .= html_writer::end_tag('a');
      $html .= html_writer::end_tag('div');//Fin de tag div.col-md-3
      //Correo 
      $html .= html_writer::start_tag('div', array('class' => 'col-md-3'));
      $html .= html_writer::start_tag('a', array('href' => 'https://www.univalle.edu.co/index.php/correo-electronico-institucional', 'target' => '_blank', 'class'=>'btn btn-header-user-uv'));
      $html .= 'Correo';
      $html .= html_writer::end_tag('br');//Fin de tag br
      $html .= 'Institucional';
      $html .= html_writer::start_tag('img', array('src'=>$img_path.'iconos_bloquesuperior_correoe.png', 'class'=>'img-user-header'));
      $html .= html_writer::end_tag('a');
      $html .= html_writer::end_tag('div');//Fin de tag div.col-md-3
      //Reglamento
      $html .= html_writer::start_tag('div', array('class' => 'col-md-3'));
      $html .= html_writer::start_tag('a', array('href' => 'http://secretariageneral.univalle.edu.co/consejo-superior/reglamento-estudiantil/index.html', 'target' => '_blank', 'class'=>'btn btn-header-user-uv'));
      $html .= 'Reglamento';
      $html .= html_writer::start_tag('img', array('src'=>$img_path.'iconos_bloquesuperior_reglamento.png', 'class'=>'img-user-header'));
      $html .= html_writer::end_tag('a');
      $html .= html_writer::end_tag('div');//Fin de tag div.col-md-3
      //registro
      $html .= html_writer::start_tag('div', array('class' => 'col-md-3'));
      $html .= html_writer::start_tag('a', array('href' => 'http://registro.univalle.edu.co/', 'target' => '_blank', 'class'=>'btn btn-header-user-uv'));
      $html .= 'Registro';
      $html .= html_writer::end_tag('br');//Fin de tag br
      $html .= 'Académico';
      $html .= html_writer::start_tag('img', array('src'=>$img_path.'iconos_bloquesuperior_registroacademico.png', 'class'=>'img-user-header'));
      $html .= html_writer::end_tag('a');
      $html .= html_writer::end_tag('div');//Fin de tag div.col-md-3
      //Bienestar
      $html .= html_writer::start_tag('div', array('class' => 'col-md-3'));
      $html .= html_writer::start_tag('a', array('href' => 'http://vicebienestar.univalle.edu.co/', 'target' => '_blank', 'class'=>'btn btn-header-user-uv'));
      $html .= 'Bienestar';
      $html .= html_writer::end_tag('br');//Fin de tag br
      $html .= 'Universitario';
      $html .= html_writer::start_tag('img', array('src'=>$img_path.'iconos_bloquesuperior_bienestaru.png', 'class'=>'img-user-header'));
      $html .= html_writer::end_tag('a');
      $html .= html_writer::end_tag('div');//Fin de tag div.col-md-3

      $html .= html_writer::end_tag('div');//Fin de tag div.row --- Fin bloque links

      $html .= html_writer::end_tag('div');//Fin de tag div.card-body
      $html .= html_writer::end_tag('div');//Fin de tag div.card
      $html .= html_writer::end_tag('div');//Fin de tag div.col-12
      $html .= html_writer::end_tag('header');//Fin de tag header
      return $html;
    }

    /**
     * Buttons for personalize site.
     *
     * Author: Diego Fdo ruiz
     * 
     * @return string HTML to display the main header.
     * 
     */
    public function page_personalize_buttons(){
      $html = html_writer::start_tag('div', array('class' => 'personalize-buttons', 'id' => 'personalize-buttons'));
      $html .= html_writer::end_tag('div');
      $this->page->requires->js_call_amd('theme_moove/btn_in_course', 'init');
      return $html;
    }

    /**
     * Alert delete courses.
     *
     * Author: Diego Fdo ruiz
     * 
     * @return string HTML to display the mydashboard.
     * 
     */
    public function alert_delete_courses(){
      global $USER, $DB, $CFG;
      $html = "";
      $result=$DB->get_records_sql("SELECT
                                      mdl_course.id
                                    FROM
                                      public.mdl_user,
                                      public.mdl_role_assignments,
                                      public.mdl_context,
                                      public.mdl_course
                                    WHERE
                                      mdl_role_assignments.userid = mdl_user.id AND
                                      mdl_role_assignments.contextid = mdl_context.id AND
                                      mdl_context.instanceid = mdl_course.id And mdl_role_assignments.roleid='3' And mdl_user.id='$USER->id'");
      $totalCursos=count($result);
      $url_delete_courses = $CFG->wwwroot."/course/delete_course_old";
      if($totalCursos > 0){
        $html .= html_writer::start_tag('div', array('class' => 'alert alert-warning', 'id' => 'alert_delete_courses'));
        $html .= html_writer::start_tag('h5');
        $html .= "Si usted desea eliminar alguno de sus cursos, por favor dirijase a la sección ";
        $html .= html_writer::start_tag('a', array('style' => 'color:#D51B23;', 'href' => $url_delete_courses));
        $html .= "Eliminar Cursos";
        $html .= html_writer::end_tag('a');
        $html .= html_writer::end_tag('h5');
        $html .= html_writer::end_tag('div');
      }
      return $html;
    }

}

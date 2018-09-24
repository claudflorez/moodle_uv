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
 * ASES
 *
 * @author     Jeison Cardona Gómez.
 * @package    block_ases
 * @copyright  2018 Jeison Cardona Gómez <jeison.cardona@correounivalle.edu.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Standard GPL and phpdocs
require_once __DIR__ . '/../../../config.php';
require_once $CFG->libdir . '/adminlib.php';

require_once('../managers/lib/lib.php');
require_once('../managers/instance_management/instance_lib.php');
require_once ('../managers/menu_options.php');
include_once "../managers/dphpforms/dphpforms_form_updater.php";
include('../lib.php');


global $PAGE;
global $USER;

include "../classes/output/dphpforms_form_editor_comportamientos_page.php";
include "../classes/output/renderer.php";

$title = "Editor de comportamientos";
$pagetitle = $title;
$courseid = required_param('courseid', PARAM_INT);
$form_id = required_param('form_id', PARAM_INT);
$blockid = required_param('instanceid', PARAM_INT);

$record = new stdClass();

require_login($courseid, false);

if (!consult_instance($blockid)) {
    header("Location: instanceconfiguration.php?courseid=$courseid&instanceid=$blockid");
}

$contextcourse = context_course::instance($courseid);
$contextblock = context_block::instance($blockid);

$url = new moodle_url("/blocks/ases/view/dphpforms_form_editor_comportamientos.php", array('courseid' => $courseid, 'instanceid' => $blockid));

$coursenode = $PAGE->navigation->find($courseid, navigation_node::TYPE_COURSE);

$rol = get_role_ases($USER->id);

$record->form_id = $form_id;
$record->comportamientos_disparadores = dphpforms_form_updater_get_disparadores( $form_id );

$PAGE->set_context($contextcourse);
$PAGE->set_context($contextblock);
$PAGE->set_url($url);
$PAGE->set_title($title);

$PAGE->requires->css('/blocks/ases/style/jqueryui.css', true);
$PAGE->requires->css('/blocks/ases/style/styles_pilos.css', true);
$PAGE->requires->css('/blocks/ases/style/bootstrap.min.css', true);
$PAGE->requires->css('/blocks/ases/style/sweetalert.css', true);
$PAGE->requires->css('/blocks/ases/style/sweetalert2.css', true);
$PAGE->requires->css('/blocks/ases/style/sugerenciaspilos.css', true);
$PAGE->requires->css('/blocks/ases/style/forms_pilos.css', true);
$PAGE->requires->css('/blocks/ases/style/c3.css', true);
$PAGE->requires->css('/blocks/ases/js/select2/css/select2.css', true);
$PAGE->requires->css('/blocks/ases/style/side_menu_style.css', true);
$PAGE->requires->css('/blocks/ases/style/creadorFormulario.css', true);

$PAGE->requires->js_call_amd('block_ases/dphpforms_form_editor', 'init');

$output = $PAGE->get_renderer('block_ases');

echo $output->header();
$dphpforms_form_editor_comportamientos_page = new \block_ases\output\dphpforms_form_editor_comportamientos_page($record);
echo $output->render($dphpforms_form_editor_comportamientos_page);
echo $output->footer();

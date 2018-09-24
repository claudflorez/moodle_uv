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
 * Script for management of attendance
 *
 * @package   report_categoryreports
 * @copyright 2018 Iader E. García G.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('attendance_manager.php');

if(isset($_POST['function'])){
    switch($_POST['function']){
        case 'get_courses':
            echo json_encode(get_courses_category($_POST['id_category']));
            break;
        case 'get_attendance':
            echo json_encode(get_attendance($_POST['courseid']));
            break;
    };
}
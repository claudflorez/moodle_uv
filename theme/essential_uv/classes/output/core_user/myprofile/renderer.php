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
<<<<<<< HEAD

namespace theme_essential_uv\output\core_user\myprofile;
use \core_user\output\myprofile\node;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

class renderer extends \core_user\output\myprofile\renderer {

=======
namespace theme_essential_uv\output\core_user\myprofile;
use \core_user\output\myprofile\node;
use moodle_url;
defined('MOODLE_INTERNAL') || die();
class renderer extends \core_user\output\myprofile\renderer {
>>>>>>> 8daa0a0b52492f10a5476355744095703df303ea
    /**
     * Render a node.
     *
     * @param node $node
     *
     * @return string
     */
    public function render_node(node $node) {
<<<<<<< HEAD

        global $CFG;

        $return = '';

        $userid = OPTIONAL_PARAM('id', 0, PARAM_INT);

=======
        global $CFG;
        $return = '';
        $userid = OPTIONAL_PARAM('id', 0, PARAM_INT);
        $allcourses = OPTIONAL_PARAM('showallcourses', 0, PARAM_INT);
>>>>>>> 8daa0a0b52492f10a5476355744095703df303ea
        // Al entrar al nodo 'Perfiles de curso' se consultan los cursos en los cuales está 
        // matriculado el usuario, para su posterior ordenamiento
        if($node->title == "Perfiles de curso"){
            $courses = enrol_get_all_users_courses($userid, true, null);
<<<<<<< HEAD
            $virtual_courses_array = array();
            $classroom_courses_array = array();
            $html_to_return = "";

            // Se separan los cursos en dos arreglos, uno para los cursos presenciales
            // otro para cursos virtuales
            foreach($courses as $course){
                if($course->category > 30000){
                    $classroom_courses_array[$course->id] = $course;
                }else{
                    $virtual_courses_array[$course->id] = $course;
                }
            }            

            krsort($classroom_courses_array);
            krsort($virtual_courses_array);

            $html_to_return = "<br>";
            $html_to_return .= "<b>Cursos presenciales: </b><br>";
            $html_to_return .= "<ul>";

            foreach($classroom_courses_array as $course){

                $url = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $course->id));
                
            	$html_to_return .= "<li>";
            	$html_to_return .= "<a href='".$url."'>";
            	$html_to_return .= $course->shortname." ".$course->fullname;
            	$html_to_return .= "</a>";
            	$html_to_return .= "</li>";
            }

            $html_to_return .= "</ul></br>";
            $html_to_return .= "<b>Otros cursos: </b><br>";

            foreach($virtual_courses_array as $course){

                $url = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $course->id));
                
            	$html_to_return .= "<li>";
            	$html_to_return .= "<a href='".$url."'>";
            	$html_to_return .= $course->shortname." ".$course->fullname;
            	$html_to_return .= "</a>";
            	$html_to_return .= "</li>";
            }

            $html_to_return .= "</ul>";

=======
            $classroom_courses_array = array();
            $teacher_training_courses_array = array();
            $no_regular_courses_array = array();
            $html_to_return = "";
            // Contadores y valores máximos para controlar la cantidad de cursos a visualizar
            $counter_classroom_courses = 0;
            $counter_teacher_training_courses = 0;
            $counter_no_regular_courses = 0;
            $max_classroom_courses = 5;
            $max_teacher_training_courses = 2;
            $max_no_regular_courses = 2;
            // Se separan los cursos en tres arreglos, uno para los cursos presenciales,
            // cursos formación docente y cursos no regulares
            foreach($courses as $course){
                if($course->category > 30000){
                    $classroom_courses_array[$course->id] = $course;
                }else if($course->category == 89){
                    $teacher_training_courses_array[$course->id] = $course;
                }else{
                    $no_regular_courses[$course->id] = $course;
                }
            }            
            // Ordenamiento de los arreglos de cursos
            krsort($classroom_courses_array);
            krsort($teacher_training_courses_array);
            krsort($no_regular_courses_array);
            if(count($classroom_courses_array) > 0){
                $html_to_return = "<br>";
                $html_to_return .= "<b>Cursos presenciales: </b><br>";
                $html_to_return .= "<ul style='list-style-type: disc'>";
                foreach($classroom_courses_array as $course){
                    $url = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $course->id));
                    
                    $html_to_return .= "<li>";
                    $html_to_return .= "<a href='".$url."'>";
                    $html_to_return .= $course->shortname." ".$course->fullname;
                    $html_to_return .= "</a>";
                    $html_to_return .= "</li>";
                    // Validación necesaria para la opción 'Ver más'
                    if(!$allcourses){
                        if($counter_classroom_courses < $max_classroom_courses){
                            $counter_classroom_courses += 1;
                        }else{
                            break;
                        }
                    }
                }
    
                $html_to_return .= "</ul></br>";
            }
            if(count($teacher_training_courses_array) > 0){
                $html_to_return .= "<b>Formación docente: </b><br>";
                $html_to_return .= "<ul style='list-style-type: disc'>";
                foreach($teacher_training_courses_array as $course){
                    $url = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $course->id));
                    
                    $html_to_return .= "<li>";
                    $html_to_return .= "<a href='".$url."'>";
                    $html_to_return .= $course->shortname." ".$course->fullname;
                    $html_to_return .= "</a>";
                    $html_to_return .= "</li>";
                    // Validación necesaria para la opción 'Ver más'
                    if(!$allcourses){
                        if($counter_teacher_training_courses < $max_teacher_training_courses){
                            $counter_teacher_training_courses += 1;
                        }else{
                            break;
                        }
                    }
                }
                $html_to_return .= "</ul><br>";
            }
            
            if(count($no_regular_courses_array) > 0){
                $html_to_return .= "<b>Cursos no regulares: </b><br>";
                $html_to_return .= "<ul style='list-style-type: disc'>";
                foreach($no_regular_courses_array as $course){
                    $url = new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $course->id));
                    
                    $html_to_return .= "<li>";
                    $html_to_return .= "<a href='".$url."'>";
                    $html_to_return .= $course->shortname." ".$course->fullname;
                    $html_to_return .= "</a>";
                    $html_to_return .= "</li>";
                    // Validación necesaria para la opción 'Ver más'
                    if(!$allcourses){
                        if($counter_no_regular_courses < $max_no_regular_courses){
                            $counter_no_regular_courses += 1;
                        }else{
                            break;
                        }
                    }
                }
                $html_to_return .= "</ul><br>";
            }
            if(!$allcourses){
                $url_all_courses = new moodle_url($CFG->wwwroot.'/user/profile.php', array('id' => $userid, 'showallcourses' => 1));
                $html_to_return .= "<dd><a href='".$url_all_courses."'>".get_string('viewmore')."</a></dd>";
            }else{
                $url_less_courses = new moodle_url($CFG->wwwroot.'/user/profile.php', array('id' => $userid, 'showallcourses' => 0));
                $html_to_return .= "<dd><a href='".$url_less_courses."'>".get_string('viewless')."</a></dd>";
            }
>>>>>>> 8daa0a0b52492f10a5476355744095703df303ea
            $new_node = new node($node->parentcat,
                                 $node->name,
                                 $node->title,
                                 $node->after,
                                 $node->url,
                                 $html_to_return,
                                 $node->icon,
                                 $node->classes);
<<<<<<< HEAD

            $node = $new_node;

=======
            $node = $new_node;
>>>>>>> 8daa0a0b52492f10a5476355744095703df303ea
        }
 
        if (is_object($node->url)) {
            $header = \html_writer::link($node->url, $node->title);
        } else {
            $header = $node->title;
        }
        $icon = $node->icon;
        if (!empty($icon)) {
            $header .= $this->render($icon);
        }
        $content = $node->content;
        $classes = $node->classes;
        if (!empty($content)) {
            // There is some content to display below this make this a header.
            $return = \html_writer::tag('dt', $header);
            $return .= \html_writer::tag('dd', $content);
<<<<<<< HEAD

=======
>>>>>>> 8daa0a0b52492f10a5476355744095703df303ea
            $return = \html_writer::tag('dl', $return);
            if ($classes) {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode ' . $classes));
            } else {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode'));
            }
        } else {
            $return = \html_writer::span($header);
            $return = \html_writer::tag('li', $return, array('class' => $classes));
        }
<<<<<<< HEAD

        return $return;
    }

=======
        return $return;
    }
>>>>>>> 8daa0a0b52492f10a5476355744095703df303ea
}
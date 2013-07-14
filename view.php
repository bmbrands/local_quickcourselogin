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
 * Progress Report
 *
 * This module has been created to provide a quick and easy way of loggin into a course
 *
 * @package    local
 * @subpackage quickcourselogin
 * @copyright  2013 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$getpage= true;
$debug = true;
$debuginfo = '';

$username  = optional_param('username', '', PARAM_TEXT);
$password  = optional_param('password', '', PARAM_TEXT);
$courseid  = optional_param('courseid', 0, PARAM_INT);

if (isloggedin()) {
    if ($courseid > 0 ) {
        unset($SESSION->wantsurl);
        $urltogo = $CFG->wwwroot.'/course/view.php?id='.$courseid;
        redirect($urltogo);
    }
}
if ($username != '' && $password != '') {
    if ($user = $DB->get_record('user', array('username'=>$username))) {
        //User Exists, Check pass
        $debuginfo = 'correct username';
        if (validate_internal_user_password($user, $password) ) {
            $debuginfo .= ' correct password';
            complete_user_login($user);
            if ($courseid > 0 ) {
                unset($SESSION->wantsurl);
                $urltogo = $CFG->wwwroot.'/course/view.php?id='.$courseid;
                redirect($urltogo);
            }
        }
    }
}

if ($getpage) {
    // Print Page.
    $context = context_system::instance();
    $PAGE->set_context($context);
    $PAGE->set_url('/local/quickcourselogin/view.php');
    $PAGE->set_title(format_string("Quick Course Login"));
    $PAGE->set_heading(format_string("Demo"));

    $PAGE->blocks->show_only_fake_blocks();
    echo $OUTPUT->header();
    require_once($CFG->dirroot . '/local/quickcourselogin/demoform_form.php');

    $mform = new demoform_form();

    if ($mform->is_cancelled()) {
      // form cancelled, redirect
      redirect(new moodle_url('view.php',array()));
      return;
    } else if (($data = $mform->get_data())) {
      $urltogo = $CFG->wwwroot.'/local/quickcourselogin/view.php?username='.$data->username.'&password='.$data->password.'id='.$data->courseid;
      redirect($urltogo);
    } else {
      // form has not been submitted, just display it
      $mform->display();
    }
    if ($debug) {
        echo $debuginfo;
    }
    echo $OUTPUT->footer();
}




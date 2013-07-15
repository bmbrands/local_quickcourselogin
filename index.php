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
$error = '';

$local_user = new stdClass();
$local_user->username = optional_param('username', '', PARAM_TEXT);
$local_user->password = optional_param('password', '', PARAM_TEXT);
$local_user->courseid = optional_param('courseid', 0, PARAM_INT);
$local_user->firstname = optional_param('firstname', '', PARAM_TEXT);
$local_user->lastname = optional_param('lastname', '', PARAM_TEXT);
$local_user->email = optional_param('email', '', PARAM_TEXT);
$local_user->lastlogin = $USER->currentlogin = time();


if (isloggedin()) {
    if ($local_user->courseid > 0 ) {
        unset($SESSION->wantsurl);
        $urltogo = $CFG->wwwroot.'/course/view.php?id='.$local_user->courseid;
        redirect($urltogo);
    } else {
        $urltogo = $CFG->wwwroot;
        redirect($urltogo);
    }
}

if ($local_user->username != '' && $local_user->username != '') {
    if ($user = $DB->get_record('user', array('username'=>$local_user->username))) {
        //User Exists, Check pass
        $debuginfo = 'correct username.' . $local_user->password;
        if ($user = authenticate_user_login($local_user->username, $local_user->password) ) {
            $debuginfo .= ' correct password';
            complete_user_login($user);
            go_to_course($user);
        } else {
            $error = 'Invalid Username or Password';
        }
    } else {
        $local_user = local_create_user($local_user);
        authenticate_user_login($local_user->username,$local_user->password);
        $USER->currentlogin = $USER->lastlogin = time();
        complete_user_login($local_user);
        go_to_course($local_user);
    }
}

if ($getpage) {
    // Print Page.
    $context = context_system::instance();
    $PAGE->set_context($context);
    $PAGE->set_url('/local/quickcourselogin/view.php');
    $PAGE->set_title(format_string("Quick Course Login"));
    $PAGE->set_heading(format_string("Quick Login"));
    $PAGE->blocks->show_only_fake_blocks();

    if ($error) {
        echo $OUTPUT->header("Error");
        echo $error;
        echo '<br><a href="'.$CFG->wwwroot.'/local/quickcourselogin/index.php">Try again</a>';
        echo $OUTPUT->footer();
        exit(0);
    }

    echo $OUTPUT->header("Sign in");

    echo $OUTPUT->heading('Login');
    require_once($CFG->dirroot . '/local/quickcourselogin/local_signin_form.php');
    $signin = new local_signin_form();
    $signin->display();

    require_once($CFG->dirroot . '/local/quickcourselogin/local_signup_form.php');
    echo $OUTPUT->heading('Sign up');
    $signup = new local_signup_form();
    $signup->display();

    if ($debug) {
        echo $debuginfo;
    }
    echo $OUTPUT->footer();
}




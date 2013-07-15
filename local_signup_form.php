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
 * Quick Course Login
 *
 * This module has been created to provide a quick and easy way of loggin into a course
 *
 * @package    local
 * @subpackage quickcourselogin
 * @copyright  2013 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class local_signup_form extends moodleform {

  // Define the form
  function definition() {
    global $USER, $CFG, $COURSE;

    $mform = $this->_form;
    $userid = $USER->id;
    $strrequired = get_string('required');

    $mform->addElement('text', 'username', 'username');
    $mform->setType('username', PARAM_TEXT);

    $mform->addElement('password', 'password', 'password');
    $mform->setType('password', PARAM_TEXT);

    $mform->addElement('text', 'firstname', 'firstname');
    $mform->setType('firstname', PARAM_TEXT);

    $mform->addElement('text', 'lastname', 'lastname');
    $mform->setType('lastname', PARAM_TEXT);

    $mform->addElement('text', 'email', 'email');
    $mform->setType('email', PARAM_TEXT);

    $mform->addElement('text', 'courseid', 'courseid');
    $mform->setType('courseid', PARAM_INT);

    $mform->addElement('submit', 'submit', get_string('submit'));

  }
}

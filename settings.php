<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    global $CFG, $USER, $DB;

    $moderator = get_admin();
    $site = get_site();

    $settings = new admin_settingpage('local_quickcourselogin', get_string('pluginname', 'local_quickcourselogin'));
    $ADMIN->add('localplugins', $settings);

    $name = 'local_quickcourselogin/unenrol_duration';
    $title = get_string('unenrol_duration','local_quickcourselogin');
    $description = get_string('unenrol_duration_desc','local_quickcourselogin');
    $setting = new admin_setting_configtext($name, $title, $description, 365);
    $settings ->add($setting);

    $courses_chosen = array();
    $profile_options = array();
    if ($courses = $DB->get_records('course', array('visible'=>1), 'sortorder ASC')) {
        foreach ($courses as $course) {
            if ($course->id == 1) {continue;}
             $courses_chosen[$course->id] = $course->shortname;
        }
    }

    $name = 'local_quickcourselogin/courses_chosen';
    $title = get_string('courses_chosen','local_quickcourselogin');
    $description = get_string('courses_chosen_desc', 'local_quickcourselogin');
    $setting = new admin_setting_configselect($name, $title, $description, 4, $courses_chosen);
    $settings ->add($setting);


}


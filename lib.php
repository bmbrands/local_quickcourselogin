<?php


function local_create_user($user) {
    global $CFG, $DB;


    if (empty($user->email)) {
        print_error("Please provide a valid email address");
        return;

    }
    if (empty($user->username)) {
        print_error("Please provide a username");
        return;

    }
    if (empty($user->firstname)) {
        print_error("Please provide your firstname");
        return;

    }
    if (empty($user->lastname)) {
        print_error("Please provide your lastname");
        return;
    }
    if (empty($user->password)) {
        print_error("You have to provide a password");
        return;
    }
    if (!validate_email($user->email)) {
        print_error("Please provide a valid email address");
        return;

    }
    if ($DB->record_exists('user', array('email'=>$user->email))) {
        print_error("Your email address is already registred,
        	<a href='".$CFG->wwwroot."/login/forgot_password.php'> Forgot your password?</a>");
        return;
    }

    $user->timemodified = time();
    $user->timecreated  = time();
    $user->firstaccess  = time();
    $user->lastaccess  = time();
    $user->lastlogin  = time();
    $user->currentlogin = time();
    $user->mnethostid   = $CFG->mnet_localhost_id;
    $user->auth = 'manual';
    $user->policyagreed = 1;
    $user->confirmed = 1;
    $user->password = hash_internal_user_password($user->password, true);
    $user->id = $DB->insert_record('user', $user);


    events_trigger('user_created', $user);
    return $user;


}

function go_to_course($user) {

    global $CFG, $DB;
    if (!empty($user->courseid) && $user->courseid > 0 ) {
        if (!$DB->get_record('course',array('id'=>$user->courseid))) {
            print_error("The courseid you provided does not exist");
        }
        $enrolcourse = $user->courseid;
    } else {
        $enrolcourse = get_config('local_quickcourselogin','courses_chosen');
    }
    if (enrol_is_enabled('manual')) {
        $manual = enrol_get_plugin('manual');
    } else {
        print_error("You can not enrol into this course, manual enrolments disabled on site level");
    }
    $manualinstances    = array();
    if ($instances = enrol_get_instances($enrolcourse, false)) {
        foreach ($instances as $instance) {
            if ($instance->enrol === 'manual') {
                $manualinstances[$enrolcourse] = $instance;
                break;
            }
        }
    }
    if (count($manualinstances) == 0) {
        print_error("You can not enrol into this course, manual enrolments disabled on course level");
    }
    //Get the default role id
    $rid = $manualinstances[$enrolcourse]->roleid;
    $today = time();
    $enrolduration = get_config('local_quickcourselogin','unenrol_duration');
    $timeend = time() + (60 * 60 * 24 * $enrolduration); //4 years

    $manual->enrol_user($manualinstances[$enrolcourse], $user->id, $rid, $today, $timeend);

    $urltogo = $CFG->wwwroot.'/course/view.php?id='.$enrolcourse;
    redirect($urltogo);
}
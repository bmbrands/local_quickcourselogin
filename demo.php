<?php
require_once($CFG->dirroot . '/local/quickcourselogin/demoform_form.php');

/** */
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
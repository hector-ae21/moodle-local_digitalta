<?php

/**
 * myexperience delete
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../lib.php');
require_once(__DIR__ . './../../classes/experiences.php');

use local_dta\Experience;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$strings = get_strings(['form_experience_delete_header', 'form_experience_delete_confirm', 'form_experience_delete_yes', 'form_experience_delete_no'], "local_dta");

// Get the experience id
$id = required_param('id', PARAM_INT);
$delete = optional_param('delete', '', PARAM_ALPHANUM);

$experience = Experience::getExperience($id);

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/myexperience/delete.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->form_experience_delete_header);

echo $OUTPUT->header();

// Check permissions
if (local_dta_check_permissions($experience, $USER) == false) {
    print_error('errorpermissions', 'local_dta');
}

// Check if the delete hash is correct
if ($delete === md5($experience->date)) {
    if (!Experience::deleteExperience($experience->id)) {
        print_error('errordeleteexperience', 'local_dta');
    }
    redirect(new moodle_url('/local/dta/pages/profile.php'), get_string('form_experience_delete_yes', 'local_dta'), null, \core\output\notification::NOTIFY_SUCCESS);
    exit;
}

$continueurl = new moodle_url('/local/dta/pages/myexperience/delete.php', array('id' => $experience->id, 'delete' => md5($experience->date)));
$backurl = new moodle_url('/local/dta/pages/myexperience/dashboard.php');
$continuebutton = new single_button(
    $continueurl,
    get_string('delete'),
    'post',
    false,
    ['data-action' => 'delete']
);

echo $OUTPUT->confirm("{$strings->form_experience_delete_confirm} <br><br> {$experience->title}", $continuebutton, $backurl);

echo $OUTPUT->footer();

<?php

/**
 * ourcases delete
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../lib.php');
require_once(__DIR__ . './../../classes/ourcases.php');

use local_dta\OurCases;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$strings = get_strings(['form_case_delete_header', 'form_case_delete_confirm', 'form_case_delete_yes', 'form_case_delete_no'], "local_dta");

// Get the ourcases id
$id = required_param('id', PARAM_INT);
$delete = optional_param('delete', '', PARAM_ALPHANUM);

$PAGE->set_url(new moodle_url('/local/dta/pages/cases/delete.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->form_case_delete_header);

if (!$ourcase = OurCases::get_case($id)) {
    print_error('invalidcases', 'local_dta');
}

// Check permissions
if (local_dta_check_permissions_case($ourcase, $USER) == false) {
    print_error('errorpermissions', 'local_dta');
}

// Check if the delete hash is correct
if ($delete === md5($ourcase->timecreated)) {
    if (!OurCases::delete_case($id)) {
        print_error('errordeletecase', 'local_dta');
    }
    redirect(new moodle_url('/local/dta/pages/cases/repository.php'), get_string('form_case_delete_yes', 'local_dta'));
    exit;
}

$continueurl = new moodle_url('/local/dta/pages/cases/delete.php', array('id' => $ourcase->id, 'delete' => md5($ourcase->timecreated)));
$backurl = new moodle_url('/local/dta/pages/cases/view.php', ['id' => $ourcase->id]);
$continuebutton = new single_button(
    $continueurl,
    get_string('delete'),
    'post',
    false,
    ['data-action' => 'delete']
);

echo $OUTPUT->header();
echo $OUTPUT->confirm("{$strings->form_case_delete_confirm}", $continuebutton, $backurl);
echo $OUTPUT->footer();

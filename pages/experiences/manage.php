<?php

/**
 * Reflection page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . './../../../../config.php');
require_once(__DIR__ . './../../classes/reflection.php');
require_once(__DIR__ . './../../classes/tiny_editor_handler.php');
require_once($CFG->dirroot . '/local/dta/classes/experience.php');
// require_once($CFG->dirroot . '/local/dta/classes/form/filepicker_form.php');


use local_dta\tiny_editor_handler;
use local_dta\Reflection;
use local_dta\Experience;

require_login();

global $CFG, $PAGE, $OUTPUT , $USER;

$experience_title = optional_param('experiencetitle', "", PARAM_RAW);
$experience_id = optional_param('id', 0, PARAM_INT);

// Seting the page url and context
$PAGE->set_url(new moodle_url('/local/dta/pages/experiences/manage.php'));
$PAGE->set_context(context_system::instance());
$PAGE->requires->js_call_amd('local_dta/myexperience/manage/form', 'init');
$PAGE->requires->js_call_amd('local_dta/myexperience/manage/filepicker', 'init');
echo $OUTPUT->header();

// Set tiny configs in DOM
(new tiny_editor_handler)->get_config_editor(['maxfiles' => 1]);


require_once("$CFG->dirroot/lib/form/filemanager.php");


// Opciones para el filepicker
$options = array(
    'maxfiles' => 1,
    'accepted_types' => array('.jpg', '.png', '.jpeg'),
    'maxbytes' => 5000000,
    'areamaxbytes' => 10485760,
    'return_types' => FILE_INTERNAL | FILE_EXTERNAL
);

$attributes = array('id' => 'fileManager', 'class' => 'fileManager', 'name' => 'fileManager');

$filepicker = new MoodleQuickForm_filemanager('filemanager', get_string('file'), $attributes, null, $options);

$filepickerHtml = $filepicker->toHtml();

if ($experience_id && $experience_id != 0) {
    $experience = Experience::get_experience($experience_id);
    if(!$experience) {
        echo $OUTPUT->notification(get_string('experience_not_found', 'local_dta'), 'error');
        echo $OUTPUT->footer();
        die();
    }
    $template_context = [
        'filepicker' => $filepickerHtml,
    ];
    $template_context = array_merge($template_context, get_object_vars($experience));
}
else {
    $template_context = [
        "title" => $experience_title,
        "filepicker" => $filepickerHtml
    ];    
}

echo $OUTPUT->render_from_template('local_dta/experiences/manage/form', $template_context);

echo $OUTPUT->footer();

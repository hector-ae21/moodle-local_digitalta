<?php

/**
 * myexperience page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/form/experiences_form.php');
require_once(__DIR__ . './../../classes/experiences.php');

use local_dta\Experience;

require_login();

global $CFG, $PAGE, $OUTPUT, $USER;

$strings = get_strings(['form_experience_header'], "local_dta");

// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/myexperience/add.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->form_experience_header);
$PAGE->set_heading($strings->form_experience_header);

// Crea un nuevo formulario de experiencias
$form = new local_experiences_form($_POST['experience_title']);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/local/dta/pages/community.php'));
} elseif ($form->is_submitted()) {
    if ($data = $form->get_data()) {

        if (!$experience = Experience::addExperience($data->experience_title, $data->experience_description, date("Y-m-d H:i:s"), $data->experience_lang, $USER->id, $data->experience_is_public)) {
            print_error('erroraddexperience', 'local_dta');
        } else {
            redirect(new moodle_url('/local/dta/pages/community/dashboard.php'));
        }
    }
}

// Muestra el formulario
echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();

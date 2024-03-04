<?php

/**
 * Experiences form
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class local_experiences_form extends moodleform {
    // Add elements to form.
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!

        $strings = get_strings( ['form_experience_description' , 'form_experience_lang' , 'form_experience_is_visible' , 'form_experience_title' ] , "local_dta");

        /* TITLE */ 
        $mform->addElement('text', 'experience_title',  $strings->form_experience_title );
        $mform->setType('experience_title', PARAM_TEXT);

        /* DESCRIPTION */ 
        $mform->addElement('textarea', 'experience_description', $strings->form_experience_description  );
        $mform->setType('experience_description', PARAM_TEXT);

        /* LANG */
        $mform->addElement('select', 'experience_lang', $strings->form_experience_lang , array('es' => 'Español', 'en' => 'English'));
        $mform->setType('experience_lang', PARAM_TEXT);

        /* IS_VISIBLE */
        $mform->addElement('select', 'experience_is_public', $strings->form_experience_is_visible  , array('1' => 'Yes', '0' => 'No'));
        $mform->setType('experience_is_public', PARAM_INT);

        /* SUBMIT */
        $this->add_action_buttons();

    }

    function validation($data , $files) {
        $errors = [];

        // Validación del título: Requerido y longitud máxima de 100 caracteres
        if (empty(trim($data['experience_title']))) {
            $errors['experience_title'] = get_string('required');
        } elseif (strlen($data['experience_title']) > 100) {
            $errors['experience_title'] = get_string('maxlength100');
        }

        // Validación de la descripción: Requerida
        if (empty(trim($data['experience_description']))) {
            $errors['experience_description'] = get_string('required');
        }

        return $errors;
    }
}
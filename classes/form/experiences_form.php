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
require_once("$CFG->dirroot/local/dta/lib.php");

class local_experiences_form extends moodleform
{
    // Add elements to form.
    public function definition()
    {
        $mform = $this->_form; // Don't forget the underscore!

        $strings = get_strings(['form_experience_description', 'form_experience_lang', 'form_experience_visibility', 'form_experience_title', 'form_experience_picture', 'form_experience_visibility_public', 'form_experience_visibility_private'], "local_dta");

        /* TITLE */
        $mform->addElement('text', 'experience_title',  $strings->form_experience_title);
        $mform->setType('experience_title', PARAM_TEXT);
        $mform->addRule('experience_title', null, 'required', null, 'client');

        /* DESCRIPTION */
        $mform->addElement('textarea', 'experience_description', $strings->form_experience_description);
        $mform->setType('experience_description', PARAM_TEXT);
        $mform->addRule('experience_description', null, 'required', null, 'client');

        /* LANG */
        $stringmanager = get_string_manager();
        $mform->addElement('select', 'experience_lang', $strings->form_experience_lang, $stringmanager->get_list_of_translations());
        $mform->setType('experience_lang', PARAM_TEXT);

        /* FILE */
        $mform->addElement(
            'filemanager',
            'picture',
            $strings->form_experience_picture,
            null,
            [
                'subdirs' => 0,
                'maxbytes' => 2097152,
                'maxfiles' => 1,
                'accepted_types' => ['.png', '.jpg', '.jpeg'],
            ]
        );

        /* IS_VISIBLE */
        $mform->addElement('select', 'experience_is_public', $strings->form_experience_visibility, array('1' => $strings->form_experience_visibility_public, '0' => $strings->form_experience_visibility_private));
        $mform->setType('experience_is_public', PARAM_INT);

        /* SUBMIT */
        $this->add_action_buttons(false);
    }

    function validation($data, $files)
    {
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

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
require_once("$CFG->dirroot/repository/lib.php");
require_once("$CFG->dirroot/local/dta/classes/tags.php");


use \local_dta\Tags;


class local_experiences_form extends moodleform
{

    public function __construct()
    {
        parent::__construct();
    }

    // Add elements to form.
    public function definition()
    {
        $mform = $this->_form;

        $strings = get_strings(['form_experience_description', 'form_experience_lang', 'form_experience_visibility', 'form_experience_title', 'form_experience_picture', 'form_experience_visibility_public', 'form_experience_visibility_private', 'form_experience_tags', 'form_experience_tags_placeholder'], "local_dta");

        $mform->addElement('html', '<div class="d-flex flex-column w-100">');

        /* TITLE */
        $mform->addElement('text', 'title',  $strings->form_experience_title);
        $mform->setType('title', PARAM_TEXT);

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
                'return_types' => FILE_INTERNAL | FILE_EXTERNAL
            ]
        );

        /* DESCRIPTION */
        $mform->addElement('editor', 'description', $strings->form_experience_description);
        $mform->setType('description', PARAM_RAW);

        /* LANG */
        $stringmanager = get_string_manager();
        $mform->addElement('select', 'lang', $strings->form_experience_lang, $stringmanager->get_list_of_translations());
        $mform->setType('lang', PARAM_TEXT);

        /* IS_VISIBLE */
        $mform->addElement('select', 'visible', $strings->form_experience_visibility, array('1' => $strings->form_experience_visibility_public, '0' => $strings->form_experience_visibility_private));
        $mform->setType('visible', PARAM_INT);

        /* HIDDEN */
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        /* TAGS */
        $tags = Tags::getAllTags();
        if(!$tags) {
            $tags = [];
        }

        $tagsName = array_reduce($tags, function($carry, $tag) {
            $carry[$tag->id] = $tag->name;
            return $carry;
        }, []);
        
        $options = array(
            'multiple' => true,
            'noselectionstring' => $strings->form_experience_tags_placeholder,
        );
        $mform->addElement('autocomplete', 'tags',$strings->form_experience_tags, $tagsName, $options);

        /* SUBMIT */
        $this->add_action_buttons(false);

        $mform->addElement('html', '</div>');
    }

    function validation($data, $files)
    {
        $errors = [];

        // Validación del título: Requerido y longitud máxima de 100 caracteres
        if (empty(trim($data['title']))) {
            $errors['title'] = get_string('required');
        } elseif (strlen($data['title']) > 100) {
            $errors['title'] = get_string('maxlength100');
        }

        // Validación de la descripción: Requerida
        if (empty($data['description'])) {
            $errors['description'] = get_string('required');
        }

        return $errors;
    }
}

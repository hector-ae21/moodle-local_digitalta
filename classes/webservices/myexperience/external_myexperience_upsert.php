<?php

/**
 * external_myexperience_save
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/experiences.php');

use local_dta\Experiences;

class external_myexperience_upsert extends external_api
{

    public static function myexperience_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'experience ID'),
                'title' => new external_value(PARAM_RAW, 'Title'),
                'lang' => new external_value(PARAM_RAW, 'Lang'),
                'visible' => new external_value(PARAM_BOOL, 'Visible', VALUE_DEFAULT, 1),
                'status' => new external_value(PARAM_INT, 'Status' , VALUE_DEFAULT, 0),
                'themes' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'ID del elemento')  , 'Themes' , VALUE_DEFAULT, []
                ),
                'tags' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'ID del elemento')  , 'Tags' , VALUE_DEFAULT, []
                )
            )
        );
    }

    public static function myexperience_upsert($id, $title, $lang, $visible = 1, $status = 0 , $themes = [], $tags = [])
    {
        $experience          = new stdClass();
        $experience->id      = $id ?? null;
        $experience->title   = $title;
        $experience->lang    = $lang;
        $experience->visible = $visible;
        $experience->status  = $status;
        $experience->themes  = $themes;
        $experience->tags    = $tags;

        if(!$experience = Experiences::upsert_experience($experience)){
            return [
                'result' => false,
                'error' => 'Error saving experience'
            ];
        }

        return [
            'result' => true,
            'experienceid' => $experience->id
        ];
    }

    public static function myexperience_upsert_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'experienceid' => new external_value(PARAM_INT, 'Section ID' , VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
            ]
        );
    }
}

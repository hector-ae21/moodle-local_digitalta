<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class external_myexperience_save_comment extends external_api
{

    public static function myexperience_save_comment_parameters()
    {
        return new external_function_parameters(
            array(
                'experienceid' => new external_value(PARAM_INT, 'ID of the experience', VALUE_REQUIRED),
                'comment' => new external_value(PARAM_RAW, 'Comment', VALUE_REQUIRED)
            )
        );
    }

    public static function myexperience_save_comment_dislike($experienceid, $comment)
    {
        global $USER, $DB;

        if (!$DB->get_record('digital_experiences', array('id' => $experienceid))) {
            return array('result' => false, 'error' => 'Experience not found');
        }

        $record = new \stdClass();
        $record->experienceid = $experienceid;
        $record->userid = $USER->id;
        $record->comment = $comment;

        $DB->insert_record('digital_experience_comments', $record);

        return ['result' => true, 'comment' => $comment, 'user' => ['id' => $USER->id, 'fullname' => $USER->fullname]];
    }

    public static function myexperience_save_comment_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result of the operation'),
                'comment' => new external_value(PARAM_RAW, 'Comment'),
                'user' => new external_single_structure(
                    [
                        'id' => new external_value(PARAM_INT, 'User ID'),
                        'fullname' => new external_value(PARAM_RAW, 'User Fullname')
                    ]
                )
            ]
        );
    }
}

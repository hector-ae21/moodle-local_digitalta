<?php

/**
 * external_ourcases_section_text_update
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class external_myexperience_get_comments extends external_api
{

    public static function myexperience_get_comments_parameters()
    {
        return new external_function_parameters(
            array(
                'experienceid' => new external_value(PARAM_INT, 'Experience ID', VALUE_REQUIRED)
            )
        );
    }

    public static function myexperience_get_comments($experienceid)
    {
        global $DB;

        if (!$DB->get_record('digital_experiences', array('id' => $experienceid))) {
            return array('result' => false, 'error' => 'Our case not found');
        }

        $comments = $DB->get_records('digital_experience_comments', array('experienceid' => $experienceid));
        foreach ($comments as $comment) {
            $user = $DB->get_record('user', array('id' => $comment->userid));
            $comment->user = new \stdClass();
            $comment->user->id = $user->id;
            $comment->user->username = $user->username;
            $comment->user->firstname = $user->firstname;
            $comment->user->lastname = $user->lastname;
            $comment->user->fullname = $user->firstname . ' ' . $user->lastname;
            $comment->user->email = $user->email;
        }

        return ['result' => true, 'comments' => $comments];
    }

    public static function myexperience_get_comments_returns()
    {
        return new external_single_structure(
            array(
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'comments' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'ID'),
                            'experienceid' => new external_value(PARAM_INT, 'Experience ID'),
                            'user' => new external_single_structure(
                                array(
                                    'id' => new external_value(PARAM_INT, 'ID'),
                                    'username' => new external_value(PARAM_RAW, 'Username'),
                                    'firstname' => new external_value(PARAM_RAW, 'First Name'),
                                    'lastname' => new external_value(PARAM_RAW, 'Last Name'),
                                    'fullname' => new external_value(PARAM_RAW, 'Full Name'),
                                    'email' => new external_value(PARAM_RAW, 'Email'),
                                )
                            ),
                            'comment' => new external_value(PARAM_RAW, 'Comment'),
                        )
                    )
                )
            )
        );
    }
}

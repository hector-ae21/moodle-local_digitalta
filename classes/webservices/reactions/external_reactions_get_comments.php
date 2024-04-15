<?php

/**
 * external_ourcases_section_text_update
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class external_reactions_get_comments extends external_api
{

    public static function reactions_get_comments_parameters()
    {
        return new external_function_parameters(
            array(
                'instancetype' => new external_value(PARAM_INT, 'Type of instance 1 for experiences, 0 for cases', VALUE_REQUIRED),
                'instanceid' => new external_value(PARAM_INT, 'ID of the instance', VALUE_REQUIRED),
            )
        );
    }

    public static function reactions_get_comments($instancetype, $instanceid)
    {
        global $DB;

        if (!self::validate_instance_id($instancetype, $instanceid)) {
            return array('result' => false, 'error' => 'Invalid instance id');
        }

        $table = self::get_table($instancetype);
        $column = self::get_column($instancetype);

        $comments = $DB->get_records($table, array($column => $instanceid));
        foreach ($comments as $comment) {
            $user = $DB->get_record('user', array('id' => $comment->userid));
            
            $comment->instanceid = $instanceid;
            
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

    protected static function validate_instance_id($type, $instanceid)
    {
        global $DB;
        switch ($type) {
            case 0:
                return $DB->get_record('digital_ourcases', array('id' => $instanceid));
            case 1:
                return $DB->get_record('digital_experiences', array('id' => $instanceid));
            default:
                return false;
        }
    }

    protected static function get_table($type)
    {
        switch ($type) {
            case 0:
                return 'digital_case_comments';
            case 1:
                return 'digital_experience_comments';
            default:
                return false;
        }
    }

    protected static function get_column($type)
    {
        switch ($type) {
            case 0:
                return 'caseid';
            case 1:
                return 'experienceid';
            default:
                return false;
        }
    }

    public static function reactions_get_comments_returns()
    {
        return new external_single_structure(
            array(
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'comments' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'ID'),
                            'instanceid' => new external_value(PARAM_INT, 'Instance ID'),
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

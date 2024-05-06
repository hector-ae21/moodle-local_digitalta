<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class external_reactions_toggle_like_dislike extends external_api
{

    public static function reactions_toggle_like_dislike_parameters()
    {
        return new external_function_parameters(
            array(
                'instancetype' => new external_value(PARAM_INT, 'Type of instance 1 for experiences, 0 for cases', VALUE_REQUIRED),
                'instanceid' => new external_value(PARAM_INT, 'ID of the instance', VALUE_REQUIRED),
                'action' => new external_value(PARAM_INT, '1 for like, 0 for dislike', VALUE_DEFAULT, null)
            )
        );
    }

    public static function reactions_toggle_like_dislike($instancetype, $instanceid, $action = null )
    {
        global $USER, $DB;

        if (!$USER->id) {
            return array('result' => false, 'error' => 'User not logged in');
        }

        if (!self::validate_instance_id($instancetype, $instanceid)) {
            return array('result' => false, 'error' => 'Invalid instance id');
        }
        
        $table = self::get_table($instancetype);
        $column = self::get_column($instancetype);

        $reaction = new \stdClass();
        $reaction->userid = $USER->id;
        $reaction->$column = $instanceid;
        $reaction->reactiontype = $action;


        if ($actual_reaction = $DB->get_record($table, array($column => $instanceid, 'userid' => $USER->id))) {
            $reaction->id = $actual_reaction->id;
            $DB->update_record($table, $reaction);
        } else {
            $DB->insert_record($table, $reaction);
        }


        $likes = $DB->count_records($table, array($column => $instanceid, 'reactiontype' => 1));
        $dislikes = $DB->count_records($table, array($column => $instanceid, 'reactiontype' => 0));

        return ['result' => true, 'likes' => $likes, 'dislikes' => $dislikes];
    }

    protected static function validate_instance_id($type, $instanceid)
    {
        global $DB;

        if ($type != 0 && $type != 1) {
            return false;
        }

        switch ($type) {
            case 0:
                return $DB->get_record('digital_cases', array('id' => $instanceid));
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
                return 'digital_cases_likes';
            case 1:
                return 'digital_experiences_likes';
            default:
                return null;
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
                return null;
        }
    }

    protected static function get_table_instance($type)
    {
        switch ($type) {
            case 0:
                return 'digital_cases';
            case 1:
                return 'digital_experiences';
            default:
                return null;
        }
    }

    public static function reactions_toggle_like_dislike_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result of the operation'),
                'likes' => new external_value(PARAM_INT, 'Number of likes'),
                'dislikes' => new external_value(PARAM_INT, 'Number of dislikes'),
            ]
        );
    }
}

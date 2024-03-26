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
                'id' => new external_value(PARAM_INT, 'ID of the instance (CASE OR EXPERIENCE) id', VALUE_REQUIRED),
                'action' => new external_value(PARAM_BOOL, '1 for like, 0 for dislike', VALUE_OPTIONAL),
                'type' => new external_value(PARAM_INT, 'Type of reaction 1 for experiences, 0 for cases', VALUE_REQUIRED)
            )
        );
    }

    public static function reactions_toggle_like_dislike($id = 1, $action = null , $type = null)
    {
        global $USER, $DB;

        $reaction = new \stdClass();

        if($type == 0){
            if (!$DB->get_record('digital_ourcases', array('id' => $id))) {
                return array('result' => false, 'error' => 'Case not found');
            }

            $reaction->caseid = $id;
            $reaction->userid = $USER->id;
            $reaction->reactiontype = $action;

            $table = 'digital_case_likes';
            $column = 'caseid';   
        }else{
            if (!$DB->get_record('digital_experiences', array('id' => $id))) {
                return array('result' => false, 'error' => 'Experience not found');
            }

            $reaction->experienceid = $id;
            $reaction->userid = $USER->id;
            $reaction->reactiontype = $action;

            $table = 'digital_experience_likes';
            $column = 'experienceid';   
        }


        if ($actual_reaction = $DB->get_record($table, array($column => $id, 'userid' => $USER->id))) {
            $reaction->id = $actual_reaction->id;
            $DB->update_record($table, $reaction);
        } else {
            $DB->insert_record($table, $reaction);
        }


        $likes = $DB->count_records($table, array($column => $id, 'reactiontype' => 1));
        $dislikes = $DB->count_records($table, array($column => $id, 'reactiontype' => 0));

        return ['result' => true, 'likes' => $likes, 'dislikes' => $dislikes];
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

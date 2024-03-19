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

    public static function reactions_toggle_like_dislike($id, $action = null , $type)
    {
        global $USER, $DB;

        if($type == 0){
            if (!$case = $DB->get_record('digital_ourcases', array('id' => $id))) {
                return array('result' => false, 'error' => 'Case not found');
            }

            $reaction = new \stdClass();
            $reaction->caseid = $id;
            $reaction->userid = $USER->id;
            $reaction->reactiontype = $action;
            
        }



        if (!$experience = $DB->get_record('digital_experiences', array('id' => $experienceid))) {
            return array('result' => false, 'error' => 'Experience not found');
        }

        $like = new \stdClass();
        $like->experienceid = $experienceid;
        $like->userid = $USER->id;
        $like->reactiontype = $action;

        if ($actual_reaction = $DB->get_record('digital_experience_likes', array('experienceid' => $experienceid, 'userid' => $USER->id))) {
            $like->id = $actual_reaction->id;
            $DB->update_record('digital_experience_likes', $like);
        } else {
            $DB->insert_record('digital_experience_likes', $like);
        }

        $likes = $DB->count_records('digital_experience_likes', array('experienceid' => $experienceid, 'reactiontype' => 1));
        $dislikes = $DB->count_records('digital_experience_likes', array('experienceid' => $experienceid, 'reactiontype' => 0));

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

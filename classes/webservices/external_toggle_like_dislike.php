<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

class local_dta_external_toggle_like_dislike extends external_api
{

    public static function toggle_like_dislike_parameters()
    {
        return new external_function_parameters(
            array(
                'experienceid' => new external_value(PARAM_INT, 'ID of the experience'),
                'action' => new external_value(PARAM_BOOL, '1 for like, 0 for dislike', VALUE_OPTIONAL)
            )
        );
    }

    public static function toggle_like_dislike($experienceid, $action = null)
    {
        global $USER, $DB;

        if (!$experience = $DB->get_record('digital_experiences', array('id' => $experienceid))) {
            return array('result' => false, 'error' => 'Experience not found');
        }

        $like = new \stdClass();
        $like->experienceid = $experienceid;
        $like->userid = $USER->id;
        $like->reactiontype = $action;

        if ($DB->get_record('digital_experience_likes', array('experienceid' => $experienceid, 'userid' => $USER->id))) {
            $DB->update_record('digital_experience_likes', $like);
        } else {
            $DB->insert_record('digital_experience_likes', $like);
        }

        $likes = $DB->count_records('digital_experience_likes', array('experienceid' => $experienceid, 'reactiontype' => 1));
        $dislikes = $DB->count_records('digital_experience_likes', array('experienceid' => $experienceid, 'reactiontype' => 0));
        
        return array('result' => true, 'likes' => $likes, 'dislikes' => $dislikes);
    }

    public static function toggle_like_dislike_returns()
    {
        return new external_single_structure(
            array(
                'result' => new external_value(PARAM_BOOL, 'Result of the operation'),
                'likes' => new external_value(PARAM_INT, 'Number of likes'),
                'dislikes' => new external_value(PARAM_INT, 'Number of dislikes'),
            )
        );
    }

    // Aquí puedes añadir más funciones para comentarios, etc.
}

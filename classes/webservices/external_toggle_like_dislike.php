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

class local_dta_external_toggle_like_dislike extends external_api {

    public static function toggle_like_dislike_parameters() {
        return new external_function_parameters(
            array(
                'experienceid' => new external_value(PARAM_INT, 'ID of the experience'),
                'action' => new external_value(PARAM_ALPHA, 'Action to perform: like or dislike'),
            )
        );
    }

    public static function toggle_like_dislike($experienceid, $action) {
        global $USER, $DB;

        // Aquí iría tu lógica para manejar el like/dislike
        // Por ejemplo, comprobar si ya existe un like/dislike para esta experiencia por este usuario,
        // y actualizar la base de datos según corresponda.

        return array('result' => true, 'likes' => $likes, 'dislikes' => $dislikes);
    }

    public static function toggle_like_dislike_returns() {
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

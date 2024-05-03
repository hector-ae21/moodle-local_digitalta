<?php

/**
 * external_context_delete
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../context.php');

use local_dta\Context;

class external_context_delete extends external_api
{

    public static function context_delete_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'id of the context to delete' , VALUE_REQUIRED),
            )
        );
    }

    public static function context_delete($id)
    {
        Context::remove_context($id);
        
        return [
            'result' => true,
        ];
    }

    public static function context_delete_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
            ]
        );
    }
}

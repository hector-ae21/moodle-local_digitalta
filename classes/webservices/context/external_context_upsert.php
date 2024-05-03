<?php

/**
 * external_context_save
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../context.php');

use local_dta\Context;

class external_context_upsert extends external_api
{

    public static function context_upsert_parameters()
    {
        return new external_function_parameters(
            array(
                'component' => new external_value(PARAM_TEXT, 'Component'),
                'componentinstance' => new external_value(PARAM_INT, 'Component instance'),
                'modifier' => new external_value(PARAM_TEXT, 'Modifier'),
                'modifierinstance' => new external_value(PARAM_INT, 'Modifier instance'),
            )
        );
    }

    public static function context_upsert($component, $componentinstance, $modifier, $modifierinstance)
    {
        global $USER;

        if(!$contextId = Context::upsert_context($component, $componentinstance, $modifier, $modifierinstance)){
            return [
                'result' => false,
                'error' => 'Error saving context'
            ];
        }

        return [
            'result' => true,
            'contextid' => $contextId
        ];
    }

    public static function context_upsert_returns()
    {
        return new external_single_structure(
            [
                'result' => new external_value(PARAM_BOOL, 'Result'),
                'contextid' => new external_value(PARAM_INT, 'Section ID' , VALUE_OPTIONAL),
                'error' => new external_value(PARAM_RAW, 'Error message' , VALUE_OPTIONAL),
            ]
        );
    }
}

<?php

/**
 * external_external_ourcases_edit
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../ourcases.php');

use local_dta\OurCases;
class external_ourcases_edit extends external_api
{

    public static function ourcases_edit_parameters()
    {
        return new external_function_parameters(
            array(
                'ourcaseid' => new external_value(PARAM_INT, 'Our Case ID'),
                'status' => new external_value(PARAM_INT, 'Status', VALUE_DEFAULT, 0),
                'tags' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'ID del elemento')  , 'Tags' , VALUE_DEFAULT, []
                )
            )
        );
    }

    public static function ourcases_edit($ourcaseid, $status = 0, $tags = [])
    {
        
        if (!$ourcase = OurCases::get_case($ourcaseid)) {
            return array('result' => false, 'error' => 'Our case not found');
        }

        if (empty($ourcaseid) || empty($status) ) {
            return array('result' => false, 'error' => 'Empty Values');
        }

        $newcase = new stdClass();
        $newcase->id = $ourcaseid;
        $newcase->status = $status;
        $newcase->tags = $tags;


        if (!OurCases::update_case($newcase)) {
            return array('result' => false, 'error' => 'Failed to update our case');
        }

        return array('result' => true);
    }

    public static function ourcases_edit_returns()
    {
        return new external_single_structure(
            array(
                'error' => new external_value(PARAM_TEXT, 'Error message if any' , VALUE_OPTIONAL),
                'result' => new external_value(PARAM_BOOL, 'True if success, false otherwise' , VALUE_OPTIONAL)
            )
        );
    }
}

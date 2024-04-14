<?php

/**
 * external_ourcases_section_text_update
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/dta/classes/tags.php');

use local_dta\Tags;

class external_get_tags extends external_api
{

    public static function get_tags_parameters()
    {
        return new external_function_parameters(
            [
                'searchText' => new external_value(PARAM_TEXT, 'Search text', VALUE_DEFAULT, '%%')
            ]
        );
    }

    public static function get_tags($searchText = '%%')
    {
        $searchText = '%' . $searchText . '%';
        $tags = Tags::getTagsByText($searchText) ?? [];
        
        return $tags;
    }

    public static function get_tags_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Tag ID'),
                    'name' => new external_value(PARAM_TEXT, 'Tag name')
                )
            )
        );
    }
}

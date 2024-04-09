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

class external_create_tags extends external_api
{

    public static function create_tags_parameters()
    {
        return new external_function_parameters(
            [
                'tag' => new external_value(PARAM_TEXT, 'Tag name', VALUE_REQUIRED)
            ]
        );
    }

    public static function create_tags($tag)
    {
        $tag = Tags::addTag($tag);

        return $tag;
    }

    public static function create_tags_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Tag ID'),
                )
            )
        );
    }
}

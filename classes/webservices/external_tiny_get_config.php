<?php

/**
 * External Web Service
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../tiny_editor_handler.php');

use local_dta\tiny_editor_handler;


class external_tiny_get_config extends external_api
{

    public static function tiny_get_config_parameters()
    {
        return new external_function_parameters([]);
    }

    public static function tiny_get_config()
    {   
        return (new tiny_editor_handler())->get_config_editor([]);
    }

    public static function tiny_get_config_returns()
    {
        return new external_value(PARAM_RAW, 'Encoded JSON');
    }
}

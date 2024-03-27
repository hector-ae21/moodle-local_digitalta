<?php

/**
 * External Web Service
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../reflection.php');
require_once(__DIR__ . './../../lib.php');

use local_dta\CONSTANTS;
use local_dta\Reflection;


class external_reflection_upsert_text_section extends external_api
{

  public static function reflection_upsert_text_section_parameters()
  {
    return new external_function_parameters(
      array(
        'reflectionid' => new external_value(PARAM_INT, 'Our Case ID'),
        'group' => new external_value(PARAM_RAW, 'Available Groups', VALUE_OPTIONAL),
        'content' => new external_value(PARAM_RAW, 'Text', VALUE_OPTIONAL),
        'id' => new external_value(PARAM_INT, 'Sequence', VALUE_OPTIONAL)
      )
    );
  }

  public static function reflection_upsert_text_section($reflectionid, $group = "", $content = '', $id = null)
  { 
    $valid_groups = CONSTANTS::GROUPS;

    if (!array_key_exists($group, $valid_groups)) {
      return ['error' => 'Invalid group'];
    }

    if(!$upsert = Reflection::upsert_reflection_section_text($reflectionid, $group, $content, $id)){
      return ['error' => 'Error upserting reflection section'];
    }

    return ['sectionid' => $upsert->id];

  }

  public static function reflection_upsert_text_section_returns()
  {
    return new external_single_structure(
      [
          'error' => new external_value(PARAM_TEXT, 'Error message if any' , VALUE_OPTIONAL),
          'sectionid' => new external_value(PARAM_INT, 'Reflection ID', VALUE_OPTIONAL)
      ]
    );
  }
}

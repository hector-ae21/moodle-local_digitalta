<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * WebService to translate text
 *
 * @package   local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/digitalta/classes/utils/filterutils.php');

use local_digitalta\utils\FilterUtils;

/**
 * Class to translate text
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_translate_text extends external_api
{

  /**
   * Returns the description of the external function parameters
   *
   * @return external_function_parameters The external function parameters
   */
  public static function translate_text_parameters()
  {
    return new external_function_parameters(
      [
        'text' => new external_value(PARAM_RAW, 'Text to translate', VALUE_REQUIRED)
      ]
    );
  }

  /**
   * Get translated text
   *
   * @param  string  Text to translate
   * @return 
   */
  public static function translate_text($text)
  {
    global $PAGE;
    $PAGE->set_context(\context_system::instance());
    return [
      'response' => FilterUtils::apply_filters($text)
    ];
  }

  /**
   * Returns the description of the external function return value
   *
   * @return external_value The external function return value
   */
  public static function translate_text_returns()
  {
    return new external_single_structure(
      [
        'response' => new external_value(PARAM_RAW, 'Translated text')
      ]
    );
  }
}

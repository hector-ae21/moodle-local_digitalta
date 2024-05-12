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
 * Languages class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once($CFG->dirroot . '/local/dta/locallib.php');

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to manage the languages of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Languages
{
    /** @var string The table name for the languages. */
    private static $table = 'digital_languages';

    /**
     * Get all the languages
     *
     * @param  bool  $prioritize_installed Whether to prioritize the installed languages
     * @return array
     */
    public static function get_all_languages(bool $prioritize_installed = false)
    {
        global $DB, $USER;
        $languages = $DB->get_records(self::$table);
        $languages = array_values(array_map(function ($language) {
            return $language->name;
        }, $languages));
        if ($prioritize_installed) {
            $prioritized_languages = [$USER->lang];
            $prioritized_languages = array_merge($prioritized_languages, 
                array_diff(array_keys(get_string_manager()->get_list_of_translations()), $prioritized_languages));
            $languages = array_merge($prioritized_languages,
                array_diff($languages, $prioritized_languages));
        }
        $languages = array_map(function ($language) {
            return [
                'code' => $language,
                'name' => local_dta_get_element_translation('lang', $language)
            ];
        }, $languages);
        return $languages;
    }

    /**
     * Get the language by id
     *
     * @param  int         $id The language identifier
     * @return object|null The language
     */
    public static function get_language_by_id(int $id)
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get the language by name
     *
     * @param  string      $name The language name
     * @return object|null The language
     */
    public static function get_language_by_name(string $name)
    {
        global $DB;
        return $DB->get_record(self::$table, ['name' => $name]);
    }
}

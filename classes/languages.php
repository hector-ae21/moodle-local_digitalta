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

defined('MOODLE_INTERNAL') || die();

/**
 * This class is used to manage the languages of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Languages
{
    /**
     * Get all the languages
     *
     * @param  bool  $prioritize_installed Whether to prioritize the installed languages
     * @return array
     */
    public static function get_all_languages(bool $prioritize_installed = false)
    {
        $languages = get_string_manager()->get_list_of_languages();
        asort($languages);
        if ($prioritize_installed) {
            $prioritized_languages = [];
            $current_language = current_language();
            $prioritized_languages[$current_language] = $languages[$current_language];
            $installed_languages = get_string_manager()->get_list_of_translations();
            $prioritized_languages = array_merge($prioritized_languages,
                array_diff_key($installed_languages, $prioritized_languages));
            $languages = array_merge($prioritized_languages,
                array_diff_key($languages, $prioritized_languages));
        }
        $languages = array_values(array_map(function ($name, $code) {
            return (object) ['code' => $code, 'name' => $name];
        }, $languages, array_keys($languages)));
        return $languages;
    }
}

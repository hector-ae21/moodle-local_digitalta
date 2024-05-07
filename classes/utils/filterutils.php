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
 * Filter utils
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\utils;

/**
 * This class is used to operate with filters
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class FilterUtils
{
    /**
     * Apply filter to template object
     * 
     * @param object $template_object
     * @param string $context
     * @param string $format
     * @param array $options
     * @return void
     */
    public static function apply_filter_to_template_object($template_object, $contextid = 1, $format = FORMAT_HTML, $options = []) {
        $options['filter'] = true; // Asegúrate de que los filtros estén siempre activos
    
        if (is_array($template_object)) {
            // Procesa arrays
            foreach ($template_object as $key => &$value) {
                // Omitir campos que contienen 'url' en la clave
                if (stripos($key, 'url') === false) {
                    $value = self::apply_filter_to_template_object($value, $contextid, $format, $options);
                }
            }
            return $template_object;
        } elseif (is_object($template_object)) {
            // Procesa objetos
            foreach ($template_object as $property => &$value) {
                // Omitir propiedades que contienen 'url' en el nombre
                if (stripos($property, 'url') === false) {
                    $template_object->$property = self::apply_filter_to_template_object($value, $contextid, $format, $options);
                }
            }
            return $template_object;
        } elseif (is_string($template_object)) {
            // Aplica el filtro a cadenas
            return format_text($template_object, $format, $options, $contextid);
        } else {
            // Devuelve el valor sin cambios si no es array, objeto o cadena
            return $template_object;
        }
    }
}

<?php

/**
 * Filter utils
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\utils;

class filter_utils
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
    public static function apply_filter_to_template_object($template_object, $contextid = 1, $format = FORMAT_HTML, $options = []){
        $options['filter'] = true;

        if (is_array($template_object)) {
            foreach ($template_object as $key => &$value) {
                $value = self::apply_filter_to_template_object($value, $contextid, $format, $options);
            }
            return $template_object;
        } elseif (is_object($template_object)) {
            foreach ($template_object as $property => &$value) {
                $template_object->$property = self::apply_filter_to_template_object($value, $contextid, $format, $options);
            }
            return $template_object;
        } elseif (is_string($template_object)) {
            return format_text($template_object, $format, $options, $contextid);
        } else {
            return $template_object;
        }
    }
}

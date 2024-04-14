<?php

/**
 * external_ourcases_section_text_update
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta\utils;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/moodlelib.php');


class date_utils
{
    public static function time_elapsed_string($datetime, $full = false)
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $weeks = floor($diff->d / 7);
        $diff->d -= $weeks * 7;

        $parts = array();
        $time_units = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute'
        );

        foreach ($time_units as $key => $time) {
            $value = $key === 'w' ? $weeks : $diff->$key;
            if ($value > 0) {
                $parts[] = $value . ' ' . get_string(($value > 1 ? $time . 's' : $time), 'core', $value);
            }
        }

        if (!$parts) {
            return get_string('date_justnow', 'local_dta');
        }

        if (!$full) {
            $parts = array_slice($parts, 0, 1);
        }

        return get_string('date_timeago', 'local_dta', implode(', ', $parts));
    }
}

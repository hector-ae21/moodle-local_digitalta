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
 * External functions and service definitions.
 *
 * @package    local_dta
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    'local_dta_toggle_like_dislike' => array(
        'classname'   => 'local_dta/external/local_dta_external_toggle_like_dislike',
        'methodname'  => 'toggle_like_dislike',
        'description' => 'Toggle like or dislike for an experience',
        'type'        => 'write,read',
        'ajax'        => true,
    ),
    'local_dta_ourcases_section_text_upsert' => array(
        'classname'   => 'local_dta/external/local_dta_external_ourcases_section_text_upsert',
        'methodname'  => 'ourcases_section_text_upsert',
        'description' => 'Upsert the text of a section of the Our Cases page.',
        'type'        => 'write,read',
        'ajax'        => true,
    ),
);
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

$functions = [
    'local_dta_reactions_toggle_like_dislike' => [
        'classname'   => 'external_reactions_toggle_like_dislike',
        'methodname'  => 'reactions_toggle_like_dislike',
        'classpath'   => 'local/dta/classes/webservices/external_reactions_toggle_like_dislike.php',
        'description' => 'Toggle like or dislike for an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_myexperience_save_comment' => [
        'classname'   => 'external_myexperience_save_comment',
        'methodname'  => 'myexperience_save_comment',
        'classpath'   => 'local/dta/classes/webservices/external_myexperience_save_comment.php',
        'description' => 'Save a comment for an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_myexperience_get_comments' => [
        'classname'   => 'external_myexperience_get_comments',
        'methodname'  => 'myexperience_get_comments',
        'classpath'   => 'local/dta/classes/webservices/external_myexperience_get_comments.php',
        'description' => 'Get comments for an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_ourcases_section_text_upsert' => [
        'classname'   => 'external_ourcases_section_text_upsert',
        'methodname'  => 'ourcases_section_text_upsert',
        'classpath'   => 'local/dta/classes/webservices/external_ourcases_section_text_upsert.php',
        'description' => 'Upsert the text of a section of the Our Cases page.',
        'type'        => 'write',
        'requirelogin' => false,
        'ajax'        => true,
    ],
    'local_dta_ourcases_section_text_delete' => [
        'classname'   => 'external_ourcases_section_text_delete',
        'methodname'  => 'ourcases_section_text_delete',
        'classpath'   => 'local/dta/classes/webservices/external_ourcases_section_text_delete.php',
        'description' => 'Delete the text of a section of the Our Cases page.',
        'type'        => 'write',
        'requirelogin' => false,
        'ajax'        => true,
    ],
];
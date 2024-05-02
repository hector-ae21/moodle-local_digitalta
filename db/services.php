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
    // REACTIONS
    'local_dta_reactions_get_comments' => [
        'classname'   => 'external_reactions_get_comments',
        'methodname'  => 'reactions_get_comments',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_get_comments.php',
        'description' => 'Get comments for an instance of an experience or a case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_reactions_save_comment' => [
        'classname'   => 'external_reactions_save_comment',
        'methodname'  => 'reactions_save_comment',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_save_comment.php',
        'description' => 'Save a comment for an instance of an experience or a case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_reactions_toggle_like_dislike' => [
        'classname'   => 'external_reactions_toggle_like_dislike',
        'methodname'  => 'reactions_toggle_like_dislike',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_toggle_like_dislike.php',
        'description' => 'Toggle like or dislike for an instance of an experience or a case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_reactions_send_report' => [
        'classname'   => 'external_reactions_send_report',
        'methodname'  => 'reactions_send_report',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_send_report.php',
        'description' => 'Send a report for an experience or a case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // REFLECTIONS
    'local_dta_reflection_upsert_text_section' => [
        'classname'   => 'external_reflection_upsert_text_section',
        'methodname'  => 'reflection_upsert_text_section',
        'classpath'   => 'local/dta/classes/webservices/reflection/external_reflection_upsert_text_section.php',
        'description' => 'Upsert the text of a section of a reflection',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // EXPERIENCES
    'local_dta_myexperience_upsert' => [
        'classname'   => 'external_myexperience_upsert',
        'methodname'  => 'myexperience_upsert',
        'classpath'   => 'local/dta/classes/webservices/myexperience/external_myexperience_upsert.php',
        'description' => 'upsert an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_myexperience_toggle_status' => [
        'classname'   => 'external_myexperience_toggle_status',
        'methodname'  => 'myexperience_toggle_status',
        'classpath'   => 'local/dta/classes/webservices/myexperience/external_myexperience_toggle_status.php',
        'description' => 'Toggle the status of an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // OUR CASES
    'local_dta_ourcases_section_text_upsert' => [
        'classname'   => 'external_ourcases_section_text_upsert',
        'methodname'  => 'ourcases_section_text_upsert',
        'classpath'   => 'local/dta/classes/webservices/cases/external_ourcases_section_text_upsert.php',
        'description' => 'Upsert the text of a section of the Our Cases page.',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_ourcases_section_text_delete' => [
        'classname'   => 'external_ourcases_section_text_delete',
        'methodname'  => 'ourcases_section_text_delete',
        'classpath'   => 'local/dta/classes/webservices/cases/external_ourcases_section_text_delete.php',
        'description' => 'Delete the text of a section of the Our Cases page.',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_ourcases_edit' => [
        'classname'   => 'external_ourcases_edit',
        'methodname'  => 'ourcases_edit',
        'classpath'   => 'local/dta/classes/webservices/cases/external_ourcases_edit.php',
        'description' => 'Edit an Our Case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_ourcases_get' => [
        'classname'   => 'external_ourcases_get',
        'methodname'  => 'ourcases_get',
        'classpath'   => 'local/dta/classes/webservices/cases/external_ourcases_get.php',
        'description' => 'Get all cases',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // TAGS
    'local_dta_get_tags' => [
        'classname'   => 'external_get_tags',
        'methodname'  => 'get_tags',
        'classpath'   => 'local/dta/classes/webservices/tags/external_get_tags.php',
        'description' => 'Get all tags by text',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_create_tags' => [
        'classname'   => 'external_create_tags',
        'methodname'  => 'create_tags',
        'classpath'   => 'local/dta/classes/webservices/tags/external_create_tags.php',
        'description' => 'Create tags',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ], 

    //FILES
    'local_dta_upload_file_from_draft' => [
        'classname'   => 'external_upload_file_from_draft',
        'methodname'  => 'upload_file_from_draft',
        'classpath'   => 'local/dta/classes/webservices/files/external_upload_file_from_draft.php',
        'description' => 'Upload a file',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // RESOURCES
    'local_dta_resources_upsert' => [
        'classname'   => 'external_resources_upsert',
        'methodname'  => 'resources_upsert',
        'classpath'   => 'local/dta/classes/webservices/resources/external_resources_upsert.php',
        'description' => 'upsert a resource',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_resources_get' => [
        'classname'   => 'external_resources_get',
        'methodname'  => 'resources_get',
        'classpath'   => 'local/dta/classes/webservices/resources/external_resources_get.php',
        'description' => 'get resources',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // CONTEXT 
    'local_dta_context_upsert' => [
        'classname'   => 'external_context_upsert',
        'methodname'  => 'context_upsert',
        'classpath'   => 'local/dta/classes/webservices/context/external_context_upsert.php',
        'description' => 'upsert a context',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    
];
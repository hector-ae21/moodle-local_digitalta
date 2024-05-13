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

    // Experiences
    'local_dta_experiences_get' => [
        'classname'   => 'external_experiences_get',
        'methodname'  => 'experiences_get',
        'classpath'   => 'local/dta/classes/webservices/experiences/external_experiences_get.php',
        'description' => 'Get all experiences',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_experiences_upsert' => [
        'classname'   => 'external_experiences_upsert',
        'methodname'  => 'experiences_upsert',
        'classpath'   => 'local/dta/classes/webservices/experiences/external_experiences_upsert.php',
        'description' => 'upsert an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_experiences_toggle_status' => [
        'classname'   => 'external_experiences_toggle_status',
        'methodname'  => 'experiences_toggle_status',
        'classpath'   => 'local/dta/classes/webservices/experiences/external_experiences_toggle_status.php',
        'description' => 'Toggle the status of an experience',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Cases
    'local_dta_cases_edit' => [
        'classname'   => 'external_cases_edit',
        'methodname'  => 'cases_edit',
        'classpath'   => 'local/dta/classes/webservices/cases/external_cases_edit.php',
        'description' => 'Edit a Case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_cases_get' => [
        'classname'   => 'external_cases_get',
        'methodname'  => 'cases_get',
        'classpath'   => 'local/dta/classes/webservices/cases/external_cases_get.php',
        'description' => 'Get all cases',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Chat
    'local_dta_get_chat_rooms' => [
        'classname'   => 'external_chat_services',
        'methodname'  => 'get_chat_rooms',
        'classpath'   => 'local/dta/classes/webservices/chat/external_chat_services.php',
        'description' => 'Get chat rooms',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_add_user_to_chat_room' => [
        'classname'   => 'external_chat_services',
        'methodname'  => 'add_user_to_chat_room',
        'classpath'   => 'local/dta/classes/webservices/chat/external_chat_services.php',
        'description' => 'Add a user to a chat room',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_add_message' => [
        'classname'   => 'external_chat_services',
        'methodname'  => 'add_message',
        'classpath'   => 'local/dta/classes/webservices/chat/external_chat_services.php',
        'description' => 'Add a message to a chat room',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_get_messages' => [
        'classname'   => 'external_chat_services',
        'methodname'  => 'get_messages',
        'classpath'   => 'local/dta/classes/webservices/chat/external_chat_services.php',
        'description' => 'Get messages from a chat room',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Context 
    'local_dta_context_upsert' => [
        'classname'   => 'external_context_upsert',
        'methodname'  => 'context_upsert',
        'classpath'   => 'local/dta/classes/webservices/context/external_context_upsert.php',
        'description' => 'upsert a context',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_context_delete' => [
        'classname'   => 'external_context_delete',
        'methodname'  => 'context_delete',
        'classpath'   => 'local/dta/classes/webservices/context/external_context_delete.php',
        'description' => 'upsert a context',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Files
    'local_dta_prepare_draft_area_html' => [
        'classname'   => 'external_files_prepare_draft_area_html',
        'methodname'  => 'prepare_draft_area_html',
        'classpath'   => 'local/dta/classes/webservices/files/external_files_prepare_draft_area_html.php',
        'description' => 'Prepare draft area html',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_upload_file_from_draft' => [
        'classname'   => 'external_files_upload_from_draft',
        'methodname'  => 'upload_file_from_draft',
        'classpath'   => 'local/dta/classes/webservices/files/external_files_upload_from_draft.php',
        'description' => 'Upload a file',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Languages
    'local_dta_languages_get' => [
        'classname'   => 'external_languages_get',
        'methodname'  => 'languages_get',
        'classpath'   => 'local/dta/classes/webservices/languages/external_languages_get.php',
        'description' => 'Get all languages',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Mentors
    'local_dta_mentors_load' => [
        'classname'   => 'external_mentors_load',
        'methodname'  => 'load_mentors',
        'classpath'   => 'local/dta/classes/webservices/mentors/external_mentors_load.php',
        'description' => 'load mentors',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Reactions
    'local_dta_reactions_get_comments' => [
        'classname'   => 'external_reactions_get_comments',
        'methodname'  => 'reactions_get_comments',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_get_comments.php',
        'description' => 'Get comments for an instance of a component',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_reactions_add_comment' => [
        'classname'   => 'external_reactions_add_comment',
        'methodname'  => 'reactions_add_comment',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_add_comment.php',
        'description' => 'Add a comment for an instance of a component',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_reactions_toggle_like_dislike' => [
        'classname'   => 'external_reactions_toggle_like_dislike',
        'methodname'  => 'reactions_toggle_like_dislike',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_toggle_like_dislike.php',
        'description' => 'Toggle like or dislike for an instance of a a component',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_reactions_toggle_report' => [
        'classname'   => 'external_reactions_toggle_report',
        'methodname'  => 'reactions_toggle_report',
        'classpath'   => 'local/dta/classes/webservices/reactions/external_reactions_toggle_report.php',
        'description' => 'toggle a report for an experience or a case',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Resources
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

    // Sections
    'local_dta_sections_upsert' => [
        'classname'    => 'external_sections_upsert',
        'methodname'   => 'sections_upsert',
        'classpath'    => 'local/dta/classes/webservices/sections/external_sections_upsert.php',
        'description'  => 'upsert a section',
        'type'         => 'write',
        'requirelogin' => true,
        'ajax'         => true,
    ],
    'local_dta_sections_delete' => [
        'classname'    => 'external_sections_delete',
        'methodname'   => 'sections_delete',
        'classpath'    => 'local/dta/classes/webservices/sections/external_sections_delete.php',
        'description'  => 'delete a section',
        'type'         => 'write',
        'requirelogin' => true,
        'ajax'         => true,
    ],

    // Tags
    'local_dta_tags_get' => [
        'classname'   => 'external_tags_get',
        'methodname'  => 'tags_get',
        'classpath'   => 'local/dta/classes/webservices/tags/external_tags_get.php',
        'description' => 'Get all tags by text',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_create_tags' => [
        'classname'   => 'external_tags_create',
        'methodname'  => 'create_tags',
        'classpath'   => 'local/dta/classes/webservices/tags/external_tags_create.php',
        'description' => 'Create tags',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Themes
    'local_dta_themes_get' => [
        'classname'   => 'external_themes_get',
        'methodname'  => 'themes_get',
        'classpath'   => 'local/dta/classes/webservices/themes/external_themes_get.php',
        'description' => 'Get all themes by text',
        'type'        => 'read',
        'requirelogin' => true,
        'ajax'        => true,
    ],

    // Tutoring
    'local_dta_create_tutor_disponibility' => [
        'classname'   => 'external_create_tutor_disponibility',
        'methodname'  => 'create_tutor_disponibility',
        'classpath'   => 'local/dta/classes/webservices/tutoring/external_create_tutor_disponibility.php',
        'description' => 'Create a tutor disponibility',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_update_tutor_disponibility' => [
        'classname'   => 'external_update_tutor_disponibility',
        'methodname'  => 'update_tutor_disponibility',
        'classpath'   => 'local/dta/classes/webservices/tutoring/external_update_tutor_disponibility.php',
        'description' => 'Update a tutor disponibility',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
    'local_dta_delete_tutor_disponibility' => [
        'classname'   => 'external_delete_tutor_disponibility',
        'methodname'  => 'delete_tutor_disponibility',
        'classpath'   => 'local/dta/classes/webservices/tutoring/external_delete_tutor_disponibility.php',
        'description' => 'Delete a tutor disponibility',
        'type'        => 'write',
        'requirelogin' => true,
        'ajax'        => true,
    ],
];

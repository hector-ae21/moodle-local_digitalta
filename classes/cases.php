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
 * Cases class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once($CFG->dirroot . '/local/dta/classes/case.php');
require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/context.php');
require_once($CFG->dirroot . '/local/dta/classes/reactions.php');
require_once($CFG->dirroot . '/local/dta/classes/resources.php');
require_once($CFG->dirroot . '/local/dta/classes/sections.php');
require_once($CFG->dirroot . '/local/dta/classes/tags.php');
require_once($CFG->dirroot . '/local/dta/classes/themes.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/dateutils.php');

use local_dta\Components;
use local_dta\Context;
use local_dta\Reactions;
use local_dta\Resources;
use local_dta\Sections;
use local_dta\StudyCase;
use local_dta\Tags;
use local_dta\Themes;
use local_dta\utils\DateUtils;

use Exception;
use stdClass;

/**
 * This class is used to manage the cases of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Cases
{
    /** @var string The table name for the cases. */
    private static $table = 'digital_cases';

    /** @var string The table name for the sections. */
    private static $table_sections = 'digital_sections';

    /**
     * Get a case by this id
     * 
     * @param  int           $id The id of the case
     * @param  bool          $extra_fields If true, it will return the extra fields
     * @return stdClass|null The case
     */
    public static function get_case(int $id, bool $extra_fields = true)
    {
        global $DB;
        if (!$case = $DB->get_record(self::$table, ['id' => $id])) {
            return null;
        }
        if ($extra_fields) {
            $case = self::get_extra_fields($case);
        }
        return $case;
    }

    /**
     * Get all cases
     * 
     * @param  bool  $extra_fields If true, it will return the extra fields
     * @return array The cases
     */
    public static function get_all_cases($extra_fields = true, $active = 1)
    {
        global $DB;
        $cases = $DB->get_records(self::$table , ['status' => $active]);
        if (!$extra_fields) {
            return array_values($cases);
        }
        $cases = array_values(array_map(function ($case) {
            return self::get_extra_fields($case);
        }, $cases));
        return $cases;
    }

    /**
     * Get cases by user
     *
     * @param  int   $userid The id of the user
     * @return array The cases
     */
    public static function get_cases_by_user($userid)
    {
        global $DB;
        $cases = $DB->get_records(self::$table, ['userid' => $userid]);
        $cases = array_values(array_map(function ($case) {
            return self::get_extra_fields($case);
        }, $cases));
        return $cases;
    }

    /**
     * Get cases by experience
     *
     * @param  int   $experienceid The id of the experience
     * @return array The cases
     */
    public static function get_cases_by_experience($experienceid)
    {
        global $DB;
        return $DB->get_records(self::$table, ['experienceid' => $experienceid], 'timecreated DESC');
    }

    /**
     * Get extra fields for cases
     * 
     * @param  object $case The case
     * @return object The case with extra fields
     */
    public static function get_extra_fields($case)
    {
        global $PAGE;
        // Get the user data
        $user = get_complete_user_data("id", $case->userid);
        $picture = new \user_picture($user);
        $picture->size = 101;
        $case->user = [
            'id' => $user->id,
            'name' => $user->firstname . " " . $user->lastname,
            'email' => $user->email,
            'imageurl' => $picture->get_url($PAGE)->__toString(),
            'profileurl' => new \moodle_url('/user/profile.php', ['id' => $user->id])
        ];
        // Get the themes for the case
        $themes = Themes::get_themes_for_component('case', $case->id);
        $case->themes = array_values(array_map(function($theme) {
            return (object) [
                'name' => $theme->name,
                'id' => $theme->id
            ];
        }, $themes));
        // Get the tags for the case
        $tags = Tags::get_tags_for_component('case', $case->id);
        $case->tags = array_values(array_map(function($tag) {
            return (object) [
                'name' => $tag->name,
                'id' => $tag->id
            ];
        }, $tags));
        $case->fixed_tags = [
            ['name' => $case->lang]
        ];
        // Get the case picture
        $case->pictureurl = self::get_picture_url($case);
        // Get the case reactions
        $case->reactions = Reactions::get_reactions_for_render_component(
            Components::get_component_by_name('case')->id,
            $case->id
        );
        // Get the case creation date
        $case->timecreated = DateUtils::time_elapsed_string($case->timecreated);
        return $case;
    }

    /**
     * Gets the picture url for an case
     *
     * @param  object $case The case
     * @return string|bool The picture url or false if there is no picture
     */
    public static function get_picture_url($case)
    {
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            \context_system::instance()->id,
            'local_dta',
            'case_picture',
            $case->id,
            'sortorder DESC, id ASC',
            false
        );

        if (empty($files)) {
            return false;
        }

        $file = reset($files);
        $pictureurl = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
        return $pictureurl;
    }

    /**
     * Add a case
     *
     * @param  object      $case The case to add
     * @param  int         $status The case status
     * @return object|null The case
     */
    public static function add_case($case, $status = 0)
    {
        global $CFG, $DB;
        // Create the case
        $record = self::prepare_metadata_record($case);
        $record->id = $DB->insert_record(self::$table, $record);
        // Create default resource
        $resource               = new stdClass();
        $resource->name         = $record->title;
        $resource->description  = $record->description;
        $resource->type         = Resources::get_type_by_name('Study Case')->id;
        $resource->format       = Resources::get_format_by_name('Link')->id;
        $resource->lang         = $record->lang;
        $resource->path         = $CFG->wwwroot . '/local/dta/pages/cases/view.php?id=' . $record->id;
        $resource = Resources::upsert_resource($resource);
        $record->resourceid = $resource->id;
        $DB->update_record(self::$table, $record);
        // Create default section
        $section = new stdClass();
        $section->component         = Components::get_component_by_name('case')->id;
        $section->componentinstance = $record->id;
        $section->groupid           = Sections::get_group_by_name('General')->id;
        $section->sequence          = 1;
        $section->sectiontype       = Sections::get_type_by_name('text')->id;
        $section->content           = "";
        $section = Sections::upsert_section($section);
        // Update theme and tags
        if (!empty($case->themes)) {
            Themes::update_themes('case', $record->id, $case->themes);
        }
        if (!empty($case->tags)) {
            Tags::update_tags('case', $record->id, $case->tags);
        }
        return new StudyCase($record);

    }

    /**
     * Update a case
     *
     * @param  object      $case The case to update
     * @return object|null The case
     */
    public static function update_case($case)
    {
        global $DB;
        // Get the current case
        if (!$current_case = $DB->get_record(self::$table, ['id' => $case->id])) {
            throw new Exception('Error case not found');
        }
        // Validate the metadata
        self::prepare_metadata_record($case, $current_case);
        // Update the time modified
        $case->timemodified = date('Y-m-d H:i:s', time());
        // Update the case
        if (!$DB->update_record(self::$table,  $case)) {
            throw new Exception('Error adding case section text to the database.');
            return false;
        }
        $case = self::get_case($case->id, false);
        // Update the default resource
        $resource              = Resources::get_resource($case->resourceid);
        $resource->name        = $case->title;
        $resource->description = $case->description;
        Resources::upsert_resource($resource);
        // Update the themes and tags
        if (!empty($case->themes)) {
            Themes::update_themes('case', $case->id, $case->themes);
        }
        if (!empty($case->tags)) {
            Tags::update_tags('case', $case->id, $case->tags);
        }
        return new StudyCase($case);    
    }

    /**
    * Prepare metadata record for database insertion.
    * 
    * @param  object    $case The case object.
    * @param  object    $current_case The current case object.
    * @return object    The prepared metadata record.
    * @throws Exception If the case type is invalid.
    */
    private static function prepare_metadata_record($case, $current_case = null)
    {
        global $USER;
        self::validate_metadata($case);
        $record               = new stdClass();
        $record->experienceid = $case->experienceid ?? $current_case->experienceid ?? 0;
        $record->userid       = $case->userid ?? $current_case->userid ?? $USER->id;
        $record->title        = $case->title;
        $record->description  = $case->description ?? $current_case->description ?? '';
        $record->lang         = $case->lang;
        $record->status       = $case->status ?? $current_case->status ?? 0;
        $record->timecreated  = $case->timecreated ?? $current_case->timecreated ?? date('Y-m-d H:i:s', time());
        $record->timemodified = date('Y-m-d H:i:s', time());
        return $record;
    }

    /**
     * Validate the metadata of an case.
     * 
     * @param  object $case The case object to check.
     */
    private static function validate_metadata(object $case) {
        $keys = ['title', 'lang'];
        $missing_keys = [];
        foreach ($keys as $key) {
            if (!property_exists($case, $key) || empty($case->{$key}) || is_null($case->{$key})) {
                $missing_keys[] = $key;
            }
        }
        if (!empty($missing_keys)) {
            throw new Exception('Error adding case. Missing fields: ' . implode(', ', $missing_keys));
        }
    }

    /**
     * Delete a case
     *
     * @param  object $case The case to delete
     * @return bool   True if the case was deleted, false otherwise
     */
    public static function delete_case($case)
    {
        global $DB, $USER;
        if (!$DB->record_exists(self::$table, ['id' => $case->id])) {
            throw new Exception('Error case not found');
        }
        // Check permissions
        if (!local_dta_check_permissions($case, $USER)) {
            throw new Exception('Error permissions');
        }
        // Delete the sections
        $sections = Sections::get_sections([
            'component' => Components::get_component_by_name('case')->id,
            'componentinstance' => $case->id
        ]);
        foreach ($sections as $section) {
            Sections::delete_section($section->id);
        }
        // Delete the resource
        Resources::delete_resource($case->resourceid);
        // TODO: Delete the contexts, resources and reactions
        // Delete the case
        return $DB->delete_records(self::$table, ['id' => $case->id]);
    }










    /**
     * Populate the context of a resource.
     * 
     * @param $unique_context object The unique context.
     * 
     * @return object The resource with the populated context.
     */
    public static function populate_context(object $unique_context): object | null{
        $resource = self::get_case($unique_context->modifierinstance);
        $resource->context = $unique_context;
        $resource->section_header = [];
        return $resource;
    }

    /**
     * Get resources by context and component.
     * @param string $component The component.
     * @param int $componentinstance The component instance.
     * @return array The resources.
     */
    public static function get_cases_by_context_component(string $component, int $componentinstance) : array{
        $context = Context::get_contexts_by_component($component, $componentinstance, 'case');
        $resources = array();
        foreach ($context as $unique_context) {
            $resources[] = self::populate_context($unique_context);
        }
        return array_values($resources);
    }
}

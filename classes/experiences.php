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
 * Experiences class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once($CFG->dirroot . '/local/dta/classes/components.php');
require_once($CFG->dirroot . '/local/dta/classes/reactions.php');
require_once($CFG->dirroot . '/local/dta/classes/tags.php');
require_once($CFG->dirroot . '/local/dta/classes/utils/dateutils.php');

use local_dta\Components;
use local_dta\Reactions;
use local_dta\Tags;
use local_dta\utils\DateUtils;

use Exception;
use stdClass;

/**
 * This class is used to manage the experiences of the plugin
 *
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Experiences
{
    /** @var string The table name for the experiences. */
    private static $table = 'digital_experiences';

    /**
     * Get an experience by this id
     * 
     * @param  int         $id The id of the experience
     * @param  bool        $extra_fields If true, it will return the extra fields
     * @return object|null The experience
     */
    public static function get_experience(int $id, bool $extra_fields = true)
    {
        global $DB;
        if (!$experience = $DB->get_record(self::$table, ['id' => $id])) {
            return null;
        }
        if ($extra_fields) {
            $experience = self::get_extra_fields($experience);
        }
        return $experience;
    }

    /**
     * Get all the experiences
     * 
     * @param  bool  $include_privates If true, it will include the private experiences
     * @param  bool  $extra_fields If true, it will return the extra fields
     * @return array The experiences
     */
    public static function get_all_experiences(bool $include_privates = true, bool $extra_fields = true)
    {
        global $DB;
        $experiences = $DB->get_records(self::$table,
            $include_privates ? null : ['visible' => 1], 'timecreated DESC');
        if (!$extra_fields) {
            return array_values($experiences);
        }
        $experiences = array_values(array_map(function ($experience) {
            return self::get_extra_fields($experience);
        }, $experiences));
        return $experiences;
    }

    /**
     * Get experiences by user
     *
     * @param  int   $userid The id of the user
     * @return array The experiences
     */
    public static function get_experiences_by_user($userid)
    {
        global $DB;
        $experiences = $DB->get_records(self::$table, ['userid' => $userid]);
        $experiences = array_values(array_map(function ($experience) {
            return self::get_extra_fields($experience);
        }, $experiences));
        return $experiences;
    }

    /**
     * Get latest experiences
     * 
     * @param  int   $limit The limit of experiences
     * @param  bool  $include_privates If true, it will include the private experiences
     * @return array The latest experiences
     */
    public static function get_latest_experiences($limit = 3, $include_privates = true)
    {
        global $DB;
        $experiences = $DB->get_records(
            self::$table,
            $include_privates ? null : ['visible' => 1],
            'timecreated DESC',
            '*',
            0,
            $limit
        );
        $experiences = array_values(array_map(function ($experience) {
            return self::get_extra_fields($experience);
        }, $experiences));
        return $experiences;
    }

    /**
     * Get extra fields for experiences
     * 
     * @param  object $experience The experience
     * @return object The experience with extra fields
     */
    public static function get_extra_fields($experience)
    {
        global $PAGE;
        // Get the user data 
        $user = get_complete_user_data('id', $experience->userid);
        $user_picture = new \user_picture($user);
        $user_picture->size = 101;
        $experience->user = [
            'id' => $user->id,
            'name' => $user->firstname . " " . $user->lastname,
            'email' => $user->email,
            'imageurl' => $user_picture->get_url($PAGE)->__toString(),
            'profileurl' => new \moodle_url('/user/profile.php', ['id' => $user->id])
        ];
        // Get the tags for the experience
        $tags = Tags::get_tags_for_component('experience', $experience->id);
        $experience->tags = array_values(array_map(function ($tag) {
            return (object) [
                'name' => $tag->name,
                'id' => $tag->id
            ];
        }, $tags));
        $experience->fixed_tags = [
            ['name' => $experience->visible ? 'Public' : 'Private'],
            ['name' => $experience->lang]
        ];
        // Get the experience picture
        $experience->pictureurl = self::get_picture_url($experience);
        // Get the experience reactions
        $experience->reactions = Reactions::get_reactions_for_render_experience($experience->id);
        // Get the experience creation date
        $experience->timecreated = DateUtils::time_elapsed_string($experience->timecreated);
        return $experience;
    }

    /**
     * Gets the picture url for an experience
     *
     * @param  object $experience The experience
     * @return string|bool The picture url or false if there is no picture
     */
    public static function get_picture_url($experience)
    {
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            \context_system::instance()->id,
            'local_dta',
            'experience_picture',
            $experience->id,
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
     * Upsert an experience
     *
     * @param  object    $experience The experience to upsert
     * @return object    The upserted experience
     * @throws Exception If metadata fields are missing.
     */
    public static function upsert_experience($experience) : object
    {
        global $DB;
        $record = new stdClass;
        $record = self::prepare_metadata_record($experience);
        if (property_exists($experience, 'id')
                and !empty($experience->id)
                and $experience->id > 0) {
            if (!$current_experience = self::get_experience($experience->id)) {
                return null;
            }
            $record->id          = $current_experience->id;
            $record->timecreated = $current_experience->timecreated;
            $DB->update_record(self::$table, $record);
        } else {
            $record->id = $DB->insert_record(self::$table, $record);
        }
        // SECTIONS TODO: Description to section
        if (!empty($experience->themes)) {
            Themes::update_themes('experience', $record->id, $experience->themes);
        }
        if (!empty($experience->tags)) {
            Tags::update_tags('experience', $record->id, $experience->tags);
        }
        return new Experience($record);
    }

    /**
    * Prepare metadata record for database insertion.
    * 
    * @param  object    $experience The experience object.
    * @return object    The prepared metadata record.
    * @throws Exception If the experience type is invalid.
    */
    private static function prepare_metadata_record($experience)
    {
        global $USER;
        self::validate_metadata($experience);
        $record               = new stdClass();
        $record->userid       = $USER->id;
        $record->title        = $experience->title;
        $record->lang         = $experience->lang;
        $record->visible      = $experience->visible;
        $record->status       = $experience->status ?? 0;
        $record->timecreated  = date('Y-m-d H:i:s', time());
        $record->timemodified = date('Y-m-d H:i:s', time());
        return $record;
    }

    /**
     * Validate the metadata of an experience.
     * 
     * @param  object $experience The experience object to check.
     */
    private static function validate_metadata(object $experience) {
        $keys = ['title', 'lang'];
        $missing_keys = [];
        foreach ($keys as $key) {
            if (!property_exists($experience, $key) || empty($experience->{$key}) || is_null($experience->{$key})) {
                $missing_keys[] = $key;
            }
        }
        if (!empty($missing_keys)) {
            throw new Exception('Error adding experience. Missing fields: ' . implode(', ', $missing_keys));
        }
    }

    /**
     * Delete an experience
     *
     * @param  object $experience The experience to delete
     * @return bool   True if the experience was deleted, false otherwise
     */
    public static function delete_experience($experience)
    {
        global $DB, $USER;
        if (!$DB->record_exists(self::$table, ['id' => $experience->id])) {
            throw new Exception('Error experience not found');
        }
        // Check permissions
        if (!local_dta_check_permissions($experience, $USER)) {
            throw new Exception('Error permissions');
        }
        // Delete the sections
        $sections = Sections::get_sections([
            'component' => [Components::get_component_by_name('experience')->id],
            'componentinstance' => [$experience->id]
        ]);
        foreach ($sections as $section) {
            Sections::delete_section($section->id);
        }
        // TODO: Delete the contexts, resources and reactions
        // Delete the experience
        return $DB->delete_records(self::$table, ['id' => $experience->id]);
    }

    /**
     * Change the status of an experience
     * 
     * @param int $experienceid
     */
    public static function change_status_experience($experienceid)
    {
        global $DB, $USER;

        if (!$experience = self::get_experience($experienceid, false)) {
            throw new Exception('Error experience not found');
        }

        if (!local_dta_check_permissions($experience, $USER)) {
            throw new Exception('Error permissions');
        }
        $status = $experience->status == 1 ? 0 : 1;
        $record = new stdClass();
        $record->id = $experience->id;
        $record->status = $status;

        if (!$DB->update_record(self::$table, $record)) {
            throw new Exception('Error updating experience');
        };
        return new Experience($record);
    }

}

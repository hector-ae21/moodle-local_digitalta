<?php

/**
 * Experience class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dta;

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/reactions.php');

use stdClass;
use local_dta\Reaction;

class Experience
{
    private static $table = 'digital_experiences';
    private $db;

    private $id;
    private $title;
    private $description;

    private $userid;

    private $date;
    private $lang;

    /** @var string The picture draft id of the experience */
    private $picture;

    private $visible;
    private $status;


    /**
     * Constructor.
     * 
     * @param $data array The data to populate the experience with.
     */
    public function __construct($experience = null)
    {
        foreach ($experience as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = ($key === 'description' && is_array($value))
                    ? $value['text']
                    : $value;
            }
        }
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    /**
     * Get an experience by this id
     * 
     * @param int $id
     * @return stdClass|null
     */
    public static function get_experience($id)
    {
        global $DB;
        $experience = $DB->get_record(self::$table, ['id' => $id]);
        if ($experience) {
            $experience = self::get_extra_fields([$experience])[0];
        }
        return $experience;
    }

    /**
     * Get experiences by user
     * 
     */
    public static function get_experiences_by_user($userid)
    {
        global $DB;
        $experiences = array_values($DB->get_records(self::$table, ['userid' => $userid]));
        $experiences = self::get_extra_fields($experiences);
        return $experiences;
    }

    /**
     * Get an experience by this id
     * 
     * @param bool $includePrivates 
     * @return array|null
     */
    public static function get_all_experiences($includePrivates = true)
    {
        global $DB;
        $experiences = array_values($DB->get_records(self::$table, $includePrivates ? null : ['visible' => 1]));
        $experiences = self::get_extra_fields($experiences);
        return $experiences;
    }

    /**
     * Get extra fields for experiences
     * 
     * @param array $experiences
     * @return array
     */
    public static function get_extra_fields($experiences)
    {
        global $PAGE;
        foreach ($experiences as $experience) {
            $user = get_complete_user_data("id", $experience->userid);
            $picture = new \user_picture($user);
            $picture->size = 101;
            $experience->date = date("d/m/Y", strtotime($experience->date));
            $experience->user = [
                'id' => $user->id,
                'name' => $user->firstname . " " . $user->lastname,
                'email' => $user->email,
                'imageurl' => $picture->get_url($PAGE)->__toString(),
                'profileurl' => new \moodle_url('/user/profile.php', ['id' => $user->id])
            ];
            $tag1 = new stdClass();
            $tag1->name = $experience->visible ? 'Public' : 'Private';
            $tag1->color = '#b0b0b0';
            $tag2 = new stdClass();
            $tag2->name = $experience->lang;
            $tag2->color = '#b0b0b0';
            $experience->tags = array_values(array($tag1, $tag2));

            $experience->pictureurl = self::get_picture_url($experience);
            $experience->reactions = Reaction::get_reactions_for_render_experience($experience->id);
        }
        return $experiences;
    }

    /**
     * Gets the picture url for the experience.
     */
    public static function get_picture_url($experience) {
        global $CFG;

        $fs = get_file_storage();
        $files = $fs->get_area_files(
            \context_system::instance()->id,
            'local_dta',
            'picture',
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
     * Add an experience
     *
     * @param object $experience
     * @return object
     */
    public static function store($experience)
    {
        global $DB;
        if (empty($experience->title) || empty($experience->description) || empty($experience->date) || empty($experience->lang)) {
            throw new Exception('Error adding experience');
        }

        $record = new \stdClass();
        $record->title = $experience->title;
        $record->description = $experience->description['text'];
        $record->date = $experience->date;
        $record->lang = $experience->lang;
        $record->userid = $experience->userid;
        $record->visible = $experience->visible;
        $record->status = $experience->status ?? 0;


        if($experience->id) {
            $record->id = $experience->id;
            $DB->update_record(self::$table, $record);
        } else {
            $record->id = $DB->insert_record(self::$table, $record);
        }

        return new Experience($record);
    }


    /**
     * Delete an experience
     *
     * @param object $experience
     * @return bool
     */
    public static function delete_experience($experience)
    {
        global $DB, $USER;
        if (!self::check_experience($experience->id)) {
            throw new Exception('Error experience not found');
        }
        // Check permissions
        if (!local_dta_check_permissions($experience, $USER)) {
            throw new Exception('Error permissions');
        }
        return $DB->delete_records(self::$table, ['id' => $experience->id]);
    }

    /**
     * check if experience exists
     * 
     * @param int $id
     * @return bool
     */
    public static function check_experience($id)
    {
        global $DB;
        return $DB->record_exists(self::$table, ['id' => $id]);
    }

    /**
     * Get my experience url
     */
    public function get_url()
    {
        return new \moodle_url('/local/dta/pages/experiences/view.php', ['id' => $this->id]);
    }

    // Get the three latest experiences
    public static function get_latest_experiences($includePrivates = true)
    {
        global $DB;
        $latestExperiences = array_values(
            $DB->get_records(
                self::$table, 
                $includePrivates ? null : ['visible' => 1],
                'date DESC',
                '*',
                0,
                3
            )
        );
        $latestExperiences = self::get_extra_fields($latestExperiences);
        return $latestExperiences;
    }

    /**
     * Close the experience
     */
    public static function close_experience($experience)
    {
        global $DB, $USER;
        if (!self::check_experience($experience->id)) {
            throw new Exception('Error experience not found');
        }
        // Check permissions
        if (!local_dta_check_permissions($experience, $USER)) {
            throw new Exception('Error permissions');
        }
        $experience->status = 1;
        $record = new \stdClass();
        $record->id = $experience->id;
        $record->status = 1;
        $DB->update_record(self::$table, $record);
        return new Experience($record);
    }

}

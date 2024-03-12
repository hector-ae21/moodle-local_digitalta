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
     * Get my experiences
     */

    public static function get_my_experiences($userid)
    {
        global $DB;
        $experiences = array_values($DB->get_records(self::$table, ['userid' => $userid]));
        $experiences = self::get_extra_fields($experiences);
        foreach ($experiences as $experience) {
            $experience->description = self::trimHtml($experience->description, 300);
        }
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
        foreach ($experiences as $experience) {
            $experience->description = self::trimHtml($experience->description, 300);
        }
        return $experiences;
    }

    /**
     * Get extra fields for experiences
     * 
     * @param array $experiences
     * @return array
     */
    private static function get_extra_fields($experiences)
    {
        global $PAGE;
        foreach ($experiences as $experience) {
            $user = get_complete_user_data("id", $experience->userid);
            $picture = new \user_picture($user);
            $picture->size = 101;
            $experience->user = [
                'id' => $user->id,
                'name' => $user->firstname . " " . $user->lastname,
                'email' => $user->email,
                'imageurl' => $picture->get_url($PAGE)->__toString(),
                'profileurl' => new \moodle_url('/user/profile.php', ['id' => $user->id])
            ];
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
        return new \moodle_url('/local/dta/pages/myexperience/view.php', ['id' => $this->id]);
    }

    private static function trimHtml($html, $maxLength) {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
    
        $length = 0;
        $nodesToKeep = [];
    
        foreach ($xpath->query('//text()') as $textNode) {
            if ($length + strlen($textNode->nodeValue) > $maxLength) {
                // Calculate remaining length
                $remainingLength = $maxLength - $length;
                $textNode->nodeValue = substr($textNode->nodeValue, 0, $remainingLength);
                $nodesToKeep[] = $textNode->parentNode;
                $textNode->parentNode->appendChild(new \DOMText('...'));
                break;
            } else {
                $length += strlen($textNode->nodeValue);
                $nodesToKeep[] = $textNode->parentNode;
            }
        }
    
        foreach ($xpath->query('//*') as $node) {
            if (!in_array($node, $nodesToKeep, true) && $node->parentNode) {
                $node->parentNode->removeChild($node);
            }
        }
    
        return $dom->saveHTML();
    }
}

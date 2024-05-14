<?php

/**
 * Mentors class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This file controls DTA resource as an instance of the repository and digital_resource table in the database
// NOT TO BE CONFUSED WITH THE FILEMANAGER HANDLER OR MOODLE FILE API

namespace local_dta;

class Mentor
{
    private $id;
    private static $table = 'user';

    private static $mentor_request_table = 'digital_mentoring_request';

    /**
     * Get a mentor by its ID.
     * 
     * @param $id int The ID of the mentor.
     * 
     * @return object The mentor object.
     */
    public static function get_mentor(int $id): object
    {
        global $DB;
        return $DB->get_record(self::$table, ['id' => $id]);
    }

    /**
     * Get all mentors.
     * 
     * @return array The mentors.
     */
    public static function get_all_mentors(): array
    {
        global $DB;
        return array_values($DB->get_records(self::$table));
    }
    /**
     * Send a mentor request.
     * 
     * @param $mentorid int The ID of the mentor.
     * @param $userid int The ID of the user.
     * @param $experienceid int The ID of the experience.
     * 
     * @return bool|object The mentor request object.
     */
    public static function send_mentor_request(int $mentorid, int $experienceid): bool|object
    {
        global $DB;
        $data = new \stdClass();
        $data->mentorid = $mentorid;
        $data->experienceid = $experienceid;
        $data->status = 0;
        $data->timecreated = time();
        if (!$data->id = $DB->insert_record(self::$mentor_request_table, $data)) {
            return false;
        }
        return $data;
    }

    /**
     * Get mentor requests by mentor ID.
     * 
     * @param $mentorid int The ID of the mentor.
     * 
     * @return array The mentor requests.
     */
    public static function get_mentor_requests_by_mentor(int $mentorid): array
    {
        global $DB;
        return $DB->get_records(self::$mentor_request_table, ['mentorid' => $mentorid]);
    }

    /**
     * Get mentor requests by experience ID.
     * 
     * @param $experienceid int The ID of the experience.
     * 
     * @return array The mentor requests.
     */
    public static function get_mentor_requests_by_experience(int $experienceid): array
    {
        global $DB;
        return $DB->get_records(self::$mentor_request_table, ['experienceid' => $experienceid]);
    }

    /**
     * Change the status of a mentor request.
     * 
     * @param $id int The ID of the mentor request.
     * @param $status int The status of the mentor request.
     * 
     * @return bool True if the status was changed, false otherwise.
     */
    public static function change_mentor_request_status(int $id, int $status): bool
    {
        global $DB;
        $data = new \stdClass();
        $data->id = $id;
        $data->status = $status;
        return $DB->update_record(self::$mentor_request_table, $data);
    }

    /**
     * Get a chunk of mentors.
     * 
     * @param $numLoaded int The number of mentors already loaded.
     * @param $numToLoad int The number of mentors to load.
     * 
     * @return object The mentors.
     * 
     */
    public static function get_mentor_chunk(int $numLoaded, int $numToLoad): array
    {
        global $DB;
        return $DB->get_records_sql("SELECT * FROM {" . self::$table . "} LIMIT $numToLoad OFFSET $numLoaded");
    }

    /**
     * Verify if a mentor request exists.
     * 
     * @param $mentorid int The ID of the mentor.
     * @param $experienceid int The ID of the experience.
     */
    public static function is_enrolled_mentor_in_course(int $mentorid, int $experienceid): bool
    {
        global $DB;
        return $DB->record_exists(self::$mentor_request_table, ['mentorid' => $mentorid, 'experienceid' => $experienceid]);
    }

}

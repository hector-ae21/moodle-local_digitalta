<?php

/**
 * cases tags class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 

namespace local_dta;

class tutor_disponibility{

    private $id;
    private $tutor_id;
    private $day;
    private $hour;
    private $timecreated;


    public static function get_tutor_hours_disponibility($tutor_id){
        global $DB;
        $sql = "SELECT * FROM {local_dta_tutor_disponibility} WHERE tutor_id = ?";
        $tutor_disponibility = $DB->get_records_sql($sql, [$tutor_id]);

        $hours = [];
        foreach($tutor_disponibility as $disponibility){
            array_push($hours, $disponibility->hour);
        }
        return $hours;
    }

}

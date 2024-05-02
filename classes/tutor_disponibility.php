<?php

/**
 * cases tutor_disponibility class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



namespace local_dta;

class tutor_disponibility
{
    
    public static function create($userid, $day, $timefrom, $timeto)
    {
        global $DB;
        $tutor_disponibility = new \stdClass();
        $tutor_disponibility->userid = $userid;
        $tutor_disponibility->day = $day;
        $tutor_disponibility->timefrom = $timefrom;
        $tutor_disponibility->timeto = $timeto;
        $tutor_disponibility->timecreated = time();

        if ($tutor_disponibility->timefrom > $tutor_disponibility->timeto) {
            throw new \Exception('The timefrom must be less than timeto');
        }

        $sql = "SELECT * FROM {digital_tutor_disponibility} WHERE userid = ? AND day = ? AND ((timefrom <= ? AND timeto >= ?) OR (timefrom <= ? AND timeto >= ?))";
        $tutor_disponibility_exist = $DB->get_records_sql($sql, [$userid, $day, $timefrom, $timefrom, $timeto, $timeto]);
        if ($tutor_disponibility_exist) {
            throw new \Exception('The tutor already has a disponibility in the same day and hours');
        };
        $tutor_disponibility->id = $DB->insert_record('digital_tutor_disponibility', $tutor_disponibility);
        return $tutor_disponibility;
    }

    public static function update($id, $userid, $day, $timefrom, $timeto)
    {
        global $DB;
        $tutor_disponibility = new \stdClass();
        $tutor_disponibility->id = $id;
        $tutor_disponibility->userid = $userid;
        $tutor_disponibility->day = $day;
        $tutor_disponibility->timefrom = $timefrom;
        $tutor_disponibility->timeto = $timeto;

        if ($tutor_disponibility->timefrom > $tutor_disponibility->timeto) {
            throw new \Exception('The timefrom must be less than timeto');
        }

        $sql = "SELECT * FROM {digital_tutor_disponibility} WHERE id != ? AND iserid = ? AND day = ? AND ((timefrom <= ? AND timeto >= ?) OR (timefrom <= ? AND timeto >= ?))";
        $tutor_disponibility_exist = $DB->get_records_sql($sql, [$id, $userid, $day, $timefrom, $timefrom, $timeto, $timeto]);
        if ($tutor_disponibility_exist) {
            throw new \Exception('The tutor already has a disponibility in the same day and hours');
        };
        $DB->update_record('digital_tutor_disponibility', $tutor_disponibility);
    }


    public static function get_hours_disponibility_by_day($tutor_id, $day = "ALL")
    {
        global $DB;
        $sql = "SELECT * FROM {digital_tutor_disponibility} WHERE tutor_id = ?";
        $tutor_disponibility = $DB->get_records_sql($sql, [$tutor_id]);

        $hours = [];
        foreach ($tutor_disponibility as $disponibility) {
            if ($day != "ALL" && $disponibility->day != $day) {
                continue;
            };
            $hours[$disponibility->day] = [
                "timefrom" => $disponibility->timefrom,
                "timeto" => $disponibility->timeto
            ];
        }
        return $hours;
    }

    public static function delete($id)
    {
        global $DB;
        $DB->delete_records('digital_tutor_disponibility', ['id' => $id]);
        return true;
    }
}

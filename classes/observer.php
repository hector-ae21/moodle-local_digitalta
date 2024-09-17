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
 * Event observers
 *
 * @package    local_digitalta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_digitalta_observer
{
    public static function user_created(\core\event\user_created $event)
    {
        global $DB;
        $user = $event->get_record_snapshot('user', $event->objectid);
        profile_load_data($user);
        if (!property_exists($user, 'profile_field_digitalta_role')) {
            return;
        }
        switch ($user->profile_field_digitalta_role) {
            case 'Student':
            case 'Newly Qualified Teacher':
                $role = $DB->get_record('role', ['shortname' => 'digitaltastudent']);
                role_assign($role->id, $user->id, context_system::instance()->id);
                break;
            case 'Tutor':
            case 'Mentor':
                $role = $DB->get_record('role', ['shortname' => 'digitaltatutor']);
                role_assign($role->id, $user->id, context_system::instance()->id);
                break;
        }
    }
}

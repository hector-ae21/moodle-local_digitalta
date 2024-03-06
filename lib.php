<?php

/**
 *  lib file for the local experiences plugin
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../../config.php');

/**
 * check permissions to delete or edit an experience owner or admin
 * 
 */
function local_dta_check_permissions($experience, $user)
{
    if ($user->id == $experience->user || is_siteadmin($USER)) {
        return true;
    }
    return false;
}
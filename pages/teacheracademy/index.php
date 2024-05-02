<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . './../../classes/experience.php');
require_once(__DIR__ . './../../classes/ourcases.php');
require_once(__DIR__ . './../../classes/reactions.php');
require_once(__DIR__ . './../../classes/context.php');
require_once(__DIR__ . './../../classes/themes.php');
require_once(__DIR__ . './../../classes/utils/string_utils.php');
require_once(__DIR__ . './../../classes/utils/filter_utils.php');

require_login();

use local_dta\Experience;
use local_dta\OurCases;
use local_dta\Reaction;
use local_dta\Context;
use local_dta\Themes;
use local_dta\utils\StringUtils;
use local_dta\utils\filter_utils;

global $CFG, $PAGE, $OUTPUT;

$strings = get_strings(['teacheracademy_header', 'teacheracademy_title'], "local_dta");

$PAGE->set_url(new moodle_url('/local/dta/pages/teacheracademy/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title($strings->teacheracademy_title);
$PAGE->requires->js_call_amd('local_dta/myexperience/reactions', 'init');

echo $OUTPUT->header();

// Get themes
$themes = Themes::get_themes();
$themes = array_values($themes);
$themes = array_slice($themes, 0, 8, true);
$themes = array_map(function($key, $theme) {
    global $OUTPUT;
    $theme->picture = $OUTPUT->image_url('dta-theme' . ($key + 1) . '-colored', 'local_dta');
    return $theme;
}, array_keys($themes), $themes);

// Get experiences
$featuredExperiences = Experience::get_extra_fields(Reaction::get_most_liked_experience(5));
$featuredExperiences = array_map(function($experience) {
    return $experience->id;
}, $featuredExperiences);
$experiences = Experience::get_latest_experiences(9, false);
$experiences = array_map(function($experience) use ($featuredExperiences) {
    $experience->description = StringUtils::truncateHtmlText($experience->description);
    $experience->featured = (in_array($experience->id, $featuredExperiences)) ? true : false;
    return $experience;
}, $experiences);
array_multisort(
    array_column($experiences, 'featured'), SORT_DESC,
    array_column($experiences, 'timecreated'), SORT_DESC,
    $experiences);

// Get cases
$cases = array_values(OurCases::get_active_cases());
$cases = array_map(function($case) {
    $caseText = OurCases::get_sections_text($case->id, true);
    $case->casetext = array_values($caseText)[0];
    $case->casetext->description = str_replace("<br>",
        " ",
        StringUtils::truncateHtmlText($case->casetext->description, 300));
    return $case;
}, $cases);

// Get user data
$user = get_complete_user_data("id", $USER->id);
$picture = new user_picture($user);
$picture->size = 101;
$user->imageurl = $picture->get_url($PAGE)->__toString();

// Template context
$templateContext = [
    "user" => $user,
    "themepixurl" => $CFG->wwwroot . "/theme/dta/pix/",
    "experiences" => [
        "data" => $experiences,
        "addurl" => $CFG->wwwroot . "/local/dta/pages/experiences/manage.php",
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/experiences/view.php?id=',
        "allurl" => $CFG->wwwroot . "/local/dta/pages/experiences/dashboard.php",
    ],
    "themes" => [
        "data" => $themes,
        "viewurl" => $CFG->wwwroot . '/local/dta/pages/tags/view.php?type=theme&id=',
        "allurl" => $CFG->wwwroot . "/local/dta/pages/tags/dashboard.php"
    ],
    "cases" => [
        "data" => array_slice($cases, 0, 4),
        "viewurl" => $CFG->wwwroot . "/local/dta/pages/cases/view.php?id=",
        "allurl" => $CFG->wwwroot . "/local/dta/pages/cases/repository.php"
    ]
];

$templateContext = filter_utils::apply_filter_to_template_object($templateContext);

echo $OUTPUT->render_from_template('local_dta/teacheracademy/dashboard', $templateContext);

echo $OUTPUT->footer();

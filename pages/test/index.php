<?php

/**
 * community page
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once (__DIR__ . '/../../../../config.php');

require_login();



global $CFG, $PAGE, $OUTPUT;


// Setea el título de la página
$PAGE->set_url(new moodle_url('/local/dta/pages/test/index.php'));
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();


$user_list = [
  [
    'profile_img' => 'https://www.w3schools.com/howto/img_avatar.png',
    'name' => 'Elias',
    'university' => 'UNEATLANTICO',
  ],
  [
    'profile_img' => 'https://www.w3schools.com/howto/img_avatar2.png',
    'name' => 'Maria',
    'university' => 'FUNIBER',
  ],
];

$data = '{
  "days": [
    {
      "day": "Monday",
      "times": [
        {"start": "09:00", "end": "10:00"},
        {"start": "10:30", "end": "12:00"},
        {"start": "16:00", "end": "17:00"}
      ]
    },
    {
      "day": "Tuesday",
      "times": [
        {"start": "09:00", "end": "14:00"}
      ]
    },
    {
      "day": "Wednesday",
      "times": [
        {"start": "09:00", "end": "14:00"}
      ]
    },
    {
      "day": "Thursday",
      "times": [
        {"start": "09:00", "end": "14:00"}
      ]
    }
  ]
}
';

$data = json_decode($data);


echo $OUTPUT->render_from_template('local_dta/test/index', $data);

echo $OUTPUT->footer();

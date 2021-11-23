<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of meetzi
 *
 * @package     mod_meetzi
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib/moodlelib.php');
require_once(dirname(__FILE__).'/lib.php');
global $USER;
$PAGE->set_url($CFG->wwwroot.'/mod/meetzi/session.php');

$config = get_config('meetzi');
$institution = required_param('meetzi_institution', PARAM_TEXT);
$courseid = required_param('courseid', PARAM_INT);
$meetzihostname = required_param('meetzi_hostname', PARAM_TEXT);
$meetziroomname = required_param('meetzi_roomname', PARAM_TEXT);
$meetzipassword = required_param('meetzi_password', PARAM_TEXT);
$originlocation = required_param('originLocation', PARAM_TEXT);
$username = $USER->username;
$context = context_course::instance($courseid);
$moderation = false;
if (has_capability('mod/meetzi:moderate', $context)) {
    $moderation = true;
}

require_login($courseid);

$PAGE->set_title($meetziroomname);
$PAGE->set_heading($meetziroomname);
echo $OUTPUT->header();

if ($moderation) {
    echo "<iframe id='meetzi_frame' allow='camera; microphone; display-capture' allowfullscreen
    src='https://".$meetzihostname."?t,".$institution."_".$meetziroomname.",".$meetzipassword.",".$username.
    "&originLocation=".$originlocation."' title='meetzi'></iframe>";
} else {
    echo "<iframe id='meetzi_frame' allow='camera; microphone; display-capture' allowfullscreen
    src='https://".$meetzihostname."?v,".$institution."_".$meetziroomname.",".$meetzipassword.",".$username.
    "&originLocation=".$originlocation."' title='meetzi'></iframe>";
}

echo $OUTPUT->footer();
?>
<style>#page-footer{display:none;}</style>
<?php

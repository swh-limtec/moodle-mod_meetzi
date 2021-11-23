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
 * Prints an instance of mod_meetzi.
 *
 * @package     mod_meetzi
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

global $USER;
global $COURSE;
global $DB;

$id = optional_param('id', 0, PARAM_INT);
$n = optional_param('l', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('meetzi', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $meetzi = $DB->get_record('meetzi', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $meetzi  = $DB->get_record('meetzi', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $meetzi->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('meetzi', $meetzi->id, $course->id, false, MUST_EXIST);
} else {
    moodle_exception('missingparam');
}

require_login($course, true, $cm);

$courseid = $course->id;
$context = context_course::instance($courseid);

$roles = get_user_roles($context, $USER->id);
$config = get_config('meetzi');
$institution = $meetzi->institution;
$instance = $meetzi->instance;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://".$meetzi->loginhostname."/api/index.php?type=checkifroomexists&instance="
.$instance."&room=".$meetzi->roomname."&school=".$institution);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$jsonresult = json_decode($result, true);

if ($jsonresult['returnmsg']['status'] == "error") {
    \core\notification::warning('<div class="roomwarning" style="margin: 5px; font-size: large; font-weight: bold;">'
    .get_string('roomnotfound', 'meetzi').'</div>');
}


$PAGE->set_url('/mod/meetzi/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($meetzi->name));
$PAGE->set_heading(format_string($course->fullname));
echo $OUTPUT->header();
echo $OUTPUT->heading($meetzi->name);

$urlparams = array('courseid' => $course->id, 'meetzi_hostname' => $meetzi->meetzihostname, 'meetzi_roomname' => $meetzi->roomname,
'meetzi_password' => $meetzi->institutionpassword,
'meetzi_institution' => $meetzi->institution, 'originLocation' => urlencode($PAGE->url));

echo $OUTPUT->single_button(new moodle_url('/mod/meetzi/session.php', $urlparams), get_string('access', 'meetzi'), 'post');

echo $OUTPUT->footer();



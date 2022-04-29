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
 * Internal library of functions for module meetzi
 *
 * @package     mod_meetzi
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Creates Meetzi room outside of moodle.
 *
 * @param mod_meetzi_mod_form $mform
 * @return string $pw set password of created moodle room for joining
 */
function meetzi_create_room($mform) {
    global $DB, $CFG, $USER, $COURSE;
    $config = get_config('meetzi');

    require_once($CFG->dirroot.'/config.php');
    require_once($CFG->libdir.'/filelib.php');
    require_login();

    $inputurl = $config->loginhostname;
    $institutionpw = $config->institutionpw;
    $institution = $config->institution;
    $instance = $config->instance;

    $roomname = $mform->get_data()->{'roomname'};
    $username = $USER->username;

    $curl = new curl();

    $post = [
    'type' => 'createroom',
    'room' => $roomname,
    'user'   => $username,
    'teacherpassword' => $institutionpw,
    'microphoneEnabled' => 1,
    'cameraEnabled' => 1,
    'loginhostname' => $inputurl,
    'school' => $institution,
    'instance' => $instance,
    ];

    $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

    $curlurl = "https://".$inputurl."/api/index.php";

    $result = $curl->post($curlurl, $post, $options);
    $jsonresult = json_decode($result, true);

    if (isset($jsonresult['config']['roomPin'])) {
        $pin = $jsonresult['config']['roomPin'];
    } else {
        $pin = '';
    }

    if (isset($jsonresult['config']['password'])) {
        $pw = $jsonresult['config']['password'];
    } else {
        \core\notification::warning('<div class="roomwarning">'.get_string('roomexists', 'meetzi').'</div>');
        return 0;
    }

    $curlurl2 = "https://".$inputurl."/api/index.php?type=teacherjoinroom&room=".$roomname."&user
    =".$username."&password=".$pw."&school=".$institution."&instance=".$instance."&teacherpassword=".$institutionpw;

    $result2 = $curl->get($curlurl2, null, $options);

    return array($pw, $pin);
}





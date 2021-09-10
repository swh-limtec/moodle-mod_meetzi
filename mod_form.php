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
 * The main meetzi configuration form
 *
 * @package     mod_meetzi
 * @copyright   2021 Limtec <info@limtec.de
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Meetzi settings form.
 *
 * @package     mod_meetzi
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_meetzi_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $COURSE;
        $config = get_config('meetzi');
        $mform = $this->_form;
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'roomname', get_string('meetziroomname', 'meetzi'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('roomname', PARAM_TEXT);
        } else {
            $mform->setType('roomname', PARAM_CLEANHTML);
        }
        $defaultname = $CFG->wwwroot;
        $defaultname = trim($defaultname, "https://");
        $defaultname = trim($defaultname, "http://");
        $defaultname = str_replace('.', '-', $defaultname);
        $defaultname = $defaultname."-".$COURSE->fullname;
        $mform->setDefault('roomname', $defaultname);
        $mform->addRule('roomname', null, 'required', null, 'client');
        $mform->addRule('roomname', get_string('warningalphanumeric', 'meetzi'), 'regex', '/^[a-zA-Z0-9-_]+$/', 'client');
        $mform->addRule('roomname', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('roomname', 'modulename', 'meetzi');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Validates form and checks if chosen roomname already exists within meetzi instance
     *
     * @param mod_meetzi_mod_form $data
     * @return array $errors validation errors like room already exists within meetzi instance
     */
    public function validation($data) {
        $errors = array();
        $config = get_config('meetzi');
        $hostname = $config->loginhostname;
        $institution = $config->institution;
        $institutionpw = $config->institutionpw;
        $instance = $config->instance;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://".$hostname."/api/index.php?type=checkifroomexists&instance=".
        $instance."&room=".$data['roomname']."&school=".$institution);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $jsonresult = json_decode($result, true);

        if (isset($jsonresult['returnmsg']['status'])) {
            if ($jsonresult['returnmsg']['status'] == "success") {
                $errors['roomexists'] = 'Error - room already exists';
                \core\notification::warning('<div class="roomwarning">'.get_string('roomexists', 'meetzi').'</div>');
            }
        }
        curl_close($ch);

        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "https://".$hostname."/api/index.php?type=checkteacherpassword&instance=".$instance.
        "&teacherpassword=".$institutionpw."&school=".$institution);
        curl_setopt($ch2, CURLOPT_POST, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $result2 = curl_exec($ch2);
        $jsonresult2 = json_decode($result2, true);

        if (!isset($jsonresult2['roomauthenticated'])) {
                $errors['wrongconfig'] = 'Error - Meetzi-Instance configuration is wrong';
                \core\notification::warning('<div class="roomwarning">'.get_string('wrongconfig', 'meetzi').'</div>');
        }

        if (isset($jsonresult2['roomauthenticated'])) {
            if ($jsonresult2['roomauthenticated'] == false) {
                $errors['wrongconfig'] = 'Error - Meetzi-Instance configuration is wrong';
                \core\notification::warning('<div class="roomwarning">'.get_string('wrongconfig', 'meetzi').'</div>');
            }
        }
        curl_close($ch2);

        return $errors;
    }
}

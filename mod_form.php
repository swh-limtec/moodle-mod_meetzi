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

global $CFG;
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
        $coursename = $COURSE->fullname;
        $coursename = preg_replace('/[^A-Za-z0-9\-]/', '', $coursename);
        $defaultname = $defaultname."-".$coursename;
        $mform->setDefault('roomname', $defaultname);
        $mform->addRule('roomname', null, 'required', null, 'client');
        $mform->addRule('roomname', get_string('warningalphanumeric', 'meetzi'), 'regex', '/^[a-zA-Z0-9-]+$/', 'client');
        $mform->addRule('roomname', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('roomname', 'modulename', 'meetzi');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Validates form and checks if chosen roomname already exists within meetzi instance
     *
     * @param mod_meetzi_mod_form $data
     * @param mod_meetzi_mod_form $files
     * @return array $errors validation errors like room already exists within meetzi instance
     */
    public function validation($data, $files) {

        global $CFG;
        require_once($CFG->libdir.'/filelib.php');

        $errors = array();
        $config = get_config('meetzi');
        $hostname = $config->loginhostname;
        $institution = $config->institution;
        $institutionpw = $config->institutionpw;
        $instance = $config->instance;

        $curlurl = "https://".$hostname."/api/index.php?type=checkifroomexists&instance=".
        $instance."&room=".$data['roomname']."&school=".$institution;

        $options = array(
            'RETURNTRANSFER' => 1,
            'HEADER' => 0,
            'FAILONERROR' => 1,
        );

        $curl = new curl();
        $result = $curl->get($curlurl, null, $options);

        $jsonresult = json_decode($result, true);

        echo '<script> alert("'.$result.'")</script>';

        if (isset($jsonresult['returnmsg']['status'])) {
            if ($jsonresult['returnmsg']['status'] == "success") {
                $errors['roomexists'] = 'Error - room already exists';
                \core\notification::warning('<div class="roomwarning">'.get_string('roomexists', 'meetzi').'</div>');
            }
        }

        $curlurl2 = "https://".$hostname."/api/index.php?type=checkteacherpassword&instance=".$instance.
        "&teacherpassword=".$institutionpw."&school=".$institution;

        $result2 = $curl->get($curlurl2, null, $options);
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

        return $errors;
    }
}

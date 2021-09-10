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
 * Plugin administration pages are defined here.
 *
 * @package     mod_meetzi
 * @category    admin
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/mod/meetzi/locallib.php');


if ($hassiteconfig) {
    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_heading('meetzidomainsettings',
        get_string('meetziconfig', 'meetzi'), get_string('meetziconfigexplain', 'meetzi')));

        $settinghostname = new admin_setting_configtext('meetzi/loginhostname',
        get_string('loginhostname', 'meetzi'), get_string('loginhostnameexplain', 'meetzi'), "klassenzimmer.meetzi.de");
        $settings->add($settinghostname);

        $settinginstitution = new admin_setting_configtext('meetzi/institution',
        get_string('schoolname', 'meetzi'), get_string('schoolexplain', 'meetzi'), "Schule");
        $settings->add($settinginstitution);

        $settinginstitutionpw = new admin_setting_configtext('meetzi/institutionpw',
        get_string('teacherpw', 'meetzi'), get_string('teacherpwexplain', 'meetzi'), "XHSGC7a");
        $settings->add($settinginstitutionpw);

        $settinginstance = new admin_setting_configtext('meetzi/instance',
        get_string('instancename', 'meetzi'), get_string('instanceexplain', 'meetzi'), "c61");
        $settings->add($settinginstance);
    }
}






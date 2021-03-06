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
 * Library of interface functions and constants.
 *
 * @package     mod_meetzi
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function meetzi_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_meetzi into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $data An object from the form.
 * @param mod_meetzi_mod_form $mform The form.
 * @return int $id The id of the newly inserted record.
 */
function meetzi_add_instance($data, $mform = null) {
    global $CFG, $DB;
    require_once($CFG->dirroot.'/mod/meetzi/locallib.php');
    $config = get_config('meetzi');
    $data->name = $mform->get_data()->{'roomname'};
    $data->roomname = $mform->get_data()->{'roomname'};
    $meetzidata = meetzi_create_room($mform);
    $data->institutionpassword = $meetzidata[0];

    $data->meetzihostname = $config->loginhostname;
    $data->institution = $config->institution;
    $data->instance = $config->instance;

    $data->timecreated = time();
    $id = $DB->insert_record('meetzi', $data);
    if ($meetzidata[1] != '') {
        \core\notification::warning('<div class="roomwarning">'.get_string('roompin_01', 'meetzi').
        ''. $meetzidata[1] .''. get_string('roompin_02', 'meetzi').'</div>');
    }
    return $id;
}



/**
 * Updates an instance of the mod_meetzi in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_meetzi_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function meetzi_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('meetzi', $moduleinstance);
}

/**
 * Removes an instance of the mod_meetzi from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function meetzi_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('meetzi', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('meetzi', array('id' => $id));

    return true;
}

/**
 * Extends the global navigation tree by adding mod_meetzi nodes if there is a relevant content.
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $meetzinode An object representing the navigation tree node.
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function meetzi_extend_navigation($meetzinode, $course, $module, $cm) {
}

/**
 * Extends the settings navigation with the mod_meetzi settings.
 *
 * This function is called when the context for the page is a mod_meetzi module.
 * This is not called by AJAX so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@see settings_navigation}
 * @param navigation_node $meetzinode {@see navigation_node}
 */
function meetzi_extend_settings_navigation($settingsnav, $meetzinode = null) {
}

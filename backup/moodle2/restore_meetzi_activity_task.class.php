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
 * Provides the restore activity task class
 *
 * @package   mod_meetzi
 * @category  backup
 * @copyright 2021 Limtec <info@limtec.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
/**
 * meetzi restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */

require_once($CFG->dirroot . '/mod/meetzi/backup/moodle2/restore_meetzi_stepslib.php');

/**
 * Restore task for the meetzi activity module
 *
 * Provides all the settings and steps to perform complete restore of the activity.
 *
 * @package   mod_meetzi
 * @category  backup
 * @copyright 2021 Limtec <info@limtec.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_meetzi_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        $this->add_step(new restore_meetzi_activity_structure_step('meetzi_structure', 'meetzi.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    public static function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('meetzi', array('intro'), 'meetzi');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    public static function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('MEETZIVIEWBYID', '/mod/meetzi/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('MEETZIINDEX', '/mod/meetzi/index.php?id=$1', 'course');

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * when restoring meetzi logs. It must return one array
     */
    public static function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('meetzi', 'add', 'view.php?id={course_module}', '{meetzi}');
        $rules[] = new restore_log_rule('meetzi', 'update', 'view.php?id={course_module}', '{meetzi}');
        $rules[] = new restore_log_rule('meetzi', 'view', 'view.php?id={course_module}', '{meetzi}');
        $rules[] = new restore_log_rule('meetzi', 'choose', 'view.php?id={course_module}', '{meetzi}');
        $rules[] = new restore_log_rule('meetzi', 'choose again', 'view.php?id={course_module}', '{meetzi}');
        $rules[] = new restore_log_rule('meetzi', 'report', 'report.php?id={course_module}', '{meetzi}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * when restoring course logs. It must return one array
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    public static function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('meetzi', 'view all', 'index?id={course}', null,
                                        null, null, 'index.php?id={course}');
        $rules[] = new restore_log_rule('meetzi', 'view all', 'index.php?id={course}', null);

        return $rules;
    }

}
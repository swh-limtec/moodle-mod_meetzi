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

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete meetzi structure for backup
 *
 * @package   mod_meetzi
 * @category  backup
 * @copyright 2021 Limtec <info@limtec.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_meetzi_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {
        $meetzi = new backup_nested_element('meetzi', array('id'), array(
            'name', 'timecreated', 'meetzihostname', 'intro', 'introformat',
            'institutionpassword', 'roomname', 'institution',
            'instance', 'usermodified'));

        $meetzi->set_source_table('meetzi', array('id' => backup::VAR_ACTIVITYID));

        $meetzi->annotate_files('mod_meetzi', 'intro', null, $contextid = null);

        return $this->prepare_activity_structure($meetzi);
    }
}
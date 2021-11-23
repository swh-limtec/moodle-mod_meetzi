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

namespace mod_meetzi\privacy;

use core_privacy\local\metadata\collection;
/**
 * Implementation of the privacy subsystem plugin provider for the meetzi module.
 *
 * @copyright   2021 Limtec <info@limtec.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\data_provider {
    /**
     * Returns meta data about this system.
     *
     * @param   collection     $collection The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {

        $collection->add_external_location_link('meetzi_extern', [
            'username' => 'privacy:metadata:meetzi_extern:username',
        ],  'privacy:metadata:meetzi_extern'
        );
        return $collection;
    }
}
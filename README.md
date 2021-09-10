# Meetzi moodle plugin #

With this plugin you can create **meetzi conference rooms** for your moodle course.

Meetzi is a platform for online meeting rooms without registration. It allows collaborative work
through shared documents and views in addition to audio-/video-conferences.

For your institution there is also the option to use a custom meetziPro instance
with your own domain which allows for password protected rooms as well as making the room fit your corporate design.

This plugin enables you to create your conference room directly in moodle and use it 
together with the members of your moodle course.

Additional information about meetzi can be found here: [https://meetzi.de/](https://meetzi.de/)

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/meetzi

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2021 Limtec <info@limtec.de>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.

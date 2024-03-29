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
 * Quick Course Login
 *
 * This module has been created to provide a quick and easy way of loggin into a course
 *
 * @package    local
 * @subpackage quickcourselogin
 * @copyright  2013 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$plugin->version  = 2013071600;
$plugin->requires = 2011120500;  // Requires this Moodle 2.2 version or newer
$plugin->release = '1.0 (Build: 2013071500)';
$plugin->maturity = MATURITY_BETA;             // this version's maturity level
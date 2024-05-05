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
 * Library of functions and constants for the local_dta plugin.
 *
 * @package   local_dta
 * @copyright Salvador Banderas Rovira
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

const LOCAL_DTA_COMPONENTS = [
    "experience",
    "case",
    "resource",
    "user"
];

// RESOURCES AND CASE is duplicated as a component and as a modifier bc it can be both
// we need to refactor this in the future, but not now it's fine

const LOCAL_DTA_MODIFIERS = [
    "theme",
    "tag",
    "resource",
    "case"
];

const LOCAL_DTA_THEMES = [
    "Digital Technology",
    "Classroom Management",
    "Communication and Relationship Building",
    "Diversity and Inclusion",
    "Professional Collaboration and Development",
    "School Culture",
    "Curriculum Planning and Development",
    "Others"
];

const LOCAL_DTA_RESOURCE_TYPES = [
    "Other",
    "Book",
    "Chart",
    "Comic",
    "Diary",
    "Field Notes",
    "Image",
    "Interview",
    "Journal",
    "Magazine",
    "Map",
    "Music",
    "Newspaper",
    "Photograph",
    "Podcast",
    "Report",
    "Video",
    "Website"
];

const LOCAL_DTA_RESOURCE_FORMATS = [
    "None",
    "Link",
    "Image",
    "Video",
    "Document"
];

/**
 * Get the translation of an element.
 *
 * @param string $element The element to translate.
 * @param string $string The string to translate.
 * @return string The translated string.
 *
 * @todo This function should be moved to a helper class.
 * @todo This function should use the values from the database.
 */
function local_dta_get_element_translation(string $element, string $string) : string {
    switch ($element) {
        case "component":
            $elements = LOCAL_DTA_COMPONENTS;
            break;
        case "modifier":
            $elements = LOCAL_DTA_MODIFIERS;
            break;
        case "theme":
            $elements = LOCAL_DTA_THEMES;
            break;
        case "resource_type":
            $elements = LOCAL_DTA_RESOURCE_TYPES;
            break;
        case "resource_format":
            $elements = LOCAL_DTA_RESOURCE_FORMATS;
            break;
        default:
            return $string;
    }
    if (!in_array($string, $elements)) {
        return $string;
    }
    return get_string($element . ":" . strtolower(str_replace(" ", "_", $string)), 'local_dta');
}

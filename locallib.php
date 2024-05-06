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

const LOCAL_DTA_MODIFIERS = [
    "theme",
    "tag"
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
    "Study Case",
    "Video",
    "Website",
];

const LOCAL_DTA_RESOURCE_FORMATS = [
    "None",
    "Link",
    "Image",
    "Video",
    "Document"
];

const LOCAL_DTA_SECTION_GROUPS = [
    "General",
    "What?",
    "So What?",
    "Now What?",
    "Extra"
];

const LOCAL_DTA_SECTION_TYPES = [
    "Text"
];

const LOCAL_DTA_LANGUAGES = $codes = [
    'ab' => 'Abkhazian',
    'aa' => 'Afar',
    'af' => 'Afrikaans',
    'ak' => 'Akan',
    'sq' => 'Albanian',
    'am' => 'Amharic',
    'ar' => 'Arabic',
    'an' => 'Aragonese',
    'hy' => 'Armenian',
    'as' => 'Assamese',
    'av' => 'Avaric',
    'ae' => 'Avestan',
    'ay' => 'Aymara',
    'az' => 'Azerbaijani',
    'bm' => 'Bambara',
    'ba' => 'Bashkir',
    'eu' => 'Basque',
    'be' => 'Belarusian',
    'bn' => 'Bengali',
    'bh' => 'Bihari languages',
    'bi' => 'Bislama',
    'bs' => 'Bosnian',
    'br' => 'Breton',
    'bg' => 'Bulgarian',
    'my' => 'Burmese',
    'ca' => 'Catalan, Valencian',
    'km' => 'Central Khmer',
    'ch' => 'Chamorro',
    'ce' => 'Chechen',
    'ny' => 'Chichewa, Chewa, Nyanja',
    'zh' => 'Chinese',
    'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
    'cv' => 'Chuvash',
    'kw' => 'Cornish',
    'co' => 'Corsican',
    'cr' => 'Cree',
    'hr' => 'Croatian',
    'cs' => 'Czech',
    'da' => 'Danish',
    'dv' => 'Divehi, Dhivehi, Maldivian',
    'nl' => 'Dutch, Flemish',
    'dz' => 'Dzongkha',
    'en' => 'English',
    'eo' => 'Esperanto',
    'et' => 'Estonian',
    'ee' => 'Ewe',
    'fo' => 'Faroese',
    'fj' => 'Fijian',
    'fi' => 'Finnish',
    'fr' => 'French',
    'ff' => 'Fulah',
    'gd' => 'Gaelic, Scottish Gaelic',
    'gl' => 'Galician',
    'lg' => 'Ganda',
    'ka' => 'Georgian',
    'de' => 'German',
    'ki' => 'Gikuyu, Kikuyu',
    'el' => 'Greek (Modern)',
    'kl' => 'Greenlandic, Kalaallisut',
    'gn' => 'Guarani',
    'gu' => 'Gujarati',
    'ht' => 'Haitian, Haitian Creole',
    'ha' => 'Hausa',
    'he' => 'Hebrew',
    'hz' => 'Herero',
    'hi' => 'Hindi',
    'ho' => 'Hiri Motu',
    'hu' => 'Hungarian',
    'is' => 'Icelandic',
    'io' => 'Ido',
    'ig' => 'Igbo',
    'id' => 'Indonesian',
    'ia' => 'Interlingua (International Auxiliary Language Association)',
    'ie' => 'Interlingue',
    'iu' => 'Inuktitut',
    'ik' => 'Inupiaq',
    'ga' => 'Irish',
    'it' => 'Italian',
    'ja' => 'Japanese',
    'jv' => 'Javanese',
    'kn' => 'Kannada',
    'kr' => 'Kanuri',
    'ks' => 'Kashmiri',
    'kk' => 'Kazakh',
    'rw' => 'Kinyarwanda',
    'kv' => 'Komi',
    'kg' => 'Kongo',
    'ko' => 'Korean',
    'kj' => 'Kwanyama, Kuanyama',
    'ku' => 'Kurdish',
    'ky' => 'Kyrgyz',
    'lo' => 'Lao',
    'la' => 'Latin',
    'lv' => 'Latvian',
    'lb' => 'Letzeburgesch, Luxembourgish',
    'li' => 'Limburgish, Limburgan, Limburger',
    'ln' => 'Lingala',
    'lt' => 'Lithuanian',
    'lu' => 'Luba-Katanga',
    'mk' => 'Macedonian',
    'mg' => 'Malagasy',
    'ms' => 'Malay',
    'ml' => 'Malayalam',
    'mt' => 'Maltese',
    'gv' => 'Manx',
    'mi' => 'Maori',
    'mr' => 'Marathi',
    'mh' => 'Marshallese',
    'ro' => 'Moldovan, Moldavian, Romanian',
    'mn' => 'Mongolian',
    'na' => 'Nauru',
    'nv' => 'Navajo, Navaho',
    'nd' => 'Northern Ndebele',
    'ng' => 'Ndonga',
    'ne' => 'Nepali',
    'se' => 'Northern Sami',
    'no' => 'Norwegian',
    'nb' => 'Norwegian Bokmål',
    'nn' => 'Norwegian Nynorsk',
    'ii' => 'Nuosu, Sichuan Yi',
    'oc' => 'Occitan (post 1500)',
    'oj' => 'Ojibwa',
    'or' => 'Oriya',
    'om' => 'Oromo',
    'os' => 'Ossetian, Ossetic',
    'pi' => 'Pali',
    'pa' => 'Panjabi, Punjabi',
    'ps' => 'Pashto, Pushto',
    'fa' => 'Persian',
    'pl' => 'Polish',
    'pt' => 'Portuguese',
    'qu' => 'Quechua',
    'rm' => 'Romansh',
    'rn' => 'Rundi',
    'ru' => 'Russian',
    'sm' => 'Samoan',
    'sg' => 'Sango',
    'sa' => 'Sanskrit',
    'sc' => 'Sardinian',
    'sr' => 'Serbian',
    'sn' => 'Shona',
    'sd' => 'Sindhi',
    'si' => 'Sinhala, Sinhalese',
    'sk' => 'Slovak',
    'sl' => 'Slovenian',
    'so' => 'Somali',
    'st' => 'Sotho, Southern',
    'nr' => 'South Ndebele',
    'es' => 'Spanish, Castilian',
    'su' => 'Sundanese',
    'sw' => 'Swahili',
    'ss' => 'Swati',
    'sv' => 'Swedish',
    'tl' => 'Tagalog',
    'ty' => 'Tahitian',
    'tg' => 'Tajik',
    'ta' => 'Tamil',
    'tt' => 'Tatar',
    'te' => 'Telugu',
    'th' => 'Thai',
    'bo' => 'Tibetan',
    'ti' => 'Tigrinya',
    'to' => 'Tonga (Tonga Islands)',
    'ts' => 'Tsonga',
    'tn' => 'Tswana',
    'tr' => 'Turkish',
    'tk' => 'Turkmen',
    'tw' => 'Twi',
    'ug' => 'Uighur, Uyghur',
    'uk' => 'Ukrainian',
    'ur' => 'Urdu',
    'uz' => 'Uzbek',
    've' => 'Venda',
    'vi' => 'Vietnamese',
    'vo' => 'Volap_k',
    'wa' => 'Walloon',
    'cy' => 'Welsh',
    'fy' => 'Western Frisian',
    'wo' => 'Wolof',
    'xh' => 'Xhosa',
    'yi' => 'Yiddish',
    'yo' => 'Yoruba',
    'za' => 'Zhuang, Chuang',
    'zu' => 'Zulu'
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
        case "section_group":
            $elements = LOCAL_DTA_SECTION_GROUPS;
            break;
        case "section_type":
            $elements = LOCAL_DTA_SECTION_TYPES;
            break;
        default:
            return $string;
    }
    if (!in_array($string, $elements)) {
        return $string;
    }
    return get_string($element . ":" . strtolower(str_replace("?", "", str_replace(" ", "_", $string))), 'local_dta');
}

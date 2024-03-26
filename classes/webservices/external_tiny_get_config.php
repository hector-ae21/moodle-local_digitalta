<?php

/**
 * External Web Service
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . './../tiny_editor_handler.php');

use local_dta\tiny_editor_handler;


class external_tiny_get_config extends external_api
{

    public static function tiny_get_config_parameters()
    {
        return new external_function_parameters([]);
    }

    public static function tiny_get_config()
    {   
        $configJson = '{
            "css": "http://localhost/moodle/theme/styles_debug.php?theme=dta&type=editor",
            "context": 17,
            "filepicker": {
              "h5p": {
                "defaultlicense": "unknown",
                "licenses": {
                  "unknown": {
                    "id": "1",
                    "shortname": "unknown",
                    "fullname": "Licence not specified",
                    "source": "",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "1"
                  },
                  "allrightsreserved": {
                    "id": "2",
                    "shortname": "allrightsreserved",
                    "fullname": "All rights reserved",
                    "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "2"
                  },
                  "public": {
                    "id": "3",
                    "shortname": "public",
                    "fullname": "Public domain",
                    "source": "https://en.wikipedia.org/wiki/Public_domain",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "3"
                  },
                  "cc-4.0": {
                    "id": "4",
                    "shortname": "cc-4.0",
                    "fullname": "Creative Commons - 4.0 International",
                    "source": "https://creativecommons.org/licenses/by/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "4"
                  },
                  "cc-nc-4.0": {
                    "id": "5",
                    "shortname": "cc-nc-4.0",
                    "fullname": "Creative Commons - NonCommercial 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "5"
                  },
                  "cc-nd-4.0": {
                    "id": "6",
                    "shortname": "cc-nd-4.0",
                    "fullname": "Creative Commons - NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "6"
                  },
                  "cc-nc-nd-4.0": {
                    "id": "7",
                    "shortname": "cc-nc-nd-4.0",
                    "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "7"
                  },
                  "cc-nc-sa-4.0": {
                    "id": "8",
                    "shortname": "cc-nc-sa-4.0",
                    "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "8"
                  },
                  "cc-sa-4.0": {
                    "id": "9",
                    "shortname": "cc-sa-4.0",
                    "fullname": "Creative Commons - ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "9"
                  }
                },
                "author": "Admin User",
                "repositories": {
                  "1": {
                    "id": "1",
                    "name": "Embedded files",
                    "type": "areafiles",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                    "supported_types": [],
                    "return_types": 1,
                    "defaultreturntype": 2,
                    "sortorder": 1
                  },
                  "2": {
                    "id": "2",
                    "name": "Content bank",
                    "type": "contentbank",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 4,
                    "sortorder": 2
                  },
                  "3": {
                    "id": "3",
                    "name": "Server files",
                    "type": "local",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 3
                  },
                  "4": {
                    "id": "4",
                    "name": "Recent files",
                    "type": "recent",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 4
                  },
                  "5": {
                    "id": "5",
                    "name": "Upload a file",
                    "type": "upload",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 5
                  },
                  "7": {
                    "id": "7",
                    "name": "Private files",
                    "type": "user",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 7
                  },
                  "8": {
                    "id": "8",
                    "name": "Wikimedia",
                    "type": "wikimedia",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                    "supported_types": [],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 8
                  }
                },
                "externallink": true,
                "rememberuserlicensepref": true,
                "userprefs": {
                  "recentrepository": "5",
                  "recentlicense": "unknown",
                  "recentviewmode": ""
                },
                "accepted_types": [
                  ".h5p"
                ],
                "return_types": 3,
                "context": {},
                "client_id": "6602a18a10029",
                "maxbytes": 41943040,
                "areamaxbytes": -1,
                "env": "editor",
                "itemid": 713532145
              },
              "image": {
                "defaultlicense": "unknown",
                "licenses": {
                  "unknown": {
                    "id": "1",
                    "shortname": "unknown",
                    "fullname": "Licence not specified",
                    "source": "",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "1"
                  },
                  "allrightsreserved": {
                    "id": "2",
                    "shortname": "allrightsreserved",
                    "fullname": "All rights reserved",
                    "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "2"
                  },
                  "public": {
                    "id": "3",
                    "shortname": "public",
                    "fullname": "Public domain",
                    "source": "https://en.wikipedia.org/wiki/Public_domain",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "3"
                  },
                  "cc-4.0": {
                    "id": "4",
                    "shortname": "cc-4.0",
                    "fullname": "Creative Commons - 4.0 International",
                    "source": "https://creativecommons.org/licenses/by/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "4"
                  },
                  "cc-nc-4.0": {
                    "id": "5",
                    "shortname": "cc-nc-4.0",
                    "fullname": "Creative Commons - NonCommercial 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "5"
                  },
                  "cc-nd-4.0": {
                    "id": "6",
                    "shortname": "cc-nd-4.0",
                    "fullname": "Creative Commons - NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "6"
                  },
                  "cc-nc-nd-4.0": {
                    "id": "7",
                    "shortname": "cc-nc-nd-4.0",
                    "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "7"
                  },
                  "cc-nc-sa-4.0": {
                    "id": "8",
                    "shortname": "cc-nc-sa-4.0",
                    "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "8"
                  },
                  "cc-sa-4.0": {
                    "id": "9",
                    "shortname": "cc-sa-4.0",
                    "fullname": "Creative Commons - ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "9"
                  }
                },
                "author": "Admin User",
                "repositories": {
                  "1": {
                    "id": "1",
                    "name": "Embedded files",
                    "type": "areafiles",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                    "supported_types": [],
                    "return_types": 1,
                    "defaultreturntype": 2,
                    "sortorder": 1
                  },
                  "2": {
                    "id": "2",
                    "name": "Content bank",
                    "type": "contentbank",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 4,
                    "sortorder": 2
                  },
                  "3": {
                    "id": "3",
                    "name": "Server files",
                    "type": "local",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 3
                  },
                  "4": {
                    "id": "4",
                    "name": "Recent files",
                    "type": "recent",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 4
                  },
                  "5": {
                    "id": "5",
                    "name": "Upload a file",
                    "type": "upload",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 5
                  },
                  "6": {
                    "id": "6",
                    "name": "URL downloader",
                    "type": "url",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_url&image=icon",
                    "supported_types": [
                      ".gif",
                      ".jpe",
                      ".jpeg",
                      ".jpg",
                      ".png",
                      ".svg",
                      ".svgz"
                    ],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 6
                  },
                  "7": {
                    "id": "7",
                    "name": "Private files",
                    "type": "user",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 7
                  },
                  "8": {
                    "id": "8",
                    "name": "Wikimedia",
                    "type": "wikimedia",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                    "supported_types": [],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 8
                  }
                },
                "externallink": true,
                "rememberuserlicensepref": true,
                "userprefs": {
                  "recentrepository": "5",
                  "recentlicense": "unknown",
                  "recentviewmode": ""
                },
                "accepted_types": [
                  ".gif",
                  ".jpe",
                  ".jpeg",
                  ".jpg",
                  ".png",
                  ".svg",
                  ".svgz"
                ],
                "return_types": 3,
                "context": {},
                "client_id": "6602a18a0c8fd",
                "maxbytes": 41943040,
                "areamaxbytes": -1,
                "env": "editor",
                "itemid": 713532145
              },
              "media": {
                "defaultlicense": "unknown",
                "licenses": {
                  "unknown": {
                    "id": "1",
                    "shortname": "unknown",
                    "fullname": "Licence not specified",
                    "source": "",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "1"
                  },
                  "allrightsreserved": {
                    "id": "2",
                    "shortname": "allrightsreserved",
                    "fullname": "All rights reserved",
                    "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "2"
                  },
                  "public": {
                    "id": "3",
                    "shortname": "public",
                    "fullname": "Public domain",
                    "source": "https://en.wikipedia.org/wiki/Public_domain",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "3"
                  },
                  "cc-4.0": {
                    "id": "4",
                    "shortname": "cc-4.0",
                    "fullname": "Creative Commons - 4.0 International",
                    "source": "https://creativecommons.org/licenses/by/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "4"
                  },
                  "cc-nc-4.0": {
                    "id": "5",
                    "shortname": "cc-nc-4.0",
                    "fullname": "Creative Commons - NonCommercial 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "5"
                  },
                  "cc-nd-4.0": {
                    "id": "6",
                    "shortname": "cc-nd-4.0",
                    "fullname": "Creative Commons - NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "6"
                  },
                  "cc-nc-nd-4.0": {
                    "id": "7",
                    "shortname": "cc-nc-nd-4.0",
                    "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "7"
                  },
                  "cc-nc-sa-4.0": {
                    "id": "8",
                    "shortname": "cc-nc-sa-4.0",
                    "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "8"
                  },
                  "cc-sa-4.0": {
                    "id": "9",
                    "shortname": "cc-sa-4.0",
                    "fullname": "Creative Commons - ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "9"
                  }
                },
                "author": "Admin User",
                "repositories": {
                  "1": {
                    "id": "1",
                    "name": "Embedded files",
                    "type": "areafiles",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                    "supported_types": [],
                    "return_types": 1,
                    "defaultreturntype": 2,
                    "sortorder": 1
                  },
                  "2": {
                    "id": "2",
                    "name": "Content bank",
                    "type": "contentbank",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 4,
                    "sortorder": 2
                  },
                  "3": {
                    "id": "3",
                    "name": "Server files",
                    "type": "local",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 3
                  },
                  "4": {
                    "id": "4",
                    "name": "Recent files",
                    "type": "recent",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 4
                  },
                  "5": {
                    "id": "5",
                    "name": "Upload a file",
                    "type": "upload",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 5
                  },
                  "7": {
                    "id": "7",
                    "name": "Private files",
                    "type": "user",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 7
                  },
                  "8": {
                    "id": "8",
                    "name": "Wikimedia",
                    "type": "wikimedia",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                    "supported_types": [],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 8
                  }
                },
                "externallink": true,
                "rememberuserlicensepref": true,
                "userprefs": {
                  "recentrepository": "5",
                  "recentlicense": "unknown",
                  "recentviewmode": ""
                },
                "accepted_types": [
                  ".3gp",
                  ".avi",
                  ".dv",
                  ".dif",
                  ".flv",
                  ".f4v",
                  ".fmp4",
                  ".mov",
                  ".movie",
                  ".mp4",
                  ".m4v",
                  ".mpeg",
                  ".mpe",
                  ".mpg",
                  ".ogv",
                  ".qt",
                  ".rmvb",
                  ".rv",
                  ".ts",
                  ".webm",
                  ".wmv",
                  ".asf",
                  ".aac",
                  ".aif",
                  ".aiff",
                  ".aifc",
                  ".au",
                  ".flac",
                  ".m3u",
                  ".mp3",
                  ".m4a",
                  ".oga",
                  ".ogg",
                  ".ra",
                  ".ram",
                  ".rm",
                  ".wav",
                  ".wma"
                ],
                "return_types": 3,
                "context": {},
                "client_id": "6602a18a0d5f7",
                "maxbytes": 41943040,
                "areamaxbytes": -1,
                "env": "editor",
                "itemid": 713532145
              },
              "link": {
                "defaultlicense": "unknown",
                "licenses": {
                  "unknown": {
                    "id": "1",
                    "shortname": "unknown",
                    "fullname": "Licence not specified",
                    "source": "",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "1"
                  },
                  "allrightsreserved": {
                    "id": "2",
                    "shortname": "allrightsreserved",
                    "fullname": "All rights reserved",
                    "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "2"
                  },
                  "public": {
                    "id": "3",
                    "shortname": "public",
                    "fullname": "Public domain",
                    "source": "https://en.wikipedia.org/wiki/Public_domain",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "3"
                  },
                  "cc-4.0": {
                    "id": "4",
                    "shortname": "cc-4.0",
                    "fullname": "Creative Commons - 4.0 International",
                    "source": "https://creativecommons.org/licenses/by/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "4"
                  },
                  "cc-nc-4.0": {
                    "id": "5",
                    "shortname": "cc-nc-4.0",
                    "fullname": "Creative Commons - NonCommercial 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "5"
                  },
                  "cc-nd-4.0": {
                    "id": "6",
                    "shortname": "cc-nd-4.0",
                    "fullname": "Creative Commons - NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "6"
                  },
                  "cc-nc-nd-4.0": {
                    "id": "7",
                    "shortname": "cc-nc-nd-4.0",
                    "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "7"
                  },
                  "cc-nc-sa-4.0": {
                    "id": "8",
                    "shortname": "cc-nc-sa-4.0",
                    "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "8"
                  },
                  "cc-sa-4.0": {
                    "id": "9",
                    "shortname": "cc-sa-4.0",
                    "fullname": "Creative Commons - ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "9"
                  }
                },
                "author": "Admin User",
                "repositories": {
                  "1": {
                    "id": "1",
                    "name": "Embedded files",
                    "type": "areafiles",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                    "supported_types": [],
                    "return_types": 1,
                    "defaultreturntype": 2,
                    "sortorder": 1
                  },
                  "2": {
                    "id": "2",
                    "name": "Content bank",
                    "type": "contentbank",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 4,
                    "sortorder": 2
                  },
                  "3": {
                    "id": "3",
                    "name": "Server files",
                    "type": "local",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 3
                  },
                  "4": {
                    "id": "4",
                    "name": "Recent files",
                    "type": "recent",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 4
                  },
                  "5": {
                    "id": "5",
                    "name": "Upload a file",
                    "type": "upload",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 5
                  },
                  "6": {
                    "id": "6",
                    "name": "URL downloader",
                    "type": "url",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_url&image=icon",
                    "supported_types": [
                      ".gif",
                      ".jpe",
                      ".jpeg",
                      ".jpg",
                      ".png",
                      ".svg",
                      ".svgz"
                    ],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 6
                  },
                  "7": {
                    "id": "7",
                    "name": "Private files",
                    "type": "user",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 7
                  },
                  "8": {
                    "id": "8",
                    "name": "Wikimedia",
                    "type": "wikimedia",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                    "supported_types": [],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 8
                  }
                },
                "externallink": true,
                "rememberuserlicensepref": true,
                "userprefs": {
                  "recentrepository": "5",
                  "recentlicense": "unknown",
                  "recentviewmode": ""
                },
                "accepted_types": [],
                "return_types": 3,
                "context": {},
                "client_id": "6602a18a0e959",
                "maxbytes": 41943040,
                "areamaxbytes": -1,
                "env": "editor",
                "itemid": 713532145
              },
              "subtitle": {
                "defaultlicense": "unknown",
                "licenses": {
                  "unknown": {
                    "id": "1",
                    "shortname": "unknown",
                    "fullname": "Licence not specified",
                    "source": "",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "1"
                  },
                  "allrightsreserved": {
                    "id": "2",
                    "shortname": "allrightsreserved",
                    "fullname": "All rights reserved",
                    "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "2"
                  },
                  "public": {
                    "id": "3",
                    "shortname": "public",
                    "fullname": "Public domain",
                    "source": "https://en.wikipedia.org/wiki/Public_domain",
                    "enabled": "1",
                    "version": "2010033100",
                    "custom": "0",
                    "sortorder": "3"
                  },
                  "cc-4.0": {
                    "id": "4",
                    "shortname": "cc-4.0",
                    "fullname": "Creative Commons - 4.0 International",
                    "source": "https://creativecommons.org/licenses/by/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "4"
                  },
                  "cc-nc-4.0": {
                    "id": "5",
                    "shortname": "cc-nc-4.0",
                    "fullname": "Creative Commons - NonCommercial 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "5"
                  },
                  "cc-nd-4.0": {
                    "id": "6",
                    "shortname": "cc-nd-4.0",
                    "fullname": "Creative Commons - NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "6"
                  },
                  "cc-nc-nd-4.0": {
                    "id": "7",
                    "shortname": "cc-nc-nd-4.0",
                    "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "7"
                  },
                  "cc-nc-sa-4.0": {
                    "id": "8",
                    "shortname": "cc-nc-sa-4.0",
                    "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "8"
                  },
                  "cc-sa-4.0": {
                    "id": "9",
                    "shortname": "cc-sa-4.0",
                    "fullname": "Creative Commons - ShareAlike 4.0 International",
                    "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                    "enabled": "1",
                    "version": "2022120100",
                    "custom": "0",
                    "sortorder": "9"
                  }
                },
                "author": "Admin User",
                "repositories": {
                  "1": {
                    "id": "1",
                    "name": "Embedded files",
                    "type": "areafiles",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                    "supported_types": [],
                    "return_types": 1,
                    "defaultreturntype": 2,
                    "sortorder": 1
                  },
                  "2": {
                    "id": "2",
                    "name": "Content bank",
                    "type": "contentbank",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 4,
                    "sortorder": 2
                  },
                  "3": {
                    "id": "3",
                    "name": "Server files",
                    "type": "local",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 3
                  },
                  "4": {
                    "id": "4",
                    "name": "Recent files",
                    "type": "recent",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 4
                  },
                  "5": {
                    "id": "5",
                    "name": "Upload a file",
                    "type": "upload",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                    "supported_types": [],
                    "return_types": 2,
                    "defaultreturntype": 2,
                    "sortorder": 5
                  },
                  "7": {
                    "id": "7",
                    "name": "Private files",
                    "type": "user",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                    "supported_types": [],
                    "return_types": 6,
                    "defaultreturntype": 2,
                    "sortorder": 7
                  },
                  "8": {
                    "id": "8",
                    "name": "Wikimedia",
                    "type": "wikimedia",
                    "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                    "supported_types": [],
                    "return_types": 3,
                    "defaultreturntype": 2,
                    "sortorder": 8
                  }
                },
                "externallink": true,
                "rememberuserlicensepref": true,
                "userprefs": {
                  "recentrepository": "5",
                  "recentlicense": "unknown",
                  "recentviewmode": ""
                },
                "accepted_types": [
                  ".vtt"
                ],
                "return_types": 3,
                "context": {},
                "client_id": "6602a18a0f492",
                "maxbytes": 41943040,
                "areamaxbytes": -1,
                "env": "editor",
                "itemid": 713532145
              }
            },
            "currentLanguage": "en",
            "branding": true,
            "language": {
              "currentlang": "en",
              "installed": {
                "en": "English \u200e(en)\u200e",
                "es": "Espa\u00f1ol - Internacional \u200e(es)\u200e"
              },
              "available": {
                "aa": "Afar",
                "ab": "Abkhazian",
                "ae": "Avestan",
                "af": "Afrikaans",
                "ak": "Akan",
                "am": "Amharic",
                "an": "Aragonese",
                "ar": "Arabic",
                "as": "Assamese",
                "av": "Avaric",
                "ay": "Aymara",
                "az": "Azerbaijani",
                "ba": "Bashkir",
                "be": "Belarusian",
                "bg": "Bulgarian",
                "bh": "Bihari languages",
                "bi": "Bislama",
                "bm": "Bambara",
                "bn": "Bengali",
                "bo": "Tibetan",
                "br": "Breton",
                "bs": "Bosnian",
                "ca": "Catalan; Valencian",
                "ce": "Chechen",
                "ch": "Chamorro",
                "co": "Corsican",
                "cr": "Cree",
                "cs": "Czech",
                "cu": "Church Slavic; Old Slavonic",
                "cv": "Chuvash",
                "cy": "Welsh",
                "da": "Danish",
                "de": "German",
                "dv": "Divehi; Dhivehi; Maldivian",
                "dz": "Dzongkha",
                "ee": "Ewe",
                "el": "Greek, Modern (1453-)",
                "en": "English",
                "eo": "Esperanto",
                "es": "Spanish; Castilian",
                "et": "Estonian",
                "eu": "Basque",
                "fa": "Persian",
                "ff": "Fulah",
                "fi": "Finnish",
                "fj": "Fijian",
                "fo": "Faroese",
                "fr": "French",
                "fy": "Western Frisian",
                "ga": "Irish",
                "gd": "Gaelic; Scottish Gaelic",
                "gl": "Galician",
                "gn": "Guarani",
                "gu": "Gujarati",
                "gv": "Manx",
                "ha": "Hausa",
                "he": "Hebrew",
                "hi": "Hindi",
                "ho": "Hiri Motu",
                "hr": "Croatian",
                "ht": "Haitian; Haitian Creole",
                "hu": "Hungarian",
                "hy": "Armenian",
                "hz": "Herero",
                "ia": "Interlingua (IALA)",
                "id": "Indonesian",
                "ie": "Interlingue; Occidental",
                "ig": "Igbo",
                "ii": "Sichuan Yi; Nuosu",
                "ik": "Inupiaq",
                "io": "Ido",
                "is": "Icelandic",
                "it": "Italian",
                "iu": "Inuktitut",
                "ja": "Japanese",
                "jv": "Javanese",
                "ka": "Georgian",
                "kg": "Kongo",
                "ki": "Kikuyu; Gikuyu",
                "kj": "Kuanyama; Kwanyama",
                "kk": "Kazakh",
                "kl": "Kalaallisut; Greenlandic",
                "km": "Central Khmer",
                "kn": "Kannada",
                "ko": "Korean",
                "kr": "Kanuri",
                "ks": "Kashmiri",
                "ku": "Kurdish",
                "kv": "Komi",
                "kw": "Cornish",
                "ky": "Kirghiz; Kyrgyz",
                "la": "Latin",
                "lb": "Luxembourgish",
                "lg": "Ganda",
                "li": "Limburgan",
                "ln": "Lingala",
                "lo": "Lao",
                "lt": "Lithuanian",
                "lu": "Luba-Katanga",
                "lv": "Latvian",
                "mg": "Malagasy",
                "mh": "Marshallese",
                "mi": "Maori",
                "mk": "Macedonian",
                "ml": "Malayalam",
                "mn": "Mongolian",
                "mr": "Marathi",
                "ms": "Malay",
                "mt": "Maltese",
                "my": "Burmese",
                "na": "Nauru",
                "nb": "Norwegian Bokm\u00e5l",
                "nd": "Ndebele, North",
                "ne": "Nepali",
                "ng": "Ndonga",
                "nl": "Dutch; Flemish",
                "nn": "Norwegian Nynorsk",
                "no": "Norwegian",
                "nr": "Ndebele, South",
                "nv": "Navajo; Navaho",
                "ny": "Chichewa",
                "oc": "Occitan (post 1500); Proven\u00e7al",
                "oj": "Ojibwa",
                "om": "Oromo",
                "or": "Oriya",
                "os": "Ossetian; Ossetic",
                "pa": "Panjabi; Punjabi",
                "pi": "Pali",
                "pl": "Polish",
                "ps": "Pushto; Pashto",
                "pt": "Portuguese",
                "qu": "Quechua",
                "rm": "Romansh",
                "rn": "Rundi",
                "ro": "Romanian; Moldavian; Moldovan",
                "ru": "Russian",
                "rw": "Kinyarwanda",
                "sa": "Sanskrit",
                "sc": "Sardinian",
                "sd": "Sindhi",
                "se": "Northern Sami",
                "sg": "Sango",
                "si": "Sinhala; Sinhalese",
                "sk": "Slovak",
                "sl": "Slovenian",
                "sm": "Samoan",
                "sn": "Shona",
                "so": "Somali",
                "sq": "Albanian",
                "sr": "Serbian",
                "ss": "Swati",
                "st": "Sotho, Southern",
                "su": "Sundanese",
                "sv": "Swedish",
                "sw": "Swahili",
                "ta": "Tamil",
                "te": "Telugu",
                "tg": "Tajik",
                "th": "Thai",
                "ti": "Tigrinya",
                "tk": "Turkmen",
                "tl": "Tagalog",
                "tn": "Tswana",
                "to": "Tonga (Tonga Islands)",
                "tr": "Turkish",
                "ts": "Tsonga",
                "tt": "Tatar",
                "tw": "Twi",
                "ty": "Tahitian",
                "ug": "Uighur; Uyghur",
                "uk": "Ukrainian",
                "ur": "Urdu",
                "uz": "Uzbek",
                "ve": "Venda",
                "vi": "Vietnamese",
                "vo": "Volap\u00fck",
                "wa": "Walloon",
                "wo": "Wolof",
                "xh": "Xhosa",
                "yi": "Yiddish",
                "yo": "Yoruba",
                "za": "Zhuang; Chuang",
                "zh": "Chinese",
                "zu": "Zulu"
              }
            },
            "placeholderSelectors": [],
            "plugins": {
              "anchor": {
                "buttons": [
                  "anchor"
                ],
                "menuitems": {
                  "anchor": "insert"
                }
              },
              "charmap": {
                "buttons": [
                  "charmap"
                ],
                "menuitems": {
                  "charmap": "insert"
                }
              },
              "code": {
                "buttons": [
                  "code"
                ],
                "menuitems": {
                  "code": "view"
                }
              },
              "codesample": {
                "buttons": [
                  "codesample"
                ],
                "menutiems": {
                  "codesample": "insert"
                }
              },
              "directionality": {
                "buttons": [
                  "ltr",
                  "rtl"
                ]
              },
              "emoticons": {
                "buttons": [
                  "emoticons"
                ],
                "menuitems": {
                  "emoticons": "insert"
                }
              },
              "fullscreen": {
                "buttons": [
                  "fullscreen"
                ],
                "menuitems": {
                  "fullscreen": "view"
                }
              },
              "help": {
                "buttons": [
                  "help"
                ],
                "menuitems": {
                  "help": "help"
                }
              },
              "insertdatetime": {
                "buttons": [
                  "insertdatetime"
                ],
                "menuitems": {
                  "insertdatetime": "insert"
                }
              },
              "lists": {
                "buttons": [
                  "bullist",
                  "numlist"
                ]
              },
              "nonbreaking": {
                "buttons": [
                  "nonbreaking"
                ],
                "menuitems": {
                  "nonbreaking": "insert"
                }
              },
              "pagebreak": {
                "buttons": [
                  "pagebreak"
                ],
                "menuitems": {
                  "pagebreak": "insert"
                }
              },
              "quickbars": {
                "buttons": [
                  "quickimage",
                  "quicklink",
                  "quicktable"
                ]
              },
              "save": {
                "buttons": [
                  "cancel",
                  "save"
                ]
              },
              "searchreplace": {
                "buttons": [
                  "searchreplace"
                ],
                "menuitems": {
                  "searchreplace": "edit"
                }
              },
              "table": {
                "buttons": [
                  "table",
                  "tablecellprops",
                  "tablecopyrow",
                  "tablecutrow",
                  "tabledelete",
                  "tabledeletecol",
                  "tabledeleterow",
                  "tableinsertdialog",
                  "tableinsertcolafter",
                  "tableinsertcolbefore",
                  "tableinsertrowafter",
                  "tableinsertrowbefore",
                  "tablemergecells",
                  "tablepasterowafter",
                  "tablepasterowbefore",
                  "tableprops",
                  "tablerowprops",
                  "tablesplitcells",
                  "tableclass",
                  "tablecellclass",
                  "tablecellvalign",
                  "tablecellborderwidth",
                  "tablecellborderstyle",
                  "tablecaption",
                  "tablecellbackgroundcolor",
                  "tablecellbordercolor",
                  "tablerowheader",
                  "tablecolheader"
                ],
                "menuitems": {
                  "inserttable": "table",
                  "tableprops": "table",
                  "deletetable": "table",
                  "cell": "table",
                  "tablemergecells": "table",
                  "tablesplitcells": "table",
                  "tablecellprops": "table",
                  "column": "table",
                  "tableinsertcolumnbefore": "table",
                  "tableinsertcolumnafter": "table",
                  "tablecutcolumn": "table",
                  "tablecopycolumn": "table",
                  "tablepastecolumnbefore": "table",
                  "tablepastecolumnafter": "table",
                  "tabledeletecolumn": "table",
                  "row": "table",
                  "tableinsertrowbefore": "table",
                  "tableinsertrowafter": "table",
                  "tablecutrow": "table",
                  "tablecopyrow": "table",
                  "tablepasterowbefore": "table",
                  "tablepasterowafter": "table",
                  "tablerowprops": "table",
                  "tabledeleterow": "table"
                }
              },
              "visualblocks": {
                "buttons": [
                  "visualblocks"
                ],
                "menuitems": {
                  "visualblocks": "view"
                }
              },
              "visualchars": {
                "buttons": [
                  "visualchars"
                ],
                "menuitems": {
                  "visualchars": "view"
                }
              },
              "wordcount": {
                "buttons": [
                  "wordcount"
                ],
                "menuitems": {
                  "wordcount": "tools"
                }
              },
              "tiny_accessibilitychecker/plugin": {
                "buttons": [
                  "tiny_accessibilitychecker/tiny_accessibilitychecker_image"
                ],
                "menuitems": [
                  "tiny_accessibilitychecker/tiny_accessibilitychecker_image"
                ],
                "config": {
                  "permissions": {
                    "upload": true
                  },
                  "storeinrepo": true
                }
              },
              "tiny_autosave/plugin": {
                "config": {
                  "pagehash": "52afb582df8bfcbb097dcea50a037dc225394dfb",
                  "pageinstance": "51bc4f146f2d1b41ad37e0aea5836e69",
                  "backoffTime": 500
                }
              },
              "tiny_equation/plugin": {
                "buttons": [
                  "tiny_equation/equation"
                ],
                "menuitems": [
                  "tiny_equation/equation"
                ],
                "config": {
                  "texfilter": true,
                  "contextid": 17,
                  "libraries": [
                    {
                      "key": "group1",
                      "groupname": "Operators",
                      "elements": [
                        "cdot",
                        "times",
                        "ast",
                        "div",
                        "diamond",
                        "pm",
                        "mp",
                        "oplus",
                        "ominus",
                        "otimes",
                        "oslash",
                        "odot",
                        "circ",
                        "bullet",
                        "asymp",
                        "equiv",
                        "subseteq",
                        "supseteq",
                        "leq",
                        "geq",
                        "preceq",
                        "succeq",
                        "sim",
                        "simeq",
                        "approx",
                        "subset",
                        "supset",
                        "ll",
                        "gg",
                        "prec",
                        "succ",
                        "infty",
                        "in",
                        "ni",
                        "forall",
                        "exists",
                        "neq"
                      ],
                      "active": true
                    },
                    {
                      "key": "group2",
                      "groupname": "Arrows",
                      "elements": [
                        "leftarrow",
                        "rightarrow",
                        "uparrow",
                        "downarrow",
                        "leftrightarrow",
                        "nearrow",
                        "searrow",
                        "swarrow",
                        "nwarrow",
                        "Leftarrow",
                        "Rightarrow",
                        "Uparrow",
                        "Downarrow",
                        "Leftrightarrow"
                      ]
                    },
                    {
                      "key": "group3",
                      "groupname": "Greek symbols",
                      "elements": [
                        "alpha",
                        "beta",
                        "gamma",
                        "delta",
                        "epsilon",
                        "zeta",
                        "eta",
                        "theta",
                        "iota",
                        "kappa",
                        "lambda",
                        "mu",
                        "nu",
                        "xi",
                        "pi",
                        "rho",
                        "sigma",
                        "tau",
                        "upsilon",
                        "phi",
                        "chi",
                        "psi",
                        "omega",
                        "Gamma",
                        "Delta",
                        "Theta",
                        "Lambda",
                        "Xi",
                        "Pi",
                        "Sigma",
                        "Upsilon",
                        "Phi",
                        "Psi",
                        "Omega"
                      ]
                    },
                    {
                      "key": "group4",
                      "groupname": "Advanced",
                      "elements": [
                        "sum{a,b}",
                        "sqrt[a]{b+c}",
                        "int_{a}^{b}{c}",
                        "iint_{a}^{b}{c}",
                        "iiint_{a}^{b}{c}",
                        "oint{a}",
                        "(a)",
                        "[a]",
                        "lbrace{a}rbrace",
                        "left| begin{matrix} a_1 & a_2  a_3 & a_4 end{matrix} right|",
                        "frac{a}{b+c}",
                        "vec{a}",
                        "binom {a} {b}",
                        "{a brack b}",
                        "{a brace b}"
                      ]
                    }
                  ],
                  "texdocsurl": "https://docs.moodle.org/401/en/Using_TeX_Notation"
                }
              },
              "tiny_h5p/plugin": {
                "buttons": [
                  "tiny_h5p/h5p"
                ],
                "menuitems": [
                  "tiny_h5p/h5p"
                ],
                "config": {
                  "permissions": {
                    "embed": true,
                    "upload": true,
                    "uploadandembed": true
                  },
                  "storeinrepo": true
                }
              },
              "tiny_link/plugin": {
                "buttons": [
                  "tiny_link/tiny_link_link",
                  "tiny_link/tiny_link_unlink"
                ],
                "menuitems": [
                  "tiny_link/tiny_link_link"
                ],
                "config": {
                  "permissions": {
                    "filepicker": true
                  }
                }
              },
              "tiny_media/plugin": {
                "buttons": [
                  "tiny_media/tiny_media_image"
                ],
                "menuitems": [
                  "tiny_media/tiny_media_image"
                ],
                "config": {
                  "permissions": {
                    "image": {
                      "filepicker": true
                    },
                    "embed": {
                      "filepicker": true
                    }
                  },
                  "storeinrepo": true,
                  "data": {
                    "params": {
                      "area": {
                        "context": 17,
                        "areamaxbytes": -1,
                        "maxbytes": 41943040,
                        "subdirs": 0,
                        "return_types": 3,
                        "removeorphaneddrafts": false
                      },
                      "usercontext": 5
                    },
                    "fpoptions": {
                      "h5p": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".h5p"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a10029",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "image": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "6": {
                            "id": "6",
                            "name": "URL downloader",
                            "type": "url",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_url&image=icon",
                            "supported_types": [
                              ".gif",
                              ".jpe",
                              ".jpeg",
                              ".jpg",
                              ".png",
                              ".svg",
                              ".svgz"
                            ],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 6
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".gif",
                          ".jpe",
                          ".jpeg",
                          ".jpg",
                          ".png",
                          ".svg",
                          ".svgz"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0c8fd",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "media": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".3gp",
                          ".avi",
                          ".dv",
                          ".dif",
                          ".flv",
                          ".f4v",
                          ".fmp4",
                          ".mov",
                          ".movie",
                          ".mp4",
                          ".m4v",
                          ".mpeg",
                          ".mpe",
                          ".mpg",
                          ".ogv",
                          ".qt",
                          ".rmvb",
                          ".rv",
                          ".ts",
                          ".webm",
                          ".wmv",
                          ".asf",
                          ".aac",
                          ".aif",
                          ".aiff",
                          ".aifc",
                          ".au",
                          ".flac",
                          ".m3u",
                          ".mp3",
                          ".m4a",
                          ".oga",
                          ".ogg",
                          ".ra",
                          ".ram",
                          ".rm",
                          ".wav",
                          ".wma"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0d5f7",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "link": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "6": {
                            "id": "6",
                            "name": "URL downloader",
                            "type": "url",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_url&image=icon",
                            "supported_types": [
                              ".gif",
                              ".jpe",
                              ".jpeg",
                              ".jpg",
                              ".png",
                              ".svg",
                              ".svgz"
                            ],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 6
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0e959",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "subtitle": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".vtt"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0f492",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      }
                    }
                  }
                }
              },
              "tiny_recordrtc/plugin": {
                "buttons": [
                  "tiny_recordrtc/tiny_recordrtc_image"
                ],
                "menuitems": [
                  "tiny_recordrtc/tiny_recordrtc_image"
                ],
                "config": {
                  "data": {
                    "params": {
                      "contextid": 17,
                      "sesskey": "7tC5ZQQoc2",
                      "allowedtypes": "both",
                      "audiobitrate": "128000",
                      "videobitrate": "2500000",
                      "audiotimelimit": "120",
                      "videotimelimit": "120",
                      "maxrecsize": 41943040
                    },
                    "fpoptions": {
                      "h5p": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".h5p"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a10029",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "image": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "6": {
                            "id": "6",
                            "name": "URL downloader",
                            "type": "url",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_url&image=icon",
                            "supported_types": [
                              ".gif",
                              ".jpe",
                              ".jpeg",
                              ".jpg",
                              ".png",
                              ".svg",
                              ".svgz"
                            ],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 6
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".gif",
                          ".jpe",
                          ".jpeg",
                          ".jpg",
                          ".png",
                          ".svg",
                          ".svgz"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0c8fd",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "media": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".3gp",
                          ".avi",
                          ".dv",
                          ".dif",
                          ".flv",
                          ".f4v",
                          ".fmp4",
                          ".mov",
                          ".movie",
                          ".mp4",
                          ".m4v",
                          ".mpeg",
                          ".mpe",
                          ".mpg",
                          ".ogv",
                          ".qt",
                          ".rmvb",
                          ".rv",
                          ".ts",
                          ".webm",
                          ".wmv",
                          ".asf",
                          ".aac",
                          ".aif",
                          ".aiff",
                          ".aifc",
                          ".au",
                          ".flac",
                          ".m3u",
                          ".mp3",
                          ".m4a",
                          ".oga",
                          ".ogg",
                          ".ra",
                          ".ram",
                          ".rm",
                          ".wav",
                          ".wma"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0d5f7",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "link": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "6": {
                            "id": "6",
                            "name": "URL downloader",
                            "type": "url",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_url&image=icon",
                            "supported_types": [
                              ".gif",
                              ".jpe",
                              ".jpeg",
                              ".jpg",
                              ".png",
                              ".svg",
                              ".svgz"
                            ],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 6
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0e959",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      },
                      "subtitle": {
                        "defaultlicense": "unknown",
                        "licenses": {
                          "unknown": {
                            "id": "1",
                            "shortname": "unknown",
                            "fullname": "Licence not specified",
                            "source": "",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "1"
                          },
                          "allrightsreserved": {
                            "id": "2",
                            "shortname": "allrightsreserved",
                            "fullname": "All rights reserved",
                            "source": "https://en.wikipedia.org/wiki/All_rights_reserved",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "2"
                          },
                          "public": {
                            "id": "3",
                            "shortname": "public",
                            "fullname": "Public domain",
                            "source": "https://en.wikipedia.org/wiki/Public_domain",
                            "enabled": "1",
                            "version": "2010033100",
                            "custom": "0",
                            "sortorder": "3"
                          },
                          "cc-4.0": {
                            "id": "4",
                            "shortname": "cc-4.0",
                            "fullname": "Creative Commons - 4.0 International",
                            "source": "https://creativecommons.org/licenses/by/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "4"
                          },
                          "cc-nc-4.0": {
                            "id": "5",
                            "shortname": "cc-nc-4.0",
                            "fullname": "Creative Commons - NonCommercial 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "5"
                          },
                          "cc-nd-4.0": {
                            "id": "6",
                            "shortname": "cc-nd-4.0",
                            "fullname": "Creative Commons - NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "6"
                          },
                          "cc-nc-nd-4.0": {
                            "id": "7",
                            "shortname": "cc-nc-nd-4.0",
                            "fullname": "Creative Commons - NonCommercial-NoDerivatives 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-nd/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "7"
                          },
                          "cc-nc-sa-4.0": {
                            "id": "8",
                            "shortname": "cc-nc-sa-4.0",
                            "fullname": "Creative Commons - NonCommercial-ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-nc-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "8"
                          },
                          "cc-sa-4.0": {
                            "id": "9",
                            "shortname": "cc-sa-4.0",
                            "fullname": "Creative Commons - ShareAlike 4.0 International",
                            "source": "https://creativecommons.org/licenses/by-sa/4.0/",
                            "enabled": "1",
                            "version": "2022120100",
                            "custom": "0",
                            "sortorder": "9"
                          }
                        },
                        "author": "Admin User",
                        "repositories": {
                          "1": {
                            "id": "1",
                            "name": "Embedded files",
                            "type": "areafiles",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_areafiles&image=icon",
                            "supported_types": [],
                            "return_types": 1,
                            "defaultreturntype": 2,
                            "sortorder": 1
                          },
                          "2": {
                            "id": "2",
                            "name": "Content bank",
                            "type": "contentbank",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_contentbank&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 4,
                            "sortorder": 2
                          },
                          "3": {
                            "id": "3",
                            "name": "Server files",
                            "type": "local",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_local&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 3
                          },
                          "4": {
                            "id": "4",
                            "name": "Recent files",
                            "type": "recent",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_recent&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 4
                          },
                          "5": {
                            "id": "5",
                            "name": "Upload a file",
                            "type": "upload",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_upload&image=icon",
                            "supported_types": [],
                            "return_types": 2,
                            "defaultreturntype": 2,
                            "sortorder": 5
                          },
                          "7": {
                            "id": "7",
                            "name": "Private files",
                            "type": "user",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_user&image=icon",
                            "supported_types": [],
                            "return_types": 6,
                            "defaultreturntype": 2,
                            "sortorder": 7
                          },
                          "8": {
                            "id": "8",
                            "name": "Wikimedia",
                            "type": "wikimedia",
                            "icon": "http://localhost/moodle/theme/image.php?theme=dta&component=repository_wikimedia&image=icon",
                            "supported_types": [],
                            "return_types": 3,
                            "defaultreturntype": 2,
                            "sortorder": 8
                          }
                        },
                        "externallink": true,
                        "rememberuserlicensepref": true,
                        "userprefs": {
                          "recentrepository": "5",
                          "recentlicense": "unknown",
                          "recentviewmode": ""
                        },
                        "accepted_types": [
                          ".vtt"
                        ],
                        "return_types": 3,
                        "context": {},
                        "client_id": "6602a18a0f492",
                        "maxbytes": 41943040,
                        "areamaxbytes": -1,
                        "env": "editor",
                        "itemid": 713532145
                      }
                    }
                  },
                  "videoAllowed": true,
                  "audioAllowed": true
                }
              }
            },
            "nestedmenu": true,
            "draftitemid": 713532145
          }';
        return $configJson;
    }

    public static function tiny_get_config_returns()
    {
        return new external_value(PARAM_RAW, 'Encoded JSON');
    }
}

<?php
namespace local_dta;
class CONSTANTS{

    const REACTIONS_INSTANCES = [
        "CASE" => 0,
        "EXPERIENCE" => 1
    ];
    const GROUPS = [
        "SO_WHAT_HOW" => 1,
        "NOW_WHAT_ACTION" => 2,
        "NOW_WHAT_REFLECTION" => 3,
        "EXTRA" => 6 
    ]; 

    const SECTION_TYPES = [
        "TEXT" => [
            "ID" => 1,
            "TABLE" => "digital_refl_sec_text",
        ],
        "CASES" => [
            "ID" => 2,
            "TABLE" => "digital_refl_sec_cases",
        ],
    ];
}
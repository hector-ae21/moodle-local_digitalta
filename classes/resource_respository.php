<?php

/**
 * Resource Repository class
 *
 * @package   local_dta
 * @copyright 2024 ADSDR-FUNIBER Scepter Team
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_dta;

use Exception;

class ResourceRepository {

    /**
     * Get resources based on provided filters.
     *
     * @param array $filters Array of filters to apply.
     *                       Accepted filters: 'type', 'language'.
     * @return array Array of filtered resources.
     */
    public static function get_resources(array $filters = []) : array {
        $resources = array();

        if (empty($filters)) {
            return self::get_all_resources();
        }

        foreach ($filters as $filter_key => $filter_value) {
            switch ($filter_key) {
                case 'type':
                    $resources = self::apply_type_filter($filter_value, $resources);
                    break;
                case 'language':
                    $resources = self::apply_language_filter($filter_value, $resources);
                    break;
                default:
                    break;
            }
        }

        return $resources;
    }

    /**
     * Apply type filter to the resources.
     *
     * @param array $types Array of resource types to filter by.
     * @param array $resources Array of resources to filter.
     * @return array Array of filtered resources.
     */
    private static function apply_type_filter(array $types, array $resources) : array {
        $filtered_resources = array();

        foreach ($types as $type) {

            $type_resources = Resource::get_resources_by_type($type);
            if (empty($filtered_resources)) {
                $filtered_resources = $type_resources;
            } else {
                $filtered_resources = array_intersect($filtered_resources, $type_resources);
            }
        }
        return $filtered_resources;
    }

    /**
     * Apply language filter to the resources.
     *
     * @param array $languages Array of languages to filter by.
     * @param array $resources Array of resources to filter.
     * @return array Array of filtered resources.
     */
    private static function apply_language_filter(array $languages, array $resources) : array {
        $filtered_resources = array();
        foreach ($languages as $language) {
            foreach ($resources as $resource) {
                if ($resource->lang == $language) {
                    $filtered_resources[] = $resource;
                }
            }
        }
        return $filtered_resources;
    }

    /**
     * Get all resources.
     *
     * @return array Array of all resources.
     */
    private static function get_all_resources() : array {
        return Resource::get_all_resources();
    }
}

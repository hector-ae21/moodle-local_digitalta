import Ajax from 'core/ajax';

/**
 * Get all resources.
 *
 * @method getAllResources
 * @return {Promise} Resolve with warnings.
 */
export const getAllResources = () => {
    const request = {
        methodname: 'local_dta_resources_get',
        args: {}
    };
    return Ajax.call([request])[0];
};

/**
 * Upsert a resource.
 *
 * Valid args are:
 * - id: The resource id
 * - name: The resource name
 * - description: The resource description
 * - themes: The resource themes
 * - tags: The resource tags
 * - format: The resource format
 * - type: The resource type
 * - path: The resource path
 * - lang: The resource language
 * @method resourcesUpsert
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const resourcesUpsert = args => {
    const request = {
        methodname: 'local_dta_resources_upsert',
        args: args
    };
    return Ajax.call([request])[0];
};

import Ajax from 'core/ajax';

/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - id: The resource id
 * - name: The resource name
 * - description: The resource description
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
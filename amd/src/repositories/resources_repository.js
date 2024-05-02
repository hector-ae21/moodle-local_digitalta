import Ajax from 'core/ajax';
/**
 * Toggle the status of an experience.
 *
 * Valid args are:
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
import Ajax from 'core/ajax';
/**
 * Toggle the status of an experience.
 *
 * Valid args are:
 * - component: The component name.
 * - componentinstance: The component instance.
 * - modifier: The modifier.
 * - modifierinstance: The modifier instance.
 * @method upsertContext
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const upsertContext = args => {
    const request = {
        methodname: 'local_dta_context_upsert',
        args
    };
    return Ajax.call([request])[0];
};
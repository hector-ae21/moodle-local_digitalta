import Ajax from 'core/ajax';

/**
 * Upsert a context.
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
        methodname: 'local_dta_context_insert',
        args
    };
    return Ajax.call([request])[0];
};


/**
 * Delete a context.
 *
 * Valid args are:
 * - id (int): The context id.
 * @method deleteContext
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const deleteContext = args => {
    const request = {
        methodname: 'local_dta_context_delete',
        args
    };
    return Ajax.call([request])[0];
};

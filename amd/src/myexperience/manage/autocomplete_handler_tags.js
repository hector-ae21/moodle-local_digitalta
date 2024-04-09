define(['core/ajax'], function(Ajax) {
    return {
        // Public variables and functions.
        /**
         * Process the results returned from transport (convert to value + label)
         *
         * @method processResults
         * @param {String} selector
         * @param {Array} data
         * @return {Array}
         */
        processResults: function(selector, data) {
            return data;
        },

        /**
         * Fetch results based on the current query. This also renders each result from a template before returning them.
         *
         * @method transport
         * @param {String} searchText
         * @param {String} query
         * @param {Function} success
         * @param {Function} failure
         */
        transport: function(searchText, query, success, failure) {
            {
                Ajax.call([{
                    methodname: 'local_dta_get_tags',
                    args: {searchText: query},
                    done: function(result) {
                        if (result.length === 0) {
                            success([{label: 'Create: ' + query, value: query}]);
                        } else {
                            const tags = result.map(function(tag) {
                                return {label: tag.name, value: tag.id};
                            });
                            success(tags);
                        }
                    },
                    fail: failure
                }]);

            }
        }
    };
});
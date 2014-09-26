(function (angular) {

    angular.module('FormBuilderGroupHelper', []).factory('$groupHelper', function ($http, $timeout) {
        var helper = {};

        helper.groupIsEmpty = function (pageId, groupId) {
            for (var fieldId in this.entity.layoutOptions.pages[pageId].fields)
                if (typeof this.entity.layoutOptions.pages[pageId].fields[fieldId].parent !== 'undefined')
                    if (this.entity.layoutOptions.pages[pageId].fields[fieldId].parent === groupId)
                        return false;
            return true;
        };

        helper.groupNewFieldset = function () {
        };

        /**
         * Cases to handle
         * 1. Drag form field to group
         * 2. Drag new field to group
         * 3. Drag group to group
         */
        helper.groupOnDrageField = function (toPageId, groupId, fromFieldId, toFieldId) {
            this.entity.layoutOptions.pages[toPageId].groups[groupId].fields[fromFieldId] = {weight: 0};
        };

        return helper;
    });

})(angular);

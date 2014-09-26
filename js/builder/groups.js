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
        helper.groupFieldOnDrop = function ($channel, toPageId, groupId, fromFieldId, toFieldId) {
            switch ($channel) {
                case 'fieldInRoot':
                case 'fieldInGroup':
                    this.groupFieldOnDropField(toPageId, groupId, fromFieldId, toFieldId);
                    break;
                case 'newField':
                    this.groupFieldOnDropNewField(toPageId, groupId, fromFieldId, toFieldId);
            }
        };

        helper.groupFieldOnDropField = function (toPageId, groupId, fromFieldId, toFieldId) {
            // @todo Add field to new page
            if (typeof this.entity.layoutOptions.pages[toPageId].fields[fromFieldId] === 'undefined') {
                this.entity.layoutOptions.pages[toPageId].fields[fromFieldId] = {weight: 0, domTagName: 'div', domClasses: [], parent: null};
            }

            // Set field parent
            this.entity.layoutOptions.pages[toPageId].fields[fromFieldId].parent = groupId;
            this.entity.layoutOptions.pages[toPageId].fields[fromFieldId].weight = 1 + this.entity.layoutOptions.pages[toPageId].fields[toFieldId].weight;

            // Remove field from old page

            // this.entity.layoutOptions.pages[toPageId].groups[groupId].fields[fromFieldId] = {weight: 0};
        }

        return helper;
    });

})(angular);

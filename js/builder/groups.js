(function (angular) {

    angular.module('FormBuilderGroupHelper', []).factory('$groupHelper', function ($http, $timeout) {
        var helper = {};

        helper.groupIsEmpty = function (pageId, groupId) {
            for (var fieldId in this.entity.layoutOptions.pages[pageId].fields)
                if (typeof this.entity.layoutOptions.pages[pageId].fields[fieldId].parent !== 'undefined')
                    if (groupId === this.entity.layoutOptions.pages[pageId].fields[fieldId].parent)
                        return false;
            return true;
        };

        helper.groupFixFieldWeight = function (pageId, groupId) {
            var arr = [];

            for (var fieldId in this.entity.layoutOptions.pages[pageId].fields)
                if (typeof this.entity.layoutOptions.pages[pageId].fields[fieldId].parent !== 'undefined')
                    if (groupId === this.entity.layoutOptions.pages[pageId].fields[fieldId].parent)
                        arr.push({id: fieldId, weight: this.entity.layoutOptions.pages[pageId].fields[fieldId].weight});

            arr.sort(function (a, b) {
                return a.weight - b.weight;
            });

            for (var i in arr) {
                var fieldId = arr[i].id;
                this.entity.layoutOptions.pages[pageId].fields[fieldId].weight = 2 * i;
            }
        };

        helper.groupNewFieldset = function () {
        };

        /**
         * Cases to handle
         * 1. Drag form field to group
         * 2. Drag new field to group
         * 3. Drag group to group
         */
        helper.groupFieldOnDrop = function ($channel, $data, toPageId, groupId, toFieldId) {
            this.groupFixFieldWeight(toPageId, groupId);

            switch ($channel) {
                case 'fieldInRoot':
                case 'fieldInGroup':
                    var fromFieldId = $data.fieldInfo.uuid;
                    this.groupFieldOnDropField(toPageId, groupId, fromFieldId, toFieldId);
                    break;
                case 'newField':
                    this.groupFieldOnDropNewField($data, toPageId, groupId, fromFieldId, toFieldId);
            }
        };

        helper.groupFieldOnDropField = function (toPageId, groupId, fromFieldId, toFieldId) {
            // @todo If user moves field from other page
            //  - add field to new page
            //  - remove field from old page
            if (typeof this.entity.layoutOptions.pages[toPageId].fields[fromFieldId] === 'undefined') {
                this.entity.layoutOptions.pages[toPageId].fields[fromFieldId] = {weight: 0, domTagName: 'div', domClasses: [], parent: null};
            }

            // Update field parent & weight
            this.entity.layoutOptions.pages[toPageId].fields[fromFieldId].parent = groupId;
            this.entity.layoutOptions.pages[toPageId].fields[fromFieldId].weight = 'undefined' === typeof this.entity.layoutOptions.pages[toPageId].fields[toFieldId]
                    ? 0
                    : 1 + this.entity.layoutOptions.pages[toPageId].fields[toFieldId].weight;
        };

        helper.groupFieldOnDropNewField = function (fieldInfo, toPageId, groupId, fromFieldId, toFieldId) {
            this.fieldOnDropAddField(this, toPageId, toFieldId, fieldInfo);
        };

        return helper;
    });

})(angular);

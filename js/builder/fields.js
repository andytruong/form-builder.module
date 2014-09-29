(function (angular) {

    angular.module('FormBuilderFieldHelper', []).factory('$fieldHelper', function ($http) {
        var helper = {};

        helper.fieldRemove = function (pageUuid, fieldUuid) {
            delete(this.entity.fields[fieldUuid]);
            delete(this.entity.layoutOptions.pages[pageUuid]['fields'][fieldUuid]);

            if (typeof this.entity.layoutOptions.pages[pageUuid].groups === 'undefined')
                return;

            // Remove field config in a group
            for (var groupUuid in this.entity.layoutOptions.pages[pageUuid].groups)
                if (typeof this.entity.layoutOptions.pages[pageUuid].groups[groupUuid].fields[fieldUuid] !== 'undefined')
                    delete(this.entity.layoutOptions.pages[pageUuid].groups[groupUuid].fields[fieldUuid]);
        };

        helper.isAvailableFieldsEmpty = function (entityTypeName) {
            this.available.entityTypes[entityTypeName].fields = this.available.entityTypes[entityTypeName].fields || {};
            return angular.equals({}, this.available.entityTypes[entityTypeName].fields);
        };

        helper.isFieldsEmpty = function (pageUuid) {
            return 0 === this.pageStack[pageUuid].length;
        };

        helper.fieldDragValidate = function ($channel, $data) {
            return ('newField' === $channel)
                    || ('groupInRoot' === $channel)
                    || ('fieldInRoot' === $channel)
                    || ('fieldInGroup' === $channel);
        };

        // ---------------------
        // Field: Field dragging
        // ---------------------
        helper.fieldOnDrop = function ($channel, $data, baseFieldUuid, toPageId, increase) {
            var $scope = this;
            var fieldId = $data.itemInfo.uuid;
            var changePage = true;

            if ('newField' === $channel)
                return helper.fieldOnDropAddField($scope, toPageId, baseFieldUuid, $data.itemInfo, increase);

            // user changes a field to other page
            angular.forEach($scope.pageStack[toPageId], function (pageField) {
                if (pageField.uuid === fieldId)
                    changePage = false;
            });

            changePage
                    ? helper.fieldOnDropChangePage($scope, toPageId, baseFieldUuid, fieldId, increase)
                    : helper.fieldOnDropChangeWeight($scope, toPageId, baseFieldUuid, fieldId, increase);

            // User change field from group to root
            if ('fieldInGroup' === $channel) {
                // change field parent
                $scope.entity.layoutOptions.pages[toPageId].fields[fieldId].parent = null;
            }
        };

        // ---------------------
        // Field: Adding new field — user drag from available fields to page
        // ---------------------
        helper.fieldOnDropAddField = function ($scope, pageUuid, baseFieldUuid, fieldInfo, increase) {
            var fieldName = fieldInfo.entityTypeName + '.' + fieldInfo.name;
            $scope.available.addingFields[pageUuid] = $scope.available.addingFields[pageUuid] || {};
            $scope.available.addingFields[pageUuid][fieldName] = fieldInfo;

            $http
                    .post(window.location.pathname, {action: 'add-field', fieldName: fieldName, entity: $scope.entity})
                    .success(function (data) {
                        var fieldName = data.field.entityTypeName + '.' + data.field.name;
                        var weight = baseFieldUuid ? increase + $scope.entity.layoutOptions.pages[pageUuid].fields[baseFieldUuid].weight : 0;

                        fieldInfo.uuid = data.fieldUuid;

                        $scope.entity.fields[data.fieldUuid] = data.field;
                        $scope.available.addedFields[data.fieldUuid] = $scope.available.addingFields[fieldName];
                        $scope.pageStack[pageUuid].push(fieldInfo);
                        $scope.entity.layoutOptions.pages[pageUuid].fields[data.fieldUuid] = {
                            weight: weight,
                            domTagName: 'div',
                            domClasses: [],
                            parent: $scope.entity.layoutOptions.pages[pageUuid].fields[baseFieldUuid].parent
                        };
                        delete($scope.available.addingFields[pageUuid][fieldName]);
                    });
        };

        // ---------------------
        // Field: Change position of field inside a page.
        // ---------------------
        helper.fieldOnDropChangeWeight = function ($scope, pageUuid, baseFieldUuid, fieldUuid, increase) {
            var baseFieldKey, fieldKey;
            for (var key in $scope.pageStack[pageUuid])
                if (fieldUuid === $scope.pageStack[pageUuid][key].uuid)
                    fieldKey = key;

            for (var key in $scope.pageStack[pageUuid])
                if (baseFieldUuid === $scope.pageStack[pageUuid][key].uuid)
                    baseFieldKey = key;

            $scope.pageStack[pageUuid][fieldKey].weight = increase + $scope.pageStack[pageUuid][baseFieldKey].weight;             // Change field weights for next move
            $scope.pageStack[pageUuid].sort(function (a, b) {
                return a.weight - b.weight;
            });
            for (var i in $scope.pageStack[pageUuid])
                $scope.pageStack[pageUuid][i].weight = i * 2;
        };

        // ---------------------
        // Field: User move a field to other page
        // ---------------------
        helper.fieldOnDropChangePage = function ($scope, toPageUuid, baseFieldUuid, fieldUuid) {
            var fromPageUuid;
            for (var uuidPage in $scope.entity.layoutOptions.pages)
                for (var uuidField in $scope.entity.layoutOptions.pages[uuidPage].fields)
                    if (fieldUuid === uuidField)
                        fromPageUuid = uuidPage;

            // get field's config
            var fieldConfig = angular.copy($scope.entity.layoutOptions.pages[fromPageUuid].fields[fieldUuid]);

            // remove field's config from old page
            delete($scope.entity.layoutOptions.pages[fromPageUuid].fields[fieldUuid]);

            // copy config to new page
            $scope.entity.layoutOptions.pages[toPageUuid].fields[fieldUuid] = fieldConfig;

            return $scope;
        };
        return helper;
    });

})(angular);

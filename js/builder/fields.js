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
                    || ('fieldInRoot' === $channel)
                    || ('fieldInGroup' === $channel);
        };

        // ---------------------
        // Field: Field dragging
        // ---------------------
        helper.fieldOnDrop = function ($channel, field, baseFieldUuid, pageUuid) {
            var $scope = this;
            var fieldName = 'object' === typeof field ? field.entityTypeName + '.' + field.name : field;
            var changePage = true;

            if ('newField' === $channel)
                return helper.fieldOnDropAddField($scope, pageUuid, baseFieldUuid, fieldName, field);

            // use change a field to other page
            angular.forEach($scope.pageStack[pageUuid], function (pageField) {
                if (pageField.uuid === fieldName)
                    changePage = false;
            });

            changePage
                    ? helper.fieldOnDropChangePage($scope, pageUuid, baseFieldUuid, fieldName)
                    : helper.fieldOnDropChangeWeight($scope, pageUuid, baseFieldUuid, fieldName);
        };

        // ---------------------
        // Field: Adding new field — user drag from available fields to page
        // ---------------------
        helper.fieldOnDropAddField = function ($scope, pageUuid, baseFieldUuid, fieldName, field) {
            $scope.available.addingFields[pageUuid] = $scope.available.addingFields[pageUuid] || {};
            $scope.available.addingFields[pageUuid][fieldName] = field;

            $http
                    .post(window.location.pathname, {
                        action: 'add-field',
                        fieldName: fieldName,
                        entity: $scope.entity
                    })
                    .success(function (data) {
                        var fieldName = data.field.entityTypeName + '.' + data.field.name;
                        var weight = baseFieldUuid ? 1 + $scope.entity.layoutOptions.pages[pageUuid].fields[baseFieldUuid].weight : 0;

                        field.uuid = data.fieldUuid;

                        $scope.entity.fields[data.fieldUuid] = data.field;
                        $scope.available.addedFields[data.fieldUuid] = $scope.available.addingFields[fieldName];
                        $scope.pageStack[pageUuid].push(field);
                        $scope.entity.layoutOptions.pages[pageUuid].fields[data.fieldUuid] = {weight: weight, domTagName: 'div', domClasses: [], parent: null};
                        delete($scope.available.addingFields[pageUuid][fieldName]);
                    });
        };

        // ---------------------
        // Field: Change position of field inside a page.
        // ---------------------
        helper.fieldOnDropChangeWeight = function ($scope, pageUuid, baseFieldUuid, fieldUuid) {
            var baseFieldKey, fieldKey;
            for (var key in $scope.pageStack[pageUuid])
                if (fieldUuid === $scope.pageStack[pageUuid][key].uuid)
                    fieldKey = key;

            for (var key in $scope.pageStack[pageUuid])
                if (baseFieldUuid === $scope.pageStack[pageUuid][key].uuid)
                    baseFieldKey = key;

            $scope.pageStack[pageUuid][fieldKey].weight = 1 + $scope.pageStack[pageUuid][baseFieldKey].weight;             // Change field weights for next move
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

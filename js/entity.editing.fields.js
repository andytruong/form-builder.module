(function (angular) {

    angular.module('FormBuilderFieldHelper', []).factory('$fieldHelper', function ($http) {
        var helper = {};

        helper.fieldRemove = function (pageUuid, fieldUuid) {
            var field = this.entity.fields[fieldUuid];
            this.available.entityTypes[field.entityTypeName].fields[field.name] = field;

            delete(this.entity.fields[fieldUuid]);
            delete(this.entity.layoutOptions[pageUuid]['fields'][fieldUuid]);
        };

        helper.isAvailableFieldsEmpty = function (entityTypeName) {
            this.available.entityTypes[entityTypeName].fields = this.available.entityTypes[entityTypeName].fields || {};
            return angular.equals({}, this.available.entityTypes[entityTypeName].fields);
        };

        helper.isFieldsEmpty = function (pageUuid) {
            return 0 === this.pageFields[pageUuid].length;
        };

        // ---------------------
        // Field: Field dragging
        // ---------------------
        helper.fieldOnDrop = function ($event, field, baseFieldUuid, pageUuid) {
            var $scope = this;
            var fieldName = 'object' === typeof field
                    ? field.entityTypeName + '.' + field.name
                    : field;

            // add field
            if (!fieldName.match(/^.+-.+-.+-.+$/))
                return helper.fieldOnDropAddField($scope, pageUuid, baseFieldUuid, fieldName, field);

            // use change a field to other page
            $scope.changePage = true;
            angular.forEach($scope.pageFields[pageUuid], function (pageField) {
                if (pageField.uuid === fieldName)
                    $scope.changePage = false;
            });

            $scope.changePage
                    ? helper.fieldOnDropChangePage($scope, pageUuid, baseFieldUuid, fieldName)
                    : helper.fieldOnDropChangeWeight($scope, pageUuid, baseFieldUuid, fieldName);
        };

        // ---------------------
        // Field: Adding new field — user drag from available fields to page
        // ---------------------
        helper.fieldOnDropAddField = function ($scope, pageUuid, baseFieldUuid, fieldName, field) {
            $scope.available.addingFields[pageUuid] = $scope.available.addingFields[pageUuid] || {};
            $scope.available.addingFields[pageUuid][fieldName] = field;
            delete($scope.available.entityTypes[field.entityTypeName].fields[field.name]);

            $http
                    .post(window.location.pathname, {
                        action: 'add-field',
                        fieldName: fieldName,
                        entity: $scope.entity
                    })
                    .success(function (data) {
                        var fieldName = data.field.entityTypeName + '.' + data.field.name;
                        var weight = baseFieldUuid ? 1 + $scope.entity.layoutOptions[pageUuid].fields[baseFieldUuid].weight : 0;

                        field.uuid = data.fieldUuid;

                        $scope.entity.fields[data.fieldUuid] = data.field;
                        $scope.available.addedFields[data.fieldUuid] = $scope.available.addingFields[fieldName];
                        $scope.pageFields[pageUuid].push(field);
                        $scope.entity.layoutOptions[pageUuid].fields[data.fieldUuid] = {weight: weight, domTagName: 'div', domClasses: []};
                        delete($scope.available.addingFields[pageUuid][fieldName]);
                    });
        };

        // ---------------------
        // Field: Change position of field inside a page.
        // ---------------------
        helper.fieldOnDropChangeWeight = function ($scope, pageUuid, baseFieldUuid, fieldUuid) {
            var baseFieldKey, fieldKey;

            for (var key in $scope.pageFields[pageUuid])
                if (fieldUuid === $scope.pageFields[pageUuid][key].uuid)
                    fieldKey = key;

            for (var key in $scope.pageFields[pageUuid])
                if (baseFieldUuid === $scope.pageFields[pageUuid][key].uuid)
                    baseFieldKey = key;

            $scope.pageFields[pageUuid][fieldKey].weight = 1 + $scope.pageFields[pageUuid][baseFieldKey].weight;
            // Change field weights for next move
            $scope.pageFields[pageUuid].sort(function (a, b) {
                return a.weight - b.weight;
            });
            for (var i in $scope.pageFields[pageUuid])
                $scope.pageFields[pageUuid][i].weight = i * 2;
        };

        // ---------------------
        // Field: User move a field to other page
        // ---------------------
        helper.fieldOnDropChangePage = function ($scope, toPageUuid, baseFieldUuid, fieldUuid) {
            var fromPageUuid;
            for (var uuidPage in $scope.entity.layoutOptions)
                for (var uuidField in $scope.entity.layoutOptions[uuidPage].fields)
                    if (fieldUuid === uuidField)
                        fromPageUuid = uuidPage;

            // get field's config
            var fieldConfig = angular.copy($scope.entity.layoutOptions[fromPageUuid].fields[fieldUuid]);

            // remove field's config from old page
            delete($scope.entity.layoutOptions[fromPageUuid].fields[fieldUuid]);

            // copy config to new page
            $scope.entity.layoutOptions[toPageUuid].fields[fieldUuid] = fieldConfig;

            return $scope;
        };

        return helper;
    });

})(angular);

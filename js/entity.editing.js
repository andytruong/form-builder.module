(function (angular, $, Drupal) {
    function toggleEntityType($http, $scope, entityTypeName) {
        // Remove fields. @TODO: Confirm
        if (true === $scope.entity.entityTypes[entityTypeName]) {
            for (var i in $scope.available.fields)
                if (entityTypeName === $scope.available.fields[i].entityTypeName)
                    delete($scope.available.fields[i]);

            for (var i in $scope.entity.fields)
                if (entityTypeName === $scope.entity.fields[i].entityTypeName)
                    delete($scope.entity.fields[i]);
        }
        else {
            $scope.available.addingEntityTypeNames[entityTypeName] = true;

            $http
                    .post(window.location.pathname, {
                        action: 'addEntityType',
                        entityTypeName: entityTypeName,
                        entity: $scope.entity
                    })
                    .success(function (data) {
                        delete($scope.available.addingEntityTypeNames[entityTypeName]);
                        for (var name in data.entityTypeFields)
                            $scope.available.fields[name] = data.entityTypeFields[name];
                    });
        }
    }

    function fieldOnDrop($http, $scope, $timeout, fieldName, currentFieldUuid) {
        var addField = function () {
            $scope.available.addingFields[fieldName] = $scope.available.fields[fieldName];
            delete($scope.available.fields[fieldName]);

            $http
                    .post(window.location.pathname, {
                        action: 'add-field',
                        fieldName: fieldName,
                        entity: $scope.entity
                    })
                    .success(function (data) {
                        var fieldName = data.field.entityTypeName + '.' + data.field.name;
                        $scope.entity.fields[data.fieldUuid] = data.field;
                        $scope.available.addedFields[data.fieldUuid] = $scope.available.addingFields[fieldName];
                        delete($scope.available.addingFields[fieldName]);
                    });
        };

        var changeWeight = function () { // move field after currentField
            var fieldUuid = fieldName;
            var weight = $scope.entity.fields[currentFieldUuid].weight;
            $scope.entity.fields[fieldUuid].weight = weight + 1;

            $timeout(function () {
                $scope.uiFormFields.sort(function (a, b) {
                    return a.weight - b.weight;
                });

                angular.forEach($scope.uiFormFields, function (field, i) {
                    $scope.uiFormFields[i].weight
                            = $scope.entity.fields[field.uuid].weight
                            = i * 2;
                });
            }, 100);
        };

        // when fieldName is an uuid value, change weight of field instead of adding field
        fieldName.match(/^.+-.+-.+-.+$/) ? changeWeight() : addField();
    }

    function fieldRemove($scope, fieldUuid) {
        var field = $scope.entity.fields[fieldUuid];
        var fieldName = field.entityTypeName + '.' + field.name;
        $scope.available.fields[fieldName] = field;
        delete($scope.entity.fields[fieldUuid]);
    }

    function formSubmit($http, $scope) {
        $scope.saving = true;
        $http
                .post(window.location.pathname, {
                    action: 'save',
                    entity: $scope.entity
                })
                .success(function (data) {
                    $scope.saving = false;
                    console.log(data);
                });
    }

    angular.module('fob_entity_edit', ['ngDragDrop'])
            .controller('FormBuilderForm', function ($http, $scope, $timeout) {
                $scope.available = Drupal.settings.FormBuilder.available;
                $scope.available.addingEntityTypeNames = {};
                $scope.available.addingFields = {};
                $scope.available.addedFields = {};
                $scope.entity = Drupal.settings.FormBuilder.entity;
                $scope.slugDoAuto = true;

                $scope.slugAuto = function () {
                    if (!$scope.slugDoAuto)
                        return;

                    $scope.entity.slug = $scope.entity.title
                            .toLowerCase()
                            .replace(new RegExp('[^a-z0-9_]+', 'g'), '-')
                            .substr(0, 255);
                };

                $scope.slugDisableAuto = function () {
                    $scope.slugDoAuto = false;
                };

                // if empty, $scope.entity.fields is array!
                if ($scope.entity.fields instanceof Array)
                    $scope.entity.fields = {};

                $scope.uiFormFields = [];
                $scope.$watchCollection('entity.fields', function (items) {
                    $scope.uiFormFields.length = 0;
                    angular.forEach(items, function (value, key) {
                        value.uuid = key;
                        $scope.uiFormFields.push(value);
                    });
                });

                $scope.toggleEntityType = function (entityTypeName) {
                    toggleEntityType($http, $scope, entityTypeName);
                };

                $scope.isAvailableFieldsEmpty = function () {
                    return angular.equals({}, $scope.available.fields);
                };

                $scope.isFieldsEmpty = function () {
                    return angular.equals({}, $scope.entity.fields) && angular.equals({}, $scope.available.addingFields);
                };

                // Drag field from available fields to form fields.
                $scope.fieldOnDrop = function ($event, fieldName, curentFieldUuid) {
                    fieldOnDrop($http, $scope, $timeout, fieldName, curentFieldUuid);
                };

                // Remove a field from form fields
                $scope.fieldRemove = function (fieldUuid) {
                    fieldRemove($scope, fieldUuid);
                };

                // On form submit
                $scope.submit = function () {
                    formSubmit($http, $scope);
                };
            });

})(angular, jQuery, Drupal);

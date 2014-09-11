(function (angular, $, Drupal) {

    angular.module('fob_entity_edit', ['ngDragDrop'])
            .controller('HelloCtrl', function ($http, $scope, $timeout) {
                $scope.available = Drupal.settings.FormBuilder.available;
                $scope.available.addingEntityTypeNames = {};
                $scope.available.addingFields = {};
                $scope.available.addedFields = {};
                $scope.entity = Drupal.settings.FormBuilder.entity;

                if ($scope.entity.fields instanceof Array)
                    $scope.entity.fields = {};

                $scope.toggleEntityType = function (entityTypeName) {
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
                        console.log('Adding ' + entityTypeName);
                    }
                };

                $scope.isAvailableFieldsEmpty = function () {
                    return angular.equals({}, $scope.available.fields);
                };

                $scope.isFieldsEmpty = function () {
                    return angular.equals({}, $scope.entity.fields)
                            && angular.equals({}, $scope.available.addingFields);
                };

                // Drag field from available fields to form fields.
                $scope.onDrop = function ($event, fieldName) {
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

                // Remove a field from form fields
                $scope.removeField = function (fieldUuid) {
                    var field = $scope.entity.fields[fieldUuid];
                    var fieldName = field.entityTypeName + '.' + field.name;
                    $scope.available.fields[fieldName] = field;
                    delete($scope.entity.fields[fieldUuid]);
                };

                // On form submit
                $scope.submit = function () {
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
                };
            });

})(angular, jQuery, Drupal);

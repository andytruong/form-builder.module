(function (angular, $, Drupal) {

    angular.module('fob_entity_edit', ['ngDragDrop'])
            .controller('HelloCtrl', function ($http, $scope) {
                $scope.available = Drupal.settings.FormBuilder.available;
                $scope.available.addingFields = {};
                $scope.available.addedFields = {};
                $scope.entity = Drupal.settings.FormBuilder.entity;

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

(function (angular, $, Drupal) {

    angular.module('fob_entity_edit', ['ngDragDrop'])
            .controller('HelloCtrl', function ($http, $scope) {
                $scope.available = Drupal.settings.FormBuilder.available;
                $scope.available.addingFields = {};
                $scope.available.addedFields = {};
                $scope.entity = Drupal.settings.FormBuilder.entity;

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
                                $scope.entity.fields[data.fieldUuid] = data.field;
                                delete($scope.available.addingFields[data.field.entityTypeName + '.' + data.field.fieldName]);
                            });
                };

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

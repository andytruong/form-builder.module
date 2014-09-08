(function (angular, Drupal) {

    angular.module('fob_entity_edit', [])
            .controller('HelloCtrl', function ($http, $scope) {
                $scope.available = Drupal.settings.FormBuilder.available;
                $scope.entity = Drupal.settings.FormBuilder.entity;
                $scope.submit = function () {
                    console.log(123);
                    $http
                            .post(window.location.pathname, {
                                entity: JSON.stringify($scope.entity)
                            })
                            .success(function (data) {
                                console.log(data);
                            });
                };
            });

})(angular, Drupal);

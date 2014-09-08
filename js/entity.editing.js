(function (angular, Drupal) {

    angular.module('fob_entity_edit', [])
            .controller('HelloCtrl', function ($scope) {
                $scope.available = Drupal.settings.FormBuilder.available;
                $scope.entity = Drupal.settings.FormBuilder.entity;
            });

})(angular, Drupal);

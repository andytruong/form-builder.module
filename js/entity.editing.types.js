(function (angular, Drupal) {
    angular.module('FormBuilderEntityTypeHelper', []).factory('$entityTypeHelper', function ($http) {
        helper = {};

        /**
         * User enable/disale an entity type.
         */
        helper.entityTypeToggle = function (entityTypeName) {
            var $scope = this;

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
        };

        return helper;
    });
})(angular, Drupal);

(function (angular) {

    angular.module('FormBuilderEntityTypeHelper', []).factory('$entityTypeHelper', function ($http) {
        helper = {};

        helper.isFieldAdded = function (entityTypeName, fieldName) {
            for (var fieldUuid in this.entity.fields)
                if (fieldName === this.entity.fields[fieldUuid].name && entityTypeName === this.entity.fields[fieldUuid].entityTypeName)
                    return true;
            return false;
        };

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
                var post = {action: 'addEntityType', entityTypeName: entityTypeName, entity: $scope.entity};
                var done = function (data) {
                    delete($scope.available.addingEntityTypeNames[entityTypeName]);
                    for (var name in data.entityTypeFields)
                        $scope.available.fields[entityTypeName][name] = data.entityTypeFields[name];
                };

                // $http.post(window.location.pathname, post).success(done);
                delete($scope.available.addingEntityTypeNames[entityTypeName]);
            }
        };

        return helper;
    });

})(angular);

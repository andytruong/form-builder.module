(function (angular) {

    angular.module('FormBuilderEntityTypeHelper', []).factory('$entityTypeHelper', function ($http) {
        var helper = {};

        helper.isFieldAdded = function (entityTypeName, fieldName) {
            for (var fieldUuid in this.entity.fields)
                if (fieldName === this.entity.fields[fieldUuid].name && entityTypeName === this.entity.fields[fieldUuid].entityTypeName)
                    return true;
            return false;
        };

        helper.entityTypeAdd = function (entityTypeName) {
            var $scope = this;

            $scope.available.addingEntityTypeNames[entityTypeName] = true;
            $scope.entity.entityTypes[entityTypeName] = true;

            // Currently, we loaded all entity types, fields to single page.
            // In real app, this amount maybe very big. In that case, we wil
            // contact server for every user select adding new entity type.
            // var post = {action: 'addEntityType', entityTypeName: entityTypeName, entity: $scope.entity};
            // var done = function (data) {
            //     delete($scope.available.addingEntityTypeNames[entityTypeName]);
            //     for (var name in data.entityTypeFields)
            //         $scope.available.fields[entityTypeName][name] = data.entityTypeFields[name];
            // };
            // $http.post(window.location.pathname, post).success(done);

            delete($scope.available.addingEntityTypeNames[entityTypeName]);
        };

        helper.entityTypeRemove = function (entityTypeName) {
            this.entity.entityTypes[entityTypeName] = false;

            for (var i in this.available.fields)
                if (entityTypeName === this.available.fields[i].entityTypeName)
                    delete(this.available.fields[i]);

            for (var i in this.entity.fields)
                if (entityTypeName === this.entity.fields[i].entityTypeName)
                    delete(this.entity.fields[i]);
        };

        return helper;
    });

})(angular);

(function (angular) {
    angular.module('FormBuilderFieldHelper', []).factory('$fieldHelper', function ($http) {
        var helper = {};

        helper.fieldRemove = function (pageUuid, fieldUuid) {
            var field = this.entity.fields[fieldUuid];
            var fieldName = field.entityTypeName + '.' + field.name;
            this.available.fields[fieldName] = field;
            delete(this.entity.fields[fieldUuid]);
            delete(this.entity.layoutOptions[pageUuid]['fields'][fieldUuid]);
        };

        helper.isAvailableFieldsEmpty = function () {
            return angular.equals({}, this.available.fields);
        };

        helper.isFieldsEmpty = function (pageUuid) {
            return 0 === this.pageFields[pageUuid].length;
        };

        // ---------------------
        // Field: Drag field from available fields to form fields.
        // ---------------------
        helper.fieldOnDrop = function ($event, fieldName, baseFieldUuid, pageUuid) {
            var $scope = this;
            var addField = function () {
                $scope.available.addingFields[pageUuid] = $scope.available.addingFields[pageUuid] || {};
                $scope.available.addingFields[pageUuid][fieldName] = $scope.available.fields[fieldName];
                delete($scope.available.fields[fieldName]);

                $http
                        .post(window.location.pathname, {
                            action: 'add-field',
                            fieldName: fieldName,
                            entity: $scope.entity
                        })
                        .success(function (data) {
                            var fieldName = data.field.entityTypeName + '.' + data.field.name;
                            var weight = baseFieldUuid ? 1 + $scope.entity.layoutOptions[pageUuid].fields[baseFieldUuid].weight : 0;

                            $scope.entity.fields[data.fieldUuid] = data.field;
                            $scope.available.addedFields[data.fieldUuid] = $scope.available.addingFields[fieldName];
                            $scope.entity.layoutOptions[pageUuid].fields[data.fieldUuid] = {weight: weight, domTagName: 'div', domClasses: []};
                            delete($scope.available.addingFields[pageUuid][fieldName]);
                        });
            };
            var changeWeight = function () { // move field after currentField
                var fieldUuid = fieldName;
                var baseFieldKey, fieldKey;
                for (var key in $scope.pageFields[pageUuid])
                    if (fieldUuid === $scope.pageFields[pageUuid][key].uuid) {
                        fieldKey = key;
                        break;
                    }

                for (var key in $scope.pageFields[pageUuid])
                    if (baseFieldUuid === $scope.pageFields[pageUuid][key].uuid) {
                        baseFieldKey = key;
                        break;
                    }

                $scope.pageFields[pageUuid][fieldKey].weight = 1 + $scope.pageFields[pageUuid][baseFieldKey].weight;
                // Change field weights for next move
                $scope.pageFields[pageUuid].sort(function (a, b) {
                    return a.weight - b.weight;
                });
                for (var i in $scope.pageFields[pageUuid])
                    $scope.pageFields[pageUuid][i].weight = i * 2;
            };
            // when fieldName is an uuid value, change weight of field instead of adding field
            fieldName.match(/^.+-.+-.+-.+$/) ? changeWeight() : addField();
        };

        // ---------------------
        // Create new page
        // ---------------------
        helper.newPageClick = function () {
            var $scope = this;
            $scope.newPageAdding = true;
            $http
                    .post(window.location.pathname, {
                        action: 'addPage',
                        entity: $scope.entity
                    })
                    .success(function (data) {
                        $scope.newPageAdding = false;
                        $scope.entity.layoutOptions[data.pageUuid] = {
                            title: $scope.newPageTitle,
                            description: '',
                            weight: 1,
                            fields: {}
                        };
                        $scope.newPageTitle = '';
                    });
        };

        return helper;
    });

})(angular);

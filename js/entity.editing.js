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

    function fieldOnDrop($http, $scope, $timeout, fieldName, baseFieldUuid, pageUuid) {
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
    }

    function fieldRemove($scope, pageUuid, fieldUuid) {
        var field = $scope.entity.fields[fieldUuid];
        var fieldName = field.entityTypeName + '.' + field.name;
        $scope.available.fields[fieldName] = field;
        delete($scope.entity.fields[fieldUuid]);
        delete($scope.entity.layoutOptions[pageUuid]['fields'][fieldUuid]);
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
                // Layout options -> fields
                $scope.pageFields = {};
                $scope.$watch('entity.layoutOptions', function (layoutOptions) {
                    $scope.pageFields = {};
                    angular.forEach(layoutOptions, function (pageInfo, pageUuid) {
                        $scope.pageFields[pageUuid] = [];
                        angular.forEach(pageInfo.fields, function (fieldInfo, fieldUuid) {
                            fieldInfo.uuid = fieldUuid;
                            $scope.pageFields[pageUuid].push(fieldInfo);
                        });
                    });
                }, true);
                // !-- Layout options -> fields

                // New page
                $scope.newPageTitle = '';
                $scope.newPageAdding = false;
                $scope.newPageClick = function () {
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

                $scope.toggleEntityType = function (entityTypeName) {
                    toggleEntityType($http, $scope, entityTypeName);
                };
                $scope.isAvailableFieldsEmpty = function () {
                    return angular.equals({}, $scope.available.fields);
                };
                $scope.isFieldsEmpty = function (pageUuid) {
                    return 0 === $scope.pageFields[pageUuid].length;
                };
                // Drag field from available fields to form fields.
                $scope.fieldOnDrop = function ($event, fieldName, curentFieldUuid, pageUuid) {
                    fieldOnDrop($http, $scope, $timeout, fieldName, curentFieldUuid, pageUuid);
                };
                // Remove a field from form fields
                $scope.fieldRemove = function (pageUuid, fieldUuid) {
                    fieldRemove($scope, pageUuid, fieldUuid);
                };
                // On form submit
                $scope.submit = function () {
                    formSubmit($http, $scope);
                };
            });
})(angular, jQuery, Drupal);

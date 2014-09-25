(function (angular, Drupal) {
    var module = angular.module('FormBuilderApp', ['ngDragDrop', 'FormBuilderPageHelper', 'FormBuilderFieldHelper', 'FormBuilderEntityTypeHelper', 'FormBuilderFormHelper']);

    module.factory('$initState', function () {
        var initState = {
            available: Drupal.settings.FormBuilder.available,
            entity: Drupal.settings.FormBuilder.entity,
            newPageTitle: '',
            newPageAdding: false
        };

        angular.extend(initState.available, {
            addingEntityTypeNames: {},
            addingFields: {},
            addedFields: {}
        });

        if (initState.entity.layoutOptions.pages instanceof Array)
            initState.entity.layoutOptions.pages = {};

        for (var pageUuid in initState.entity.layoutOptions.pages)
            if (initState.entity.layoutOptions.pages[pageUuid].fields instanceof Array)
                initState.entity.layoutOptions.pages[pageUuid].fields = {};

        // if empty, $scope.entity.fields is array!
        if (initState.entity.fields instanceof Array)
            initState.entity.fields = {};

        return initState;
    });

    module.factory('$helpers', function ($initState, $pageHelper, $fieldHelper, $entityTypeHelper, $formHelper) {
        var helpers = $initState;

        angular.extend(helpers, $pageHelper);
        angular.extend(helpers, $fieldHelper);
        angular.extend(helpers, $entityTypeHelper);
        angular.extend(helpers, $formHelper);

        return helpers;
    });

    module.filter('toArray', function () {
        return function (input) {
            var arr = [];
            for (var i in input)
                arr.push(input[i]);
            return arr;
        };
    });

    module.controller('FormBuilderForm', function ($scope, $helpers) {
        angular.extend($scope, $helpers);

        $scope.$watch('entity.layoutOptions.pages', function (pages) {
            $scope.pages = [];
            $scope.pageStack = {};

            angular.forEach(pages, function (pageInfo, pageUuid) {
                // Add page to render array
                $scope.pages.push({uuid: pageUuid, weight: parseInt(pageInfo.weight)});

                // ---------------------
                // build field/group render array
                // ---------------------
                $scope.pageStack[pageUuid] = [];

                // add fields to render array
                angular.forEach(pageInfo.fields, function (fieldInfo, fieldUuid) {
                    fieldInfo.uuid = fieldUuid;
                    $scope.pageStack[pageUuid].push(fieldInfo);
                });
            });
        }, true);

        // @todo: This shoud be handler better, but I got stuck at ng-change, …
        // On add new entity-type
        $scope.$watch('entityTypeAdding', function (entityTypeName) {
            if (!entityTypeName)
                return;
            if (!$scope.entity.entityTypes[entityTypeName])
                return $scope.entityTypeAdd(entityTypeName);
            else if (confirm('Are you sure to remove ' + entityTypeName))
                return $scope.entityTypeRemove(entityTypeName);
            $scope.entityTypeAdding = '';
        });
    });

})(angular, Drupal);

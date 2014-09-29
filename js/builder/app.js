(function (angular, Drupal) {
    var module = angular.module('FormBuilderApp', ['ngDragDrop', 'FormBuilderPageHelper', 'FormBuilderFieldHelper', 'FormBuilderGroupHelper', 'FormBuilderEntityTypeHelper', 'FormBuilderFormHelper']);

    module.factory('$initState', function () {
        var initState = {
            available: Drupal.settings.FormBuilder.available,
            entity: Drupal.settings.FormBuilder.entity,
            newPageTitle: '',
            newPageAdding: false
        };

        angular.extend(initState.available, {addingEntityTypeNames: {}, addingFields: {}, addedFields: {}});

        if (initState.entity.layoutOptions.pages instanceof Array)
            initState.entity.layoutOptions.pages = {};

        for (var pageId in initState.entity.layoutOptions.pages)
            if (initState.entity.layoutOptions.pages[pageId].fields instanceof Array)
                initState.entity.layoutOptions.pages[pageId].fields = {};

        // if empty, $scope.entity.fields is array!
        if (initState.entity.fields instanceof Array)
            initState.entity.fields = {};

        return initState;
    });

    module.directive('pageStack', function () {
        var path = Drupal.settings.basePath + Drupal.settings.FormBuilder.modulePath + '/templates/form.edit/stack.html';
        return {restrict: 'C', templateUrl: path, link: function (scope) {
                if (typeof scope.parent === 'undefined')
                    scope.parent = null;
            }};
    });

    module.directive('groupConfig', function () {
        var path = Drupal.settings.basePath + Drupal.settings.FormBuilder.modulePath + '/templates/form.edit/group.config.html';
        return {restrict: 'C', templateUrl: path};
    });

    module.factory('$helpers', function ($initState, $pageHelper, $fieldHelper, $groupHelper, $entityTypeHelper, $formHelper) {
        var helpers = $initState;

        angular.extend(helpers, $pageHelper);
        angular.extend(helpers, $fieldHelper);
        angular.extend(helpers, $groupHelper);
        angular.extend(helpers, $entityTypeHelper);
        angular.extend(helpers, $formHelper);

        return helpers;
    });

    module.filter('toArray', function () {
        return function (input) {
            var arr = [];
            for (var i in input) {
                input[i].ngKEY = i;
                arr.push(input[i]);
            }
            return arr;
        };
    });

    module.controller('FormBuilderForm', function ($scope, $helpers) {
        angular.extend($scope, $helpers);

        $scope.$watch('entity.layoutOptions.pages', function (pages) {
            $scope.pages = [];
            $scope.pageStack = {};

            angular.forEach(pages, function (pageInfo, pageId) {
                // Add page to render array
                $scope.pages.push({uuid: pageId, weight: parseInt(pageInfo.weight)});

                // ---------------------
                // build field/group render array
                // ---------------------
                $scope.pageStack[pageId] = [];

                // add fields to render array
                angular.forEach(pageInfo.fields, function (fieldInfo, fieldUuid) {
                    fieldInfo.uuid = fieldUuid;
                    $scope.pageStack[pageId].push(fieldInfo);
                });

                // add groups to render array
                if (typeof pageInfo.groups !== 'undefined') {
                    angular.forEach(pageInfo.groups, function (groupInfo, groupUuid) {
                        groupInfo.uuid = groupUuid;
                        groupInfo.isGroup = true;
                        $scope.pageStack[pageId].push(groupInfo);
                    });
                }
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

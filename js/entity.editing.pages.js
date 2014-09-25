(function (angular) {
    angular.module('FormBuilderPageHelper', []).factory('$pageHelper', function ($http, $timeout) {
        var helper = {};

        // ---------------------
        // Create new page
        // ---------------------
        helper.pageNew = function () {
            var $scope = this;
            $scope.newPageAdding = true;
            $http
                    .post(window.location.pathname, {
                        action: 'addPage',
                        entity: $scope.entity
                    })
                    .success(function (data) {
                        $scope.newPageAdding = false;
                        $scope.entity.layoutOptions.pages[data.pageUuid] = {
                            title: $scope.newPageTitle,
                            description: '',
                            weight: 1,
                            fields: {}
                        };
                        $scope.newPageTitle = '';
                    });
        };

        // ---------------------
        // Remove page
        // ---------------------
        helper.pageRemove = function (pageUuid) {
            if (!confirm("Are you sure to delete page?"))
                return;

            // Remove fields
            for (var i in this.pageStack[pageUuid])
                this.fieldRemove(pageUuid, this.pageStack[pageUuid][i].uuid);

            // Remove page
            delete(this.entity.layoutOptions.pages[pageUuid]);
        };

        // ---------------------
        // pageOnDrop
        // ---------------------
        helper.pageOnDrop = function ($event, fromPageUuid, toPageUuid) {
            $scope = this;
            f = this.entity.layoutOptions.pages[fromPageUuid];
            t = this.entity.layoutOptions.pages[toPageUuid];
            f.weight = t.weight + 1;

            $timeout(function () {
                $scope.pages.sort(function (a, b) {
                    return a.weight - b.weight;
                });

                for (var i in $scope.pages)
                    $scope.entity.layoutOptions.pages[$scope.pages[i].uuid].weight = i * 2;
            }, 100);
        };

        return helper;
    });
})(angular);

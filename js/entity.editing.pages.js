(function (angular) {
    angular.module('FormBuilderPageHelper', []).factory('$pageHelper', function ($http) {
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
            for (var i in this.pageFields[pageUuid])
                this.fieldRemove(pageUuid, this.pageFields[pageUuid][i].uuid);

            // Remove page
            delete(this.entity.layoutOptions.pages[pageUuid]);
        };

        // ---------------------
        // pageOnDrop
        // ---------------------
        helper.pageOnDrop = function ($event, fromPageUuid, toPageUuid) {
            var fromI, toI;

            for (var i in this.pages)
                if (fromPageUuid === this.pages[i].uuid)
                    fromI = i;

            for (var i in this.pages)
                if (toPageUuid === this.pages[i].uuid)
                    toI = i;

            this.pages[fromI].weight = this.pages[toI].weight + 1;

            this.pages.sort(function (a, b) {
                return a.weight - b.weight;
            });

            for (var i in this.pages)
                this.pages[i].weight = i * 2;

            this.entity.layoutOptions.pages[fromPageUuid].weight = this.pages[fromI].weight;
            this.entity.layoutOptions.pages[toPageUuid].weight = this.pages[toI].weight;
        };

        return helper;
    });
})(angular);
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
                        $scope.entity.layoutOptions[data.pageUuid] = {
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
            var $scope = this;
            for (var i in $scope.pageFields[pageUuid])
                $scope.fieldRemove(pageUuid, $scope.pageFields[pageUuid][i].uuid);
            delete($scope.entity.layoutOptions[pageUuid]);
        };

        return helper;
    });
})(angular);
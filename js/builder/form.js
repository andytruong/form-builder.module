(function (angular) {
    angular.module('FormBuilderFormHelper', []).factory('$formHelper', function ($http) {
        var helper = {};

        // ---------------------
        // Add slug helper
        // ---------------------
        angular.extend(helper, {
            slugDoAuto: true,
            slugAuto: function () {
                if (!this.slugDoAuto)
                    return;

                this.entity.slug = this.entity.title
                        .toLowerCase()
                        .replace(new RegExp('[^a-z0-9_]+', 'g'), '-')
                        .substr(0, 255);
            },
            slugDisableAuto: function () {
                this.slugDoAuto = false;
            }
        });

        // ---------------------
        // Form submit
        // ---------------------
        helper.formSubmit = function () {
            var $scope = this;
            $scope.formSaving = true;
            $http
                    .post(window.location.pathname, {action: 'save', entity: this.entity})
                    .success(function () {
                        $scope.formSaving = false;
                    });
        };

        return helper;
    });
})(angular);

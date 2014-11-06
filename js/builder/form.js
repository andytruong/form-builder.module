(function (angular, window) {
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
                    .success(function (data) {
                        $scope.formSaving = false;

                        if ('new' === data.result) {
                            window.location = '/admin/structure/fob-form/manage/' + data.id;
                        }
                    });
        };

        return helper;
    });
})(angular, window);

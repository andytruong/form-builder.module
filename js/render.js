(function ($, Drupal) {

    Drupal.ajax.prototype.commands.FormBuilderMoreItem = function (ajax, response, status) {
        console.log([ajax, response, status]);
    };

})(jQuery, Drupal);

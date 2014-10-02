(function ($, Drupal) {

    Drupal.behaviors.fobFieldMore = {
        attach: function (context) {
            var $button = $('.fob-field-more', context);

            $button.each(function () {
                var $wrapper = $button.closest('.form-item');
                var $table = $wrapper.find('table:last tbody');
                $table.find('tr').slice(-2).addClass('fobHelper').css('display', 'none');

                // trigger to "add more" button, clone last element, append to the table
                $button.click(function () {
                    var delta = $table.find('tr').length - 2;
                    var $row = $table.find('tr:nth-last-child(2)').removeClass('fobHelper');

                    // Fix delta, path, items, …
                    $row.find('input, textarea, select').each(function () {
                        var name = $(this).attr('name').replace(/^(.+)\[\d+\](.+)$/, '$1[' + delta + ']$2');
                        $(this).attr('name', name);
                    });

                    // create last row for table
                    $row.clone().addClass('fobHelper').appendTo($table);
                    $row.css('display', 'table-row');

                    // do not submit form
                    return false;
                });
            });

            // on form delete, remove fake items
            $button.first().closest('form').bind('submit', function () {
                $button.each(function () {
                    $('.fobHelper').remove();
                });
            });
        }
    };

})(jQuery, Drupal);

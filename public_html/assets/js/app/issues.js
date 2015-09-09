$('[data-role="ajax-submit"]').on('click', function(event) {
    event.preventDefault();
    var url = $('span[data-role="ajaxUpdateUrl"]').attr('data-value');

    $.ajax({
        type:       'POST',
        url:        url,
        data:       $('#issue-form').serialize(),
        success:    function(response) {
            if( typeof response.isValid !== 'undefined' && response.isValid === true ) {
                $('#issue-form').submit();
            }
            else if( typeof response.isValid !== 'undefined' && response.isValid === false ) {
                var errorsCollection = response.errors;
                var $errorsContainer = $('div[data-role="errors-container"]');
                $errorsContainer
                        .removeClass('hidden')
                        .text('')
                        .show();

                for(var field in errorsCollection) {
                    console.log(field);

                    for(var error in errorsCollection[field]) {
                        console.log(error);
                    }
                }
            }
        },
        error:      function(response) {
            console.log('Error');
            console.log(response);
        }
    });
});
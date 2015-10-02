(function($, window, undefined) {
    // Init Bootstrap datepicker
    $('[data-role="datepicker"]').datepicker();

    // Init WYSIWYG html5 text editor
    $('textarea[data-role="wyshtml5-area"]').wysihtml5();

    // Init bootstrap select2
    $('select[data-role="select2"]').select2();

    // Init iCheck plugin
    $('input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal-blue'});
    
    // Add confirmation dialog when pressing "delete" button
    $(document).on('click', '[data-action="confirm-delete"]', function(event) {
        event.preventDefault();
        
        if( confirm('Are you sure you want to delete this item?') === true ) {
            var location = $(this).attr('href');
            
            window.location.href = location;
        }
        
        return false;
    });
})(jQuery, this);


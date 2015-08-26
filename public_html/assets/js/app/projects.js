var autocompleteUrl = $('span[data-role="autocompleteUrl"]').attr('data-value');
        
function removeMember() {
    var $row = $(this).closest('tr');

    $row.fadeOut();
}

$('input[data-role="autocomplete"]').autocomplete({
    minChars: 2,
    serviceUrl: autocompleteUrl,
    onSelect: function(suggestion) {
        var memberId = suggestion.data;
        var member = suggestion.name;
        var $table = $('table[data-role="members-table"]');
        var $roles = $('[data-role="roles"] input');

        $('button[data-role="add-member"]').on('click', function() {
            if( $table.find('tr[data-member-id="'+ memberId +'"]').length > 0 ) {
                alert('This member is already part of the project!');

                return;
            }
            
            if( $roles.filter(':checked').length === 0 ) {
                alert('Please, select at least 1 role for this member!');
                return;
            }

            var $rowTemplate = $table.find('tr[data-role="row-template"]');
            var $row = $rowTemplate.clone();
            var $cells = $row.find('td');

            $row
                    .insertBefore($rowTemplate)
                    .removeAttr('data-role')
                    .removeClass('hidden')
                    .attr('data-member-id', memberId).attr('data-member', member)
                    .fadeIn();

            $cells
                    .eq(0).text(member).end()
                    .eq(1).text('test').end();

            $('input[data-role="autocomplete"]').val('');
            $('input[data-role="autocomplete"]').autocomplete().clear();
            
            suggestion = null;
            member = null;
            memberId = null;
        });
    }
});

$(document).on('click', 'a[data-role="remove-member"]', removeMember);
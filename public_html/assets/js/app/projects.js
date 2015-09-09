var autocompleteUrl = $('span[data-role="autocompleteUrl"]').attr('data-value');
var addMemberUrl = $('span[data-role="addMemberUrl"]').attr('data-value');;
var memberInput = $('input[data-role="member"]');
var memberIdInput = $('input[data-role="memberId"]');
        
function removeMember() {
    var $row = $(this).closest('tr');

    $row.fadeOut(function() {
        $(this).remove();
    });
}

function addMember() {
    var $table = $('table[data-role="members-table"]');
    var $roles = $('[data-role="roles"] input');
    
    if( $table.find('tr[data-member-id="'+ memberIdInput.val() +'"]').length > 0 ) {
        alert('This member is already part of the project!');
        
        return;
    }
    else if( $roles.filter(':checked').length === 0 ) {
        alert('Please, select at least 1 role for this member!');
        
        return;
    }
    else {
        // An array with selected roles. Used as parameter for our ajax call
        // along with selected member Id to be associated with this project.
        var selectedRolesIds = $('[data-role="roles"] input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        // An array with selected role names, used to display in members table.
        var selectedRoles = $('[data-role="roles"] input:checkbox:checked').map(function() {
            return $(this).closest('label').text();
        }).get();
        
        // Add selected member to this project.
        $.ajax({
            type: 'POST',
            url: addMemberUrl,
            data: {
                id: $('span[data-role="projectId"]').attr('data-value'),
                memberId: memberIdInput.val(),
                rolesIds: selectedRolesIds
            },
            success: function(response) {
                if( typeof response.success !== 'undefined' && response.success === true ) {
                    // A row template to be populated with selected member data.
                    // Its easier to have hidden row that can be used as a template
                    // instead of manually creating the HTML here.
                    var $rowTemplate = $table.find('tr[data-role="row-template"]');
                    var $row = $rowTemplate.clone();
                    var $cells = $row.find('td');

                    // After we cloned the hidden row, it's time to bring it to life.
                    // We are removing the data attribute we use to find our hidden template row
                    // as well as it hidden class and then we add some helper data attributes.
                    $row
                            .insertBefore($rowTemplate)
                            .removeAttr('data-role')
                            .removeClass('hidden')
                            .attr('data-member-id', response.memberId).attr('data-member', response.member)
                            .fadeIn();

                    // Populate the first cell with our member's name.
                    // Then populate the second cell with selected roles.
                    $cells
                            .eq(0).text(response.member).end()
                            .eq(1).text(selectedRoles.join(", ")).end();
                }
            },
            error: function(response) {
                
            }
        });
        
        // Reset.
        $('input[data-role="autocomplete"]').val('');
        $('input[data-role="autocomplete"]').autocomplete().clear();
        $('[data-role="roles"] input').iCheck('uncheck');
        memberInput.attr('value', null);
        memberIdInput.attr('value', null);
    }
}

$(document).on('click', 'button[data-role="add-member"]', addMember);
$(document).on('click', 'a[data-role="remove-member"]', removeMember);

$('input[data-role="autocomplete"]').autocomplete({
    minChars: 2,
    serviceUrl: autocompleteUrl,
    onSelect: function(suggestion) {
        memberInput.attr('value', suggestion.name);
        memberIdInput.attr('value', suggestion.data);
    }
});
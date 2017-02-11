function onChangeTypeParticipation(elt) {
    var value = $(elt).val();
    if (value == 'import-photo') {
        $('#album-list').hide();
        $('#import-photo').show();
    } else {
        $('#album-list').show();
        $('#import-photo').hide();
    }
    console.log(value);
}

function share(elt)
{
    FB.ui({
        method: 'share',
        href: $(elt).data('complete-url'),
        hashtag: 'PardonMaman'
    }, function(response){});
}

function confirmTest()
{
    $.confirm({
        title: 'Confirm!',
        content: 'Simple confirm!',
        buttons: {
            confirm: function () {
                $.alert('Confirmed!');
            },
            cancel: function () {
                $.alert('Canceled!');
            },
            somethingElse: {
                text: 'Something else',
                btnClass: 'btn-primary',
                keys: ['enter', 'shift'],
                action: function(){
                    $.alert('Something else?');
                }
            }
        }
    });
}
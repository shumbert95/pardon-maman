function onChangeTypeParticipation(elt) {
    var value = $(elt).val();
    if (value == 'import-photo') {
        $('#album-list').hide();
        $('#import-photo').show();
    } else {
        $('#album-list').show();
        $('#import-photo').hide();
    }
}

function share(url) {
    FB.ui({
        method: 'share',
        href: url,
    }, function(response){
    });
}

function seeMore()
{
    var listHiddenRows = $('.gallery-row:hidden');
    if (listHiddenRows.length > 0) {
        listHiddenRows.slice(0, 3).show();
         if( listHiddenRows.length == 1) {
            $('#seeMore').hide();
        }
    }
}

$(document).ready(function() 
{

    if(window.location.href.indexOf('#myModal_2') != -1) 
    {
        $('#myModal_2').modal('show');
    }

});
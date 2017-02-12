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

function share(url) {
//{
//    FB.ui({
//        method: 'share_open_graph',
//        action_type: 'og.shares',
//        action_properties: JSON.stringify({
//            object : {
//                'og:url': 'http://',
//                'og:title': galleryItem.title,
//                'og:description': galleryItem.description,
//                'og:og:image:width': '2560',
//                'og:image:height': '960',
//                'og:image': BASE_URL + '/images/works/galleries' + galleryItem.image
//            }
//        })
//    });
    FB.ui({
        method: 'share',
        href: url,
    }, function(response){
        console.log('test');
    });
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

function seeMore()
{
    var listHiddenRows = $('.gallery-row:hidden');
    if (listHiddenRows.length > 0) {
        listHiddenRows.first().show();
         if( listHiddenRows.length == 1) {
            $('#seeMore').hide();
        }
    }
}
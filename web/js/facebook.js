$(document).ready(function(){

    var listOfScope = ['public_profile','email'];
    var maxRerequestScope = 2;
    var numberRerequestScope = 0;




    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    window.fbAsyncInit = function() {

        FB.init({
            appId      : '1504707966210156',
            cookie     : true,  // enable cookies to allow the server to access
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.5' // use graph api version 2.5
        });


        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });

    };



    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }

    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            verifyScope(testAPI, response);

        } else if (response.status === 'not_authorized') {
            numberRerequestScope = 0;
            FB.login(function(response){
                statusChangeCallback(response);
            }, {scope: listOfScope.join()});
        }
    }


    function verifyScope(callback, values){

        var listOfScopeGrantedNow = [];
        var error = false;

        FB.api('/me/permissions', function(response) {

            response.data.forEach(function(permission){
                if(permission.status == "granted"){
                    listOfScopeGrantedNow.push(permission.permission);
                }
            });

            listOfScope.forEach(function(permissionAsking){
                if( $.inArray(permissionAsking, listOfScopeGrantedNow) == -1 )
                {
                    console.log("Il manque des permissions : "+permissionAsking);
                    error = true;
                }
            })

            if(error){
                $("#subscribe").show();
                $("#disconnect").hide();
                askScopeAgain();
            }else{
                $("#subscribe").hide();
                $("#disconnect").show();
                console.log(arguments);
                callback(values);
            }

            return !error;
        });
    }

    function askScopeAgain(){

        if(numberRerequestScope < maxRerequestScope){

            FB.login(function(response){
                verifyScope(testAPI, response);
            }, {scope: listOfScope.join(), auth_type: 'rerequest'} );

            numberRerequestScope++;
        }

    }

    function testAPI(response) {
        console.log(response);
        console.log('Welcome!  Fetching your information.... ');
        console.log("Access token : "+response.authResponse.accessToken);
        console.log("User id : "+response.authResponse.userID);

    }

});
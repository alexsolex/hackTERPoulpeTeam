(function ($, window, undefined) {
    var init = function () {
        
            $('#btnDeconnexion').on('click', function(){
                $.ajax({
                type: "GET",
                url: BASE_URL + '/api/utilisateur/deconnecter' ,
                dataType : "json",
                //affichage de l'erreur en cas de problème
                error:function(){},
                success:function(){
                    $.mobile.changePage(BASE_URL,{reloadPage:true, changeHash:false});
                }
                });
            });
            


            $('#logger').on('click',function(){
                $.ajax({
                type: "POST",
                url: BASE_URL + '/api/utilisateur/authentifier' ,
                data: {login : $('#login').val(),password:$('#password').val()},
                dataType : "json",
                //affichage de l'erreur en cas de problème
                error:function(){
//                    alert('Erreur');
                    $.mobile.changePage(BASE_URL + '/utilisateur/index',{reloadPage:true, changeHash:false});
                },

                success:function(){                
                    $.mobile.changePage(BASE_URL + '/utilisateur/index',{reloadPage:true, changeHash:false});
                }
                });  
            });
    };
        
        //************************************************************
    // Public
    var objPublic = {
        init:init
    };
    //************************************************************
    // Instanciate object : Ex
    window._Comulien.Controller.Public = objPublic;
})(jQuery, window);


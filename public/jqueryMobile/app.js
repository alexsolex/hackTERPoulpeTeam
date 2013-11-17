(function ($, window, undefined) {
    "use strict";

    var dateProchaine = null;
    var dateProchaineReponse = null;
    var dateDernierMessage = null;
    var dateDernierReponse = null;
    var messageParentEnCours = null;
    var killScroll = false; // IMPORTANT
    var vue = 'messages';
    var ancre = null;
    var user =null;
//************************************************************
//PRIVATE
  
//************************************************************
//Fonction qui calcul le total des likes
function totalLike(like, unlike){
    return parseInt(like) - parseInt(unlike);
}

//************************************************************
//Fonction qui retourne la date(timestamp) au format dd/mm/YYYY
function miseFormeDate(dateMessage){
    var d = new Date(dateMessage*1000);
    return d.toLocaleDateString();
}

//************************************************************
//fonction de calcul des delais des messages
function calculDelais(){
    $("#texteNewMessage").on('click',function(){
                       dateProchaine = null;
                        $('#listeMessage').empty();
                        chargerMessagesSuivant();
                        $('#newMessage').css('display','none');
                    });
    setInterval(function(){
         $(".dateMessage").each(function(){
            delai = calculDate(this.id);
            this.innerHTML= ' ' + delai;
        });
        nbrMessagesRecent();
    }, 60000);
}

//************************************************************
//function de recherche du nombre de nouveau messages
//Depuis la dernière recherche
function nbrMessagesRecent(){
     $.ajax({
            type: "GET",
            url: BASE_URL + "/api/message/compter/fromdate/" + dateDernierMessage ,
            dataType : "json",
            //affichage de l'erreur en cas de problème
            error:function(string){
               
            },

            success:function(data){
                //traitement du json pour créer l'HTML
                
                var nbr = data.count>0 ? data.count : '';
                if(nbr > 0){
                    $("#texteNewMessage").html('Nouveaux messages (+ ' + nbr + ')');
                    
                    $("#newMessage").css('display','block');
                    dateDernierMessage = data.lastCheckedDate;
                }
                
            }
     })
}

//************************************************************
//Requête Ajax pour liker un message
function likeMessage(event){
    $.ajax({
            type: "POST",
            data: 'message='+event.id+'&rating='+event.val,
            url: BASE_URL + '/api/message/approuver/message',
            dataType : "json",
            //affichage de l'erreur en cas de problème
            error:function(string){
                $(location).attr('href',BASE_URL +"/utilisateur/authentifier");
//               alert( "Erreur !: " + string );
            },
            success:function(data){
//                $('#totalLike' + event.id).html(data.noteGlobale);
//                $('#like' + event.id).html(data.like);
//                $('#dislike' + event.id).html(data.dislike);
                $('#textLike' + event.id).html(data.like);
                $('#textDislike' + event.id).html(data.dislike);
                if(data.userRating === 1){
                    document.getElementById('like' + event.id).src = BASE_URL + '/jqueryMobile/images/like_on.png';
                    document.getElementById('dislike' + event.id).src = BASE_URL + '/jqueryMobile/images/dislike.png';
                }else if(data.userRating === -1){
                    document.getElementById('dislike' + event.id).src = BASE_URL + '/jqueryMobile/images/dislike_on.png';
                    document.getElementById('like' + event.id).src = BASE_URL + '/jqueryMobile/images/like.png';
                }
                else{
                    document.getElementById('dislike' + event.id).src = BASE_URL + '/jqueryMobile/images/dislike.png';
                    document.getElementById('like' + event.id).src = BASE_URL + '/jqueryMobile/images/like.png';
                }
            }
     });
}

//************************************************************
//Requête Ajax pour moderer un message
function modererMessage(event){
    $.ajax({
            type: "POST",
            url: BASE_URL + '/api/message/moderer' ,
            data: {messageID : event.id,format:'json'},
            dataType : "json",
            //affichage de l'erreur en cas de problème
            error:function(string){
               alert( "Erreur !: " + string );
            },

            success:function(){                
                        if($('#corps' +event.id).hasClass('modere')){
                            $('#coprs' +event.id + ' > .inactif').remove();
                           
                        }else
                        {
                            $('<div>',{
                                'class': 'inactif'
                            }).prependTo($('#corps' + event.id));
                            
                        }
                        $('#corps' +event.id).toggleClass('modere');      
            }
     });
}

//************************************************************
//Pour tous les messages
//Récupération de la dernière date
//Appel de la fonction de création des messages
function creeHtmlMessage(element){
    if(element.nextDate !== null){
        dateProchaine = element.nextDate;
    }
    for (var i = 0; i<element.messages.length; i++){
        ecrireHtmlMessage(element.messages[i], "message",element.moderator);
    }    
}

//************************************************************
//Pour toutes les réponses
//Appel de la fonction de création des réponses
function creeHtmlReponse(element){
    if(messageParentEnCours === null){ecrireHtmlMessage(element.message, "messageParent",element.moderator);}
    if(element.nextDate !== null){
        dateProchaineReponse = element.nextDate;
    }

    for (var i = 0; i<element.replys.length; i++){
        ecrireHtmlMessage(element.replys[i], "reponse",element.moderator);
        
    }    
}


//************************************************************
//Fonction de création d'un messages dans le DOM
//element : objet message
//type : message ou reponses
//moderator : true si l'utilisateur est modérateur
function ecrireHtmlMessage(element, type, moderator){

if(type==='message'){
    //Création ancre
    $('<a>', {
        name:element.messageID
    }).appendTo("#listeMessage");
    //Création du block message
    var message = $("<div>", {
    "class": "message ",
    id: "message " + element.messageID,
    "data-idmessage" : element.messageID
    }).appendTo( "#listeMessage" );
}else if(type==='messageParent'){
    //Création du block message
    var message = $("<div>", {
    "class": "message ",
    id: "message " + element.messageID,
    "data-idmessage" : element.messageID
    }).appendTo( "#messageParent" );
    
}else{
    //Création du block message
    var message = $("<div>", {
    "class": "message ",
    id: "message " + element.messageID,
    "data-idmessage" : element.messageID
    }).prependTo( "#reponses" );
}

        //Création du block entête
        var entete = $('<div>', {
        'class': 'entete ui-bar '
        }).appendTo(message);
        
        if(type!=='reponse'){
            //Ajout de la date d'activité
            $('<div>', {
            id : element.activityDate,
            'class': 'dateActiviteMessage info1',
            text : ' ' + calculDate(element.activityDate)
            }).appendTo(entete);
        } 
            //Ajout du login et éventuellement du profil s'il est corporate
            var listNomProfil = $('<ul>', {
            'class': 'list'
            }).appendTo(entete);

                $('<li>', {
                'class': 'liList nomUser',
                text : element.login
                }).appendTo(listNomProfil);

                if(element.profilID==="1"){
                    $('<li>', {
                    'class': 'liList profilUserAdmin',
                    text : ' - Organisateur'
                    }).appendTo(listNomProfil);
                }
                else if(element.profilID==="2"){
                    $('<li>', {
                    'class': 'liList profilUserCorpo',
                    text : ' - Corporate'
                    }).appendTo(listNomProfil);
                }
                else if(element.profilID==="3"){
                    $('<li>', {
                    'class': 'liList profilUserPartenaire',
                    text : ' - Partenaire'
                    }).appendTo(listNomProfil);
                }
            
        //Ajout du corps du message    
        var corps = $('<div>', {
            id: "corps " + element.messageID,
        'class': 'corps ui-grid-a'
        }).appendTo(message);
                        
            var lien = $('<a>',{
                'class' : 'profil ui-block-a',
                href : '../utilisateur/profilpublic/id/' + element.senderID
            }).appendTo(corps);
            
            var avatar = $('<div>', {
            'class': 'avatar'
            }).appendTo(lien);
            $(gravatar(element.emailMD5)).appendTo(avatar);
            
            var texteMessage = $('<div>', {
             id : 'texte' + element.messageID,
            'class': 'texteMessage ui-block-b'
            }).appendTo(corps);
            //Test si le message à été modéré ou non
            if(element.moderatorID !== null){
                corps.addClass('modere');
                
            }else
            {
                corps.removeClass('modere');
            }
            if(type==="message"){
                texteMessage.click(function(){
                    $('#messageParent').empty();
                    $('#reponses').empty();
                    ancre = element.messageID;
                    dateProchaineReponse =null;
                    //Afficher le bouton de retour**********************************
                    chargerReponses(element.messageID);
                    $('#listeMessage').fadeOut( "fast", function() {
                        $('#reponses').fadeIn( "fast");
                        vue = 'reponses';
                        $('#parent').val(element.messageID);
                        messageParentEnCours = element.messageID;
                        $('#retourMessage').css('display','block');
                      });
                     
                });
            }
            $('<div>', {
            'class': 'lblMessage',
            text : element.text
            }).appendTo(texteMessage);
            if(element.responseCount >0){
              var nbReponse = $('<div>',{
              'class' : 'nbReponse'
              }).appendTo(corps);


              $('<span>',{
                  id: 'compteur' + element.messageID,
                  'class': 'ui-btn-up-c ',
                  text: element.responseCount
              }).appendTo(nbReponse);
            }
            
            //Si l'utilisateur est modérateur
            var grid = (moderator)? "ui-grid-c":"ui-grid-b";
              var menu = $('<div>', {
                  'class': 'MenuMessage ' + grid 
              }).appendTo(message);
              
      if(element.moderatorID === null){
              var iconeLike = 'like.png';
              var iconeDislike = 'dislike.png';
              switch(element.userEvaluation){
                  case '1':
                      iconeLike = 'like_on.png';
                      break;
                  case '-1':
                      iconeDislike = 'dislike_on.png';
                      break;
              }
              
            var blockA = $('<div>',{
                'class': 'ui-block-a'
            }).appendTo(menu);
                var list = $('<ul>',{
                }).appendTo(blockA);
                 var elementMenu = $('<li>',{
                     
                 }).appendTo(list);
                  var like = $('<img>', {
                      'class': 'boutonLike',
                      id: 'like' + element.messageID,
                      src : BASE_URL + '/jqueryMobile/images/' + iconeLike
                  }).appendTo(elementMenu);
                  like.click(function(){
                      if($(this).attr('src') === BASE_URL + '/jqueryMobile/images/like_on.png')
                    {
                        likeMessage({id: element.messageID, val: '0'});
                    }else{
                        likeMessage({id: element.messageID, val: '1'});
                    }
                  });
                  $('<li>', {
                      'class': 'info',
                      id: 'textLike' + element.messageID,
                      text : element.like
                  }).appendTo(list);
              var blockB = $('<div>',{
                'class': 'ui-block-b'
            }).appendTo(menu);
            var list = $('<ul>',{
                }).appendTo(blockB);
                 var elementMenu = $('<li>',{
                     
                 }).appendTo(list);
                 
              var disLike = $('<img>', {
                  'class': 'boutonDislike',
                  id: 'dislike' + element.messageID,
                  src : BASE_URL + '/jqueryMobile/images/' + iconeDislike
              }).appendTo(elementMenu);
              $('<li>', {
                      'class': 'info',
                      id: 'textDislike' + element.messageID,
                      text : element.dislike
                  }).appendTo(list);
              disLike.click(function(){
                  if($(this).attr('src') === BASE_URL + '/jqueryMobile/images/dislike_on.png')
                    {
                        likeMessage({id: element.messageID, val: '0'});
                    }else{
                        likeMessage({id: element.messageID, val: '-1'});
                    }
                  
                  
              });
              
              
              var blockC = $('<div>',{
                'class': 'ui-block-c'
              }).appendTo(menu);
              var share = $('<a>', {
                  'class': 'boutonShare',
                  href : "#popupShare",
                  'data-rel':"popup",
                  'data-transition':"flip",
                  src : BASE_URL + '/jqueryMobile/images/social_share.png'
              }).appendTo(blockC);
              $('<img>',{
                  'class': 'boutonShare',
                  'data-rel':"popup",
                  'data-transition':"flip",
                  src : BASE_URL + '/jqueryMobile/images/social_share.png'
              }).appendTo(share);
              
//              share.click(function(){
//              //mettre l'action
//              })
              
              
              if(moderator){
                var blockD = $('<div>',{
                  'class': 'ui-block-d'
                }).appendTo(menu);
                var moderer = $('<img>', {
                    'class': 'boutonModerer',
                    src : BASE_URL + '/jqueryMobile/images/key.png'
                }).appendTo(blockD);
                moderer.click(function(){
                modererMessage({id: element.messageID});
                });
              }
              
      }else{
          if(moderator){
                var blockD = $('<div>',{
                  'class': 'ui-block-a'
                }).appendTo(menu);
                var moderer = $('<img>', {
                    'class': 'boutonModerer',
                    src : BASE_URL + '/jqueryMobile/images/key.png'
                }).appendTo(blockD);
                moderer.click(function(){
                modererMessage({id: element.messageID});
                });
              }
      }


}


//Fonction de parcours des éléments data retournés
function parseJSON(data){
    $(data).each(function(i){
        creeHtmlMessage(this);
    });
}

//Fonction de parcours des éléments data retournés
function parseJSONReponse(data){
    $(data).each(function(i){
        creeHtmlReponse(this);
    });
}


//Requête Ajax 
//Retourne les x messages suivant par rapport à une date
//IMPORTANT : Cette fonction est utilisée pour afficher les messages
//lors du premier chargement de la page
function chargerMessagesSuivant(){
    if(vue==="messages"){
        if(dateProchaine===null){dateProchaine = new Date().getTime();
            dateProchaine = Math.floor(dateProchaine/1000);
            }
         if(dateDernierMessage!==dateProchaine){
            if(dateDernierMessage===null){dateDernierMessage = dateProchaine;}

             $.ajax({
                    type: "GET", //http://localhost/comulien/public/api
                    url: BASE_URL + "/api/message/lister-tous/fromdate/" + dateProchaine ,
                    dataType : "json",
                    //affichage de l'erreur en cas de problème
                    error:function(string){
                        alert( "Erreur !: " + string );
                    },

                    success:function(data){
                        //traitement du json pour créer l'HTML
                        parseJSON(data);
                    }
             });
         }
     }else{
         if(dateProchaineReponse===null){dateProchaineReponse = new Date().getTime();
            dateProchaineReponse = Math.floor(dateProchaineReponse/1000);
            }
         if(dateDernierReponse!==dateProchaineReponse){
            if(dateDernierReponse===null){dateDernierReponse = dateProchaineReponse;}

             $.ajax({
                    type: "GET", //http://localhost/comulien/public/api
                    url: BASE_URL + "/api/message/reponses/fromdate/" + dateDernierReponse + "/count/20/message/"+ messageParentEnCours,
                    dataType : "json",
                    //affichage de l'erreur en cas de problème
                    error:function(string){
                        alert( "Erreur !: " + string );
                    },

                    success:function(data){
                        //traitement du json pour créer l'HTML
                        parseJSONReponse(data);
                    }
             });
         }
         
     }
}

function chargerReponses(numMessage){
    if(dateProchaineReponse===null){
        dateProchaineReponse = new Date().getTime();
        dateProchaineReponse = Math.floor(dateProchaineReponse/1000);
        
}

    $.ajax({
            type: "GET",
            url: BASE_URL + "/api/message/reponses/fromdate/"+ dateProchaineReponse + "/count/20/message/" + numMessage ,
            dataType : "json",
            //affichage de l'erreur en cas de problème
            error:function(string){
//                alert( "Erreur !: " + string );
            },

            success:function(data){
                //traitement du json pour créer l'HTML
                console.log(data);
                parseJSONReponse(data);
                
                return false;
            }
        });
}
//************************************************************
//PUBLIC

//Fonction qui retourne la date d'activité du message au format simplifié
//hier, min, heure, jour
function calculDate(dateMessage){
    var maintenant = new Date().getTime();
    var diff = Math.floor(maintenant/1000)- dateMessage;
    
    var diff_j = Math.floor(diff / (24*3600));
    diff = diff % (24*3600);
    var diff_h = Math.floor(diff / (3600));
    diff = diff % 3600;
    var diff_m = Math.floor(diff / (60));
    diff = diff % 60;

    
    if(diff_j === 1){
        return "hier";
    }
    else if(diff_j >1){
        return diff_j + " j";
    }
    else if(diff_h >0 && diff_h<24){
        return diff_h + " h";
    }
    else if(diff_m >0 && diff_m<60){
        return diff_m + " min";
    }
    else{
        return "à l'instant";
    }
    
}

//************************************************************
function retourAuxMessages(){

    $('#retourMessage').click(function(){
        $('#reponses').fadeOut( "fast", function() {
            $('#messageParent').empty();
                    $('#reponses').empty();
            $('#listeMessage').fadeIn( "fast");
            vue = 'messages';
            $('#parent').val("");
            $('#retourMessage').css('display','none');
            messageParentEnCours = null;
            dateProchaineReponse = null;
            window.location.hash = ancre;
            ancre = null;
            //Desafficher le bouton de retour**********************************
        });
    });
}

//************************************************************
jQuery.fn.center = function () {
    this.css("display", "none");
    this.css("position","absolute");
    
    this.css("top", Math.max(0, (($(window).height() - this.outerHeight())) +
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - this.outerWidth()) / 2) +
                                                $(window).scrollLeft()) + "px");
                                        this.css("display", "block");
    return this;
};


//************************************************************   
//Fonction de création du lien gravatar
function gravatar(email){
    return '<img src="http://www.gravatar.com/avatar/' + email + '.jpg?&d=mm&r=g&s=55"/>';
}


    var init = function () {
            ////Quand le scroll arriv
            //e au bas de la page, on cherche les messages suivnts
            //TODO modifier car la date renvoyée en json est null si pas de message
            $(window).scroll(function(){

                if (_Comulien.Utils.KillScroll === false) { // IMPORTANT - Keeps the loader from fetching more than once.
                    _Comulien.Utils.KillScroll = true; // IMPORTANT - Set killScroll to true, to make sure we do not trigger this code again before it's done running.

                if($("#selector").is(':visible') && ($(document).height() === $(window).height() + $(document).scrollTop())){
                   _Comulien.Controller.App.chargerMessagesSuivant();
                   _Comulien.Utils.KillScroll = false; // IMPORTANT - Make function available again.
                }
                
                }
                _Comulien.Utils.KillScroll = false; // IMPORTANT - Make function available again.
            });
            //Calcul automatique des délais des messages
            _Comulien.Controller.App.calculDelais();
            _Comulien.Controller.App.retourAuxMessages();
            
            
            Offline.check({
                // Should we check the connection status immediatly on page load.
                 checkOnLoad: false,
                 // Should we monitor AJAX requests to help decide if we have a connection.
                 interceptRequests: true,
                 // Should we automatically retest periodically when the connection is down(set to false to disable).
                 reconnect: {
                 // How many seconds should we wait before rechecking.
                 initialDelay: 60,
                 // How long should we wait between retries.
                 delay: 30
                 },
                 // Should we store and attempt to remake requests which fail while theconnection is down.
                 requests: true,
                 // Should we show a snake game while the connection is down to keep theuser entertained?
                 // It's not included in the normal build, you should bring in js/snake.js in addition to
                 // offline.min.js.
                 game: true
                });

        
    };
    //************************************************************
    // Public
    var objPublic = {
        init:init,
        chargerMessagesSuivant:chargerMessagesSuivant,
        calculDelais:calculDelais,
        chargerReponses:chargerReponses,
        retourAuxMessages:retourAuxMessages
    };
    //************************************************************
    // Instanciate object : Ex
    window._Comulien.Controller.App = objPublic;
    window._Comulien.Utils.KillScroll = killScroll;
})(jQuery, window);

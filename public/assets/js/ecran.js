/////////////////////////////////////////////////////////////////////
//
//PoulpeTeam 2013 - PauseTer(c)
//
/////////////////////////////////////////////////////////////////////

//Table qui affiche les prochains départs
var oTableHoraire;

/////////////////////////////////////////////////////////////////////
//Recherche et affiche la question
/////////////////////////////////////////////////////////////////////
function rechercheQuestion(){
    //Retourne la question à afficher
    
}

/////////////////////////////////////////////////////////////////////
//Rafraichi la datatable en effectuant une nouvelle requete ajax
//Mécanisme interne à la datatable
/////////////////////////////////////////////////////////////////////
function rafraichir(){
      setInterval(function(){
         oTableHoraire.fnDraw();
    }, 60000);
    callback();
}

 //callback function to bring a hidden box back
    function callback() {
      setTimeout(function() {
          $.ajax({
             "dataType":"json",
             "contentType":"application/json; charset=utf-8",
             "type":"GET",
             "url": BASE_URL + "/api/question/obtenir/TVS/LEW",
             "success": function(data){
                 $('#textQuestion').text(data['question']['question']);
                 $('#logo').attr('src', BASE_URL + '/assets/img/partenaires/' + data['sponsor']['logo']);
             }
          });
        $( "#dialog-question" ).removeAttr( "style" ).hide().fadeIn();
        callend();
      }, 6000 );
    };
    
    function callend() {
      setTimeout(function() {
        $( "#dialog-question" ).fadeOut();
      }, 30000 );
      setTimeout(function() {
        callback();
      }, 30000 );
    };
/////////////////////////////////////////////////////////////////////
//Début
/////////////////////////////////////////////////////////////////////
$(document).ready(function(){
   
    //Configuration de la datatable
    oTableHoraire = $('#datatable-horaire').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bStateSave": true,
            "sAjaxSource": BASE_URL + "/ecrangare/departs?format=json",
            "sServerMethod": "POST",

         
             //Demande les données au serveur
            "fnServerData": function (sSource, aoData, fnCallback) {
     
                $.ajax({
                    "dataType": 'json',
                    "contentType": "application/json; charset=utf-8",
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": function (data) {
//                        var json = $.parseJSON(data.d);
                        fnCallback(data);
                    }
                });
                
            },

            // Pas de filtre
            "bFilter": false,

            // Pagination
            "bPaginate": false,
            "sPaginationType": false,

            // Internationalisation
            "oLanguage": {
                "sProcessing": "Rafraichissement en cours...",
                "sEmptyTable": "Aucun horaire",
                "sInfo": "",
                "sInfoFiltered": "",
                "sInfoEmpty": "",
                "sZeroRecords": "Aucun horaire",
                "sLengthMenu": ''
                

            },

           
            // Définition des colonnes
            "aoColumnDefs": [
                { "sWidth": "70px", "sClass": "col-id", "aTargets": [0] },
                { "sWidth": "200px", "aTargets": [1] },
                { "sWidth": "150px","aTargets": [2] },
                { "sWidth": "150px","sClass": "col-jaune", "aTargets": [3] },
                { "sWidth": "250px","aTargets": [4] },
                { "sWidth": "400px", "aTargets": [5] },
                { "sWidth": "50px", "aTargets": [5] }
            ]
    });
    
    //Lance le rafraichissement de la datatable (Toutes les minutes)
    rafraichir();
    
    
     
});




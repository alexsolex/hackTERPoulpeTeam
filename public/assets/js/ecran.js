/////////////////////////////////////////////////////////////////////
//
//
//
//Fred Horn 2013 - PauseTer(c)
//
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
}


/////////////////////////////////////////////////////////////////////
//Début
/////////////////////////////////////////////////////////////////////
$(document).ready(function(){
   
    //Configuration de la datatable
    oTableHoraire = $('#datatable-horaire').dataTable({
//            "bProcessing": true,
//            "bServerSide": true,
//            "bStateSave": true,
//            "sAjaxSource": "",
////            "sServerMethod": "POST",
//
//            // Paramètres supplémentaires : filtres
//            "fnServerParams": function (aoData) {
//
//            },
//            "aaData":[
//                ['2322', 'TGV', '96584', '10h35', 'Lille Europe', 'Retard 4min', '4'],
//                ['2322', 'TGV', '96584', '10h35', 'Lille Europe', 'Retard 4min', '4']
//        
//            ],
//            // Demande les données au serveur
////            "fnServerData": function (sSource, aoData, fnCallback) {
////
////                // Convertit le tableau aoData en json interprétable par le deserializer C#
////                var s = '{ p: {';
////                for (var i = 0; i < aoData.length; i++) {
////
////                    var o = aoData[i];
////                    if (i > 0) s += ',';
////                    s += $.format('"{0}" : "{1}"', o.name, o.value);
////                }
////                s += '}}';
////
////                $.ajax({
////                    "dataType": 'json',
////                    "contentType": "application/json; charset=utf-8",
////                    "type": "POST",
////                    "url": sSource,
////                    "data": s,
////                    "success": function (data) {
////                        var json = $.parseJSON(data.d);
////                        fnCallback(json);
////                    }
////                });
////                
////            },
//
            // Pas de filtre
            "bFilter": false,

            // Pagination
            "bPaginate": false,
            "sPaginationType": false,

//            // Internationalisation
//            "oLanguage": {
//                "sProcessing": "Rafraichissement en cours...",
//                "sEmptyTable": "Aucun horaire",
//                "sInfo": "",
//                "sInfoFiltered": "",
//                "sInfoEmpty": "",
//                "sZeroRecords": "Aucun horaire",
//                "sLengthMenu": '',
//                "sSearch": "Filtre : "
//
//            },

            // Définition des colonnes
            "aoColumnDefs": [
                { "sWidth": "50px", "sClass": "col-id", "aTargets": [0] },
                { "sWidth": "300px", "aTargets": [1] },
                { "sWidth": "150px", "aTargets": [2] },
                { "sWidth": "150px", "sClass": "col-jaune", "aTargets": [3] },
                { "sWidth": "380px","aTargets": [4] },
                { "sWidth": "120px", "aTargets": [5] }
            ]
    });
    
    //Lance le rafraichissement de la datatable (Toutes les minutes)
    rafraichir();
});




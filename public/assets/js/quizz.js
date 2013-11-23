/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var urlApiGeo = BASE_URL + '/api/user/geolocalise/';
var urlApiQuestion = BASE_URL + '/api/question/obtenir/TVS/';

function echecGeo()
{
    alert('val stat !!');
    recupererGare(
            50.650,
            3.083
            );
}

function reussiteGeo(position)
{
    alert('géo ok');
    recupererGare(
            position.coords.latitude,
            position.coords.longitude
            );
}

function recupererGare(lat, long)
{
    var questionJSON = {
        "question": {
            "question": "  une commune du nord de la France, pr\u00e9fecture du d\u00e9partement du Nord et chef-lieu en r\u00e9gion Nord-Pas-de-Calais. Surnomm\u00e9e la \u00ab Capitale des Flandres \u00bb une commune du nord de la France, pr\u00e9fecture du d\u00e9partement du Nord et chef-lieu en r\u00e9gion Nord-Pas-de-Calais. Surnomm\u00e9e la \u00ab Capitale des Flandres \u00bbune commune du nord de la France, pr\u00e9fecture du d\u00e9partement du Nord et chef-lieu en r\u00e9gion Nord-Pas-de-Calais. Surnomm\u00e9e la \u00ab Capitale des Flandres \u00bb une commune du nord de la France, pr\u00e9fecture du d\u00e9partement du Nord et chef-lieu en r\u00e9gion Nord-Pas-de-Calais. Surnomm\u00e9e la \u00ab Capitale des Flandres \u00bb",
            "choix1": "Lille",
            "choix2": "Orchies",
            "choix3": "Simcity",
            "choix4": "bruxelles"
        },
        "sponsor": {
            "nom": "Starbuck",
            "FB": null,
            "twitter": null,
            "google": null,
            "url": "www.starbuck.Fr",
            "logo": "Starbucks.png"
        },
        "tvs": "LLF",
        "idq": "2"
    };
    jsonToView(questionJSON);
    return;
    chargerJSON(
            urlApiGeo + 'lat/' + lat + '/long/' + long,
            {},
            recupererQuestion,
            function()
            {
                alert('oups gare');
            }
    );
}

function jsonToView(data) {
    $('#question').text(data.question.question);
    $('#reponse1').text(data.question.choix1);
    $('#reponse2').text(data.question.choix2);
    $('#reponse3').text(data.question.choix3);
    $('#reponse4').text(data.question.choix4);
    
}


function getLocation()
{
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(reussiteGeo);
        // Fourni position.coords.latitude
        // et position.coords.longitude
    }
    else
    {
        echecGeo();
    }
}

function recupererQuestion(data, textStatus, jqXHR) {
    var gareJson = data;

    chargerJSON(
            urlApiQuestion + 'LLF', //gareJson.gare.tvs,
            {},
            afficherQuestion,
            function() {
                alert('oups questions');
            }
    );
}
;

function afficherQuestion(data, textStatus, jqXHR) {
    var questionJson = data;
    var question = question.question;
    alert(question);

    alert('Ok :)');
}


function chargerJSON(url, data, ftSuccess, ftError) {
    // préparation des données..
    var pairs = [];
    for (var i in data) {
        pairs.push(i + ':' + data[i]);
    }
    data = '{' + pairs.join(',') + '}';

    // Préparation du paramètrage Ajax..
    var options = {
        async: true,
        url: url,
        type: 'POST',
        data: data,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: ftSuccess,
        error: ftError
    };

    //jQuery.getJSON(options.url, options.data, ftSuccess);//ne permet pas les POST
    jQuery.ajax(options);
}
$(getLocation);
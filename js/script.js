// autocomplet : Cette fonction va être executé à chaque fois que le texte va changer
function autocomplet() {
    var keyword = $('#country_id').val();
    $.ajax({
        url: 'ajax_refresh.php',
        type: 'POST',
        data: {keyword:keyword},
        success:function(data){
            $('#country_list_id').show();
            $('#country_list_id').html(data);
        }
    });
}

// set_item :cette fonction va être executé quand un item sera selectionné
function set_item(item) {
    // change input value
    $('#country_id').val(item);
    // hide proposition list
    $('#country_list_id').hide();
}

$(document).ready(function(){
    
    $('html').click(function(){
        $('#country_list_id').hide();
    });

    $('#country_id').click(function(){
        $('#country_list_id').show();
    });

});

$('#sandbox-container .input-daterange').datepicker({
    language: "fr",
    todayHighlight: true,
    format: "dd/mm/yy"
});

//fonction qui coche toutes les checkboxes
function CocheTout(ref, name) {
    var form = ref;

    while (form.parentNode && form.nodeName.toLowerCase() != 'form'){
        form = form.parentNode;
    }

    var elements = form.getElementsByTagName('input');

    for (var i = 0; i < elements.length; i++) {
        if (elements[i].type == 'checkbox' && elements[i].name == name) {
            elements[i].checked = ref.checked;
        }
    }
}


//fonction pour imprimer les resultats
function imprime_bloc(titre, objet) {
// Définition de la zone à imprimer
    var zone = document.getElementById(objet).innerHTML;

// Ouverture du popup
    var fen = window.open("", "", "height=500, width=600,toolbar=0, menubar=0, scrollbars=1, resizable=1,status=0, location=0, left=10, top=10");

// style du popup
    fen.document.body.style.color = '#000000';
    fen.document.body.style.backgroundColor = '#FFFFFF';
    fen.document.body.style.padding = "20px";

// Ajout des données a imprimer
    fen.document.title = titre;
    fen.document.body.innerHTML += " " + zone + " ";

// Impression du popup
    fen.window.print();

//Fermeture du popup
    fen.window.close();
    return true;
}


// permet de mettre les inputTexts dans le textArea
var lastStr = "@mail@";
var startIndex = $("textarea").val().indexOf(lastStr);

$(document).ready(function() {
    $("#test1").keyup(function() {
        var oldVal = $("textarea").val();
        
        var firstPart = oldVal.substring(0, startIndex);
        var midPart = oldVal.substr(startIndex, lastStr.length).replace(lastStr, $("#test1").val());
        var lastPart = oldVal.substring(startIndex + lastStr.length, oldVal.length);
        
        $("textarea").val( firstPart + midPart +lastPart );
        lastStr = $("#test1").val();
    });
});


var lastStr2 = "[nom]";
var startIndex2 = $("textarea").val().indexOf(lastStr2);

$(document).ready(function() {
    $("#test2").keyup(function() {
        var oldVal2 = $("textarea").val();
        
        var firstPart2 = oldVal2.substring(0, startIndex2);
        var midPart2 = oldVal2.substr(startIndex2, lastStr2.length).replace(lastStr2, $("#test2").val());
        var lastPart2 = oldVal2.substring(startIndex2 + lastStr2.length, oldVal2.length);
        
        $("textarea").val( firstPart2 + midPart2 +lastPart2 );
        lastStr2 = $("#test2").val();
    });
});




var lastStr3 = "#telephone#";
var startIndex3 = $("textarea").val().indexOf(lastStr3);

$(document).ready(function() {
    $("#test3").keyup(function() {
        var oldVal3 = $("textarea").val();
        
        var firstPart3 = oldVal3.substring(0, startIndex3);
        var midPart3 = oldVal3.substr(startIndex3, lastStr3.length).replace(lastStr3, $("#test3").val());
        var lastPart3 = oldVal3.substring(startIndex3 + lastStr3.length, oldVal3.length);
        
        $("textarea").val( firstPart3 + midPart3 +lastPart3 );
        lastStr3 = $("#test3").val();
    });
});

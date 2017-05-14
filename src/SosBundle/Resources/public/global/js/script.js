$(document).ready(function(){

	if (typeof error != "undefined") {

        if (error == "ville") {
            sweetAlert("Oups...", "La ville n'existe pas !", "error");    
        }
        
    }


    if (typeof validation != "undefined") {

        sweetAlert("", validation, "success");

    }

    // Afficher les postes recherche en cliquant sur les secteurs
    $('.secteur-button').click(function(){
        var ul = $('.'+$(this).attr('data-target'));
        if (ul.hasClass('expanded'))
        {
            ul.slideUp().removeClass('expanded');
        }
        else
        {
            $('.expanded').slideUp().removeClass('expanded');
            ul.slideDown().addClass('expanded');
        }
        console.log($(this).attr('data-target'));
    });

    $('#add-disponibilite').click(function(){
        if ($('.datepicker-here').length == 0) 
        {
            $(this).parent().parent().prepend(''+
                '<div class="input-group" style="width:100%">'+
                    '<input type="text" data-range="true" name="disponibilite[]" data-multiple-dates-separator=" - " class="col-sm-12 datepicker-here">'+
                    '<div class="input-group-btn" style="width:40px">'+
                        '<button class="btn btn-default remove-disponibilite" type="button">'+
                            '<i class="fa fa-minus" ></i>'+
                        '</button>'+
                    '</div>'+
                '</div>');    
        }
        else
        {
            $('.datepicker-here').last().after(''+
                '<div class="input-group" style="width:100%">'+
                    '<input type="text" data-range="true" name="disponibilite[]" data-multiple-dates-separator=" - " class="col-sm-12 datepicker-here">'+
                    '<div class="input-group-btn" style="width:40px">'+
                        '<button class="btn btn-default remove-disponibilite" type="button">'+
                            '<i class="fa fa-minus" ></i>'+
                        '</button>'+
                    '</div>'+
                '</div>');    
        }

        $('.datepicker-here').datepicker({
            language: 'fr',
            minDate: new Date() // Now can select only dates, which goes after today
        });
        
        $('.remove-disponibilite').click(function(){
            $(this).parent().parent().remove();
        });
    });

    $('.datepicker-here-visible').datepicker({
        language: 'fr',
        inline: true,
        minDate: new Date() // Now can select only dates, which goes after today
    });

    $('.datepicker-here').datepicker({
        language: 'fr',
        minDate: new Date() // Now can select only dates, which goes after today
    });

    $('#user-icon').click(function(){

        if ($(this).children('i').hasClass('fa-chevron-right')) {
            $(this).children('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }else{
            $(this).children('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        }

        $('#user-menu').css({'top': $('#user-icon').offset().top + 25, 'left' : $('#user-icon').offset().left }).slideToggle();

    });

    $('#profil-picture-link').click(function(){
        $(this).next('input').slideToggle();
    });

    // INFORMATIONS FOMULAIRES MULTIPLES

    // Selectionne le main topic si un sous element est selectionné
    $('.sub-input').click(function (e) {
        $(this).parent().parent().parent().children('label').children('.main-input').prop('checked', true);
    });

    // Déselectionne les sous éléments si le main topic est déselectionné
    $('.main-input').click(function (e) {

        if ($(this).is(':checked') == false)
        {
            $(this).parent().parent().children('div').children('label').children('input[type="checkbox"]').each(function(e){
                $(this).prop('checked', false);
            })
        }
    });

    $('.select-usercritere').click(function(){
        $(this).parent().parent().children('select').toggle();
    });

    if ($('.select-usercritere').length != 0) 
    {
        $('.select-usercritere:checked').each(function(){
            $(this).parent().parent().children('select').toggle();
        });
    }

    var mainInput = $('.main-input').attr('data-name');
    var soloInput = $('.solo-input').attr('data-name');
    var subInput = $('.sub-input').attr('data-name');
    var subInput1 = $('.sub-input-1').attr('data-name');
    var subInput2 = $('.sub-input-2').attr('data-name');
    

    // Effectue les controles sur les selections avant envoie du formulaire
    $('form.informations').on('submit', function(e){

        if ($('.required-validation').length != 0) 
        {
            if ($('.required-validation:checked').length == 0) 
            {
                e.preventDefault();
                sweetAlert("Oups...", "Veuillez cocher la certification sur l'honneur", "error");     
            }
        }

        if ($('.select-usercritere').length != 0) 
        {
            $('.select-usercritere:checked').each(function(){
                if ($(this).parent().parent().children('select').val() == "") 
                {
                    e.preventDefault();
                    sweetAlert("Oups...", "Valeur manquante" , "error");     
                }
            });
        }

        if ($('.solo-input').length != 0) 
        {
            if ($('input.solo-input:checked').length == 0) 
            {
                e.preventDefault();
                sweetAlert("Oups...", "Selection manquante : " + soloInput, "error");     
            }
           
        }
        
        if ($('.main-input').length != 0 && $('.sub-input').length != 0)
        {
            if ($('input.main-input:checked').length == 0 && $('input.sub-input:checked').length == 0 )
            {
               e.preventDefault();
               sweetAlert("Oups...", "Selection manquante : " + mainInput + ", " + subInput, "error");
            }
            else if ($('input.main-input:checked').length == 0)
            {
               e.preventDefault();
               sweetAlert("Oups...", "Selection manquante : " + mainInput, "error");
            }
            else if ($('input.sub-input:checked').length == 0)
            {
                e.preventDefault();
                sweetAlert("Oups...", "Selection manquante : " + subInput, "error");
            }
            else if ($('input.sub-input-1:checked').length == 0 && $('input.sub-input-2:checked').length != 0 ) 
            {
                e.preventDefault();
                sweetAlert("Oups...", "Selection manquante : " + subInput1, "error");
            }
            else if ($('input.sub-input-1:checked').length != 0 && $('input.sub-input-2:checked').length == 0) 
            {
                e.preventDefault();
                sweetAlert("Oups...", "Selection manquante : " + subInput2, "error");            
            }
        }


    });

});

$(window).on('resize', function(){

    $('#user-menu').css({'top': $('#user-icon').offset().top + 25, 'left' : $('#user-icon').offset().left });

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


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
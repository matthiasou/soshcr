$(document).ready(function(){
    
// STEP1
    
    // Check a la validation du formulaire si tout est checké
    $('.user-critere-form').submit(function(event){

        if ($('input[type="checkbox"]').length > 0)
        {
            // vérifie les checkbox 
            var checkboxChecked = 0;
            $('input[type="checkbox"]').each(function(e){
                if ($(this).is(':checked') && !$(this).hasClass('required-validation'))
                {
                    checkboxChecked++;
                }
            });
        }

        if ($('input[type="radio"]').length > 0)
        {
            var radioChecked = 0;
            $('input[type="radio"]').each(function(e){
                if ($(this).is(':checked'))
                {
                    radioChecked++;
                }
            });  
        }
        
        if (typeof checkboxChecked != "undefined" &&  checkboxChecked == 0 || typeof radioChecked != "undefined" && radioChecked == 0)
        {
           sweetAlert("Erreur :", "Veuillez sélectionner au moins un champ", "error");
           event.preventDefault();
        }

        // vérifie les checkbox avec sous elements
        $('input[type="checkbox"]:checked').each(function(e){
            
            var attributes = $(this).parent().parent().find('.sub-element');
            if (attributes.length > 0)
            {
                if (attributes.find('input').length > 0 && attributes.find('input:checked').length == 0 || attributes.find('option:selected').text() == "--")
                {
                   sweetAlert("Erreur :", "Veuillez sélectionner un sous élélent", "error");
                   event.preventDefault();
                }     
            }
        });

    });

    if ($('input[type="checkbox"]:checked').length > 0)
    {
        $('input[type="checkbox"]:checked').parent().find('.validate').show();
        $('input[type="checkbox"]:checked').parent().find('img').addClass('validated');
        var attributes = $('input[type="checkbox"]:checked').parent().parent().children('.sub-element');
        if (attributes.length > 0)
        {
            attributes.removeClass('hidden');            
        }
    }

    $('input[type="checkbox"]').on('change',function(e){
        var attributes = $(this).parent().parent().children('.sub-element');
        if ($(this).is(':checked'))
        {
            $(this).parent().find('img').addClass('validated');
            $(this).parent().find('.validate').show();
            if (attributes.length > 0)
            {
                attributes.removeClass('hidden');
            }
        }
        else
        {
            $(this).parent().find('img').removeClass('validated');
            $(this).parent().find('.validate').hide();
            if (attributes.length > 0)
            {
                if (attributes.is('select'))
                {
                    attributes.val('0');
                }
                if (attributes.find('input').is('input:checkbox'))
                {
                    attributes.find('input').prop('checked', false);
                }
                attributes.addClass('hidden');
            }
        }
    });

    $('input[type="radio"]').on('change',function(e){
        $('input[type="radio"]').parent().find('img').removeClass('validated');
        $('input[type="radio"]').parent().find('.validate').hide();

        $(this).parent().find('img').addClass('validated');
        $(this).parent().find('.validate').show();
    });


    if ($('input[type="radio"]:checked').length > 0)
    {
        $('input[type="radio"]:checked').parent().find('.validate').show();
        $('input[type="radio"]:checked').parent().find('img').addClass('validated');
    }

    $('.select-usercritere').click(function(e){
        $(this).parent().parent().find('select').show();
    });

    $('#add-disponibilite-periode').click(function(){

        if ($('.datepicker-here').length == 0) 
        {
            $(this).parent().parent().prepend(''+
                '<div class="input-group" style="width:100%">'+
                    '<input type="text" required data-range="true" name="disponibilite[]" data-multiple-dates-separator=" - " class="form-control datepicker-here">'+
                    '<div class="input-group-btn">'+
                        '<button class="btn btn-default remove-disponibilite" type="button">'+
                            '<i class="fa fa-minus" ></i>'+
                        '</button>'+
                    '</div>'+
                '</div>');   
            $('.datepicker-here').last().trigger('click');
        }
        else
        {
            $('.datepicker-here').last().parent().after(''+
                '<div class="input-group" style="width:100%">'+
                    '<input type="text" required data-range="true" name="disponibilite[]" data-multiple-dates-separator=" - " class="form-control datepicker-here">'+
                    '<div class="input-group-btn">'+
                        '<button class="btn btn-default remove-disponibilite" type="button">'+
                            '<i class="fa fa-minus" ></i>'+
                        '</button>'+
                    '</div>'+
                '</div>');    
        }

        $('.datepicker-here').datepicker({
            language: 'fr',
            autoClose: true,
            minDate: new Date() // Now can select only dates, which goes after today
        });
        
        $('.remove-disponibilite').click(function(){
            $(this).parent().parent().remove();
        });
    });

    $('#add-disponibilite-jour').click(function(){

        if ($('.datepicker-here').length == 0) 
        {
            $(this).parent().parent().prepend(''+
                '<div class="input-group" style="width:100%">'+
                    '<input type="text" required data-range="false" name="disponibilite[]" data-multiple-dates-separator=" - " class="form-control datepicker-here">'+
                    '<div class="input-group-btn">'+
                        '<button class="btn btn-default remove-disponibilite" type="button">'+
                            '<i class="fa fa-minus" ></i>'+
                        '</button>'+
                    '</div>'+
                '</div>');   
            $('.datepicker-here').last().trigger('click');
        }
        else
        {
            $('.datepicker-here').last().parent().after(''+
                '<div class="input-group" style="width:100%">'+
                    '<input type="text" required data-range="false" name="disponibilite[]" data-multiple-dates-separator=" - " class="form-control datepicker-here">'+
                    '<div class="input-group-btn">'+
                        '<button class="btn btn-default remove-disponibilite" type="button">'+
                            '<i class="fa fa-minus" ></i>'+
                        '</button>'+
                    '</div>'+
                '</div>');    
        }

        $('.datepicker-here').datepicker({
            language: 'fr',
            autoClose: true,
            minDate: new Date() // Now can select only dates, which goes after today
        });
        
        $('.remove-disponibilite').click(function(){
            $(this).parent().parent().remove();
        });
    });

});

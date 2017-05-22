$(document).ready(function(){
    
    $('.informations').submit(function(event){
        var errors = [];
        $('input[data-name="contrat"]:checked').each(function(e){
            var attributes = $(this).parent().parent().children('div');
            if (attributes.find('input:checked').length == 0)
            {
               sweetAlert("Erreur :", "Veuillez saisir tous les champs !", "error");
               event.preventDefault();
            }
            
        });
    });

    if ($('input[data-name="contrat"]:checked').length > 0)
    {
        var attributes = $('input[data-name="contrat"]:checked').parent().parent().children('div');
        $('input[data-name="contrat"]:checked').parent().parent().find('.validate').show();
        attributes.removeClass('hidden');
    }

    $('input[data-name="contrat"]').on('change',function(e){
        var attributes = $(this).parent().parent().children('div');
        if ($(this).is(':checked'))
        {
            $(this).parent().parent().find('.validate').show();
            attributes.removeClass('hidden');
        }
        else
        {
            $(this).parent().parent().find('.validate').hide();
            attributes.addClass('hidden');
        }
    });

});

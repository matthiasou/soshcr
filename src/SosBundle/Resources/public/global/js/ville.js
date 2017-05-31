$(document).ready(function(){
    
    // get les nom des villes via google
    google.maps.event.addDomListener(window, 'load', initialize);

    $('#ville-submit').on('submit',function(e){
        if($('#ville').val() == ''){
            e.preventDefault();
            sweetAlert("Ouuups","Veuillez rentrer une ville !", "error");
        }
    })

});

function initialize() {

    var input = document.getElementById('ville');
    var autocomplete = new google.maps.places.Autocomplete(input);

}


$(document).ready(function() {

    //$('.button, input[type="button"], input[type="submit"]').button();

    $( "#summoner-search form" ).submit(function( event ) {
        event.preventDefault();
        var s_name = $('#search-name').val();
        var region = $('#search-region').val();
        var base_url = $('#base-url').val();
        window.location.href = base_url+'summoner/'+region+'/'+s_name;
    });

    $('#nav').ghNav();
    $('.label-required').tooltip();
});

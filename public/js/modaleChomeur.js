$(document).ready(function () {
    $(".boutonModal").on('click', function (event) {
        //ATTENTION: le event.preventDefault() est nécessaire sinon la modale ne s'affiche pas
        event.preventDefault();

        $.get($(this).attr('href'), function (data) {
            $("#modaleChomeur").html(data).dialog({
                height: 300,
                width: 600,
                modal: false
            });
        });
    });
});

console.log("Tout est beau");

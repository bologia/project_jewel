$(document).on('click','.ajoutpan', function() {
    const button = $(this);
    const url = $(this).data('url');

    $(button).html('Loading...'); //loading

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            $(button).parent().parent().find('.d-none').removeClass('d-none');
            $(button).parent().addClass('d-none');
            $(button).html('Ajouter au Panier'); //il faut remettre sinon il reste sur loading
        }
    });
})

$(document).on('click','.deletpropan', function() {
    const button = $(this);
    const url = $(this).data('url');

    $(button).html('Loading...'); //loading

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            $(button).parent().parent().find('.d-none').removeClass('d-none');
            $(button).parent().addClass('d-none');
            $(button).html('Retirer du Panier'); //il faut remettre sinon il reste sur loading
        }
    });
})
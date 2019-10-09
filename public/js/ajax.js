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

// pour la quantité

$(document).on('change', '#encule', function() {
    const quantite = $(this).val();
    const url = $(this).data('url');

    $.ajax({
        url: url,
        type: "POST",
        data: {
            qte: quantite
        },
        success: function(){
            prixTotal();      // c'est la fonction du dessous
        }
    });
})

// calcul js qté prix

function prixTotal(){
    var prixtotal = 0;
    $('.prix').each(function() {
        var quantite = $(this).parent().parent().parent().find('#encule').val(); // on va chercher la quantité en parcourant les div
        var prix = parseFloat($(this).text());
        prixtotal = prixtotal + (prix * quantite);
    });
    $('.prixtotal').html(`${prixtotal} €`); // concaténation `${}`
    $('.postprix').val(prixtotal);
}

var test = prixTotal();

// Pour le voir plus

$(document).on('click', '#voirplus', function() {
    const button = $(this);
    // Départ (Au début 20 puis 40,60,...)
    var offset = parseInt($('#produits-page').val());
    // Le lien où aller, avec les GET
    var url = $(this).data('url');

    $.ajax({
        url: url,
        type: "POST",
        data: {
            start: offset 
        },
        success: function(response){
            // Augmentation de 20 du point de départ
            offset += response.limit;
            // Pour qu'on le récupère la prochaine fois au début de la fonction
            $('#produits-page').val(offset);
            // On ajoute les produits à afficher à la suite
            $('.proticles').append(response.render);
            // Si le nombre de produit est inférieur à la limite alors on est à la fin donc on dégage le button
            if(response.size < response.limit){
                $(button).remove();
            }
        }
    });
})
$('.one').show();

$('.item-one').on('click', function(e) {
    e.preventDefault();
    $('.one').show();
    $('.two').hide();
    $('.three').hide();
    $('.four').hide();
    $('.five').hide();
    $('.six').hide();
});

$('.item-two').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').show();
    $('.three').hide();
    $('.four').hide();
    $('.five').hide();
    $('.six').hide();
});

$('.item-three').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').show();
    $('.four').hide();
    $('.five').hide();
    $('.six').hide();
});

$('.item-four').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').hide();
    $('.four').show();
    $('.five').hide();
    $('.six').hide();
});

$('.item-five').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').hide();
    $('.four').hide();
    $('.five').show();
    $('.six').hide();
    $('#collapseSambutan').addClass("show");
});

$('.item-six').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').hide();
    $('.four').hide();
    $('.five').hide();
    $('.six').show();
    $('#collapsePanitia').addClass("show");
});
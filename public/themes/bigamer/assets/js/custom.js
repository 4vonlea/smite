$('.one').show();

$('.item-one').on('click', function(e) {
    e.preventDefault();
    $('.one').show();
    $('.two').hide();
    $('.three').hide();
    $('.four').hide();
    $('.five').hide();
    $('.six').hide();
    $('.seven').hide();
});

$('.item-two').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').show();
    $('.three').hide();
    $('.four').hide();
    $('.five').hide();
    $('.six').hide();
    $('.seven').hide();
});

$('.item-three').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').show();
    $('.four').hide();
    $('.five').hide();
    $('.six').hide();
    $('.seven').hide();
});

$('.item-four').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').hide();
    $('.four').show();
    $('.five').hide();
    $('.six').hide();
    $('.seven').hide();
});

$('.item-five').on('click', function(e) {
    e.preventDefault();
    $('.one').hide();
    $('.two').hide();
    $('.three').hide();
    $('.four').hide();
    $('.five').show();
    $('.six').hide();
    $('.seven').hide();
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
    $('.seven').hide();
    $('#collapsePanitia').addClass("show");
});

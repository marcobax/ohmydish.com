// https://extendsclass.com/javascript-minifier.html
$(document).ready(function(){
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    if ($(window).scrollTop() > 100) {
        $('#back-to-top').show();
    }

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100 && ($(this).scrollTop() < ($(document).height() - 900))) {
            $('#back-to-top').show();
        } else {
            $('#back-to-top').hide();
        }
    });

    $('#back-to-top').click(function () {
        $('body,html').scrollTop(0);
        return false;
    });

    $('.ingredients li, .kitchen_equipment li').click(function(){
        $(this).toggleClass('checked');
    });

    $('.save-recipe').click(function(){
        var self = $(this);
        $.ajax({
            url: $(this).data('save-url'),
            method: 'POST',
            success: function(result) {
                if ('true' === result) {
                    $(self).find('.recipe-saved').removeClass('d-none');
                    $(self).find('.recipe-not-saved').addClass('d-none');
                    $('.recipe-alert').show();
                    setTimeout(function(){
                        $('.recipe-alert').fadeOut();
                    }, 4000)
                } else if('false' === result) {
                    $(self).find('.recipe-not-saved').removeClass('d-none');
                    $(self).find('.recipe-saved').addClass('d-none');
                    $('.recipe-alert').fadeOut();
                } else if('not-logged-in' === result) {
                    window.location.replace($('body').data('login-url'));
                }
            }
        });
    });

    $('.confetti-button').click(function(e){
        e.preventDefault();

        $(this).removeClass('animate');
        $(this).addClass('animate');

        setTimeout(function () {
            $(this).removeClass('animate');
            window.location.href = e.target;
        }, 750);

        return false;
    });

    $('#s').on('keyup focus', delay(function(e){
        if (e.keyCode !== 27) {
            if ($(this).val().length > 1) {
                $.ajax({
                    url: $('body').data('search-url'),
                    type: 'post',
                    data: {
                        's': $(this).val(),
                        'return_json': true
                    },
                    dataType: 'json'
                }).done(function (data) {
                    $('.suggestionbox').html(data.results);
                    showSuggestionBox();
                });
            } else {
                hideSuggestionBox();
            }
        }
    }, 400));

    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            hideSuggestionBox();
            $('#s').blur();
        }
    });

    $('.suggestionbox, #s').on('click', function(e) {
        e.stopPropagation();
    });

    $('body').on('click', function(){
        hideSuggestionBox();
    });

    function showSuggestionBox()
    {
        var sb = $('.suggestionbox');
        if ($(sb).hasClass('d-none')) {
            $(sb).removeClass('d-none').hide().fadeIn(200);
        }
    }

    function hideSuggestionBox()
    {
        var sb = $('.suggestionbox');
        if (!$(sb).hasClass('d-none')) {
            $(sb).fadeOut(300, function(){
                $(this).html('').addClass('d-none');
            });
        }
    }

    $('.star-rating').starRating({
        minRating: 1,
        totalStars: 5,
        initialRating: $(this).data('rating'),
        starSize: 30,
        useFullStars: true,
        forceRoundUp: true,
        activeColor: 'orange',
        ratedColor: 'orange',
        ratedColors: ['orange','orange','orange','orange','orange'],
        useGradient: false,
        callback: function(currentRating, $el) {
            $.ajax({
                url: $el.data('url'),
                data: {
                    'rating': currentRating
                },
                method: 'GET',
                success: function(result) {
                    $('#total-votes').text(result.rating_label);
                }
            });
        }
    });

    /*
    const site_logo = $('#site-logo');

    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        switchToDark();
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (e.matches) {
            switchToDark();
        } else {
            switchToLight();
        }
    });

    function switchToDark()
    {
        let dark = true;
        setTheme('dark');
        themeChangeHandlers.push(theme=>dark=theme==='dark');

        $('.bg-light').removeClass('bg-light').addClass('bg-dark');
        $('.bg-grey').removeClass('bg-grey').addClass('bg-dark');
        $('.navbar-light').removeClass('navbar-light').addClass('navbar-dark');
        $('.text-dark').removeClass('text-dark').addClass('text-light');
        $('.with-stripe').addClass('with-stripe-white');
        site_logo.attr('srcset', site_logo.data('dark'));
    }

    function switchToLight()
    {
        let light = true;
        setTheme('light');
        themeChangeHandlers.push(theme=>light=theme==='light');

        $('.bg-dark').removeClass('bg-dark').addClass('bg-light');
        $('.bg-grey').removeClass('bg-grey').addClass('bg-dark');
        $('.navbar-dark').removeClass('navbar-dark').addClass('navbar-light');
        $('.text-light').removeClass('text-light').addClass('text-dark');
        $('.with-stripe-white').removeClass('with-stripe-white');
        site_logo.attr('srcset', site_logo.data('light'));
    }
     */
});

function delay(fn, ms) {
    var timer = 0
    return function(...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
}
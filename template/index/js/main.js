$(document).ready(function () {
    /* закрытие прямого эфира */
    $('.live__icon_close').on('click', function(){
        $('.live_active').fadeOut();
    });

    /* начальная высота тгб */
    $('.banner-toggleable__text-small').each(function (i, e) {
        var text = $(e);
        var tgb = text.parents('.banner-toggleable');
        var offset = +text.offset().top - +tgb.offset().top + 50;
        tgb.css('height', offset + 'px');
    });

    /* открытие баннера */
    $('.banner-toggleable__button').on('click', function(){
        $(this).parent().parent('.banner-toggleable').toggleClass('height-full');
    });

    /* закрытие баннера внизу страницы */
    $('.banner-bottom__close').on('click', function(){
        $(this).parent().fadeOut();
    });

    /* фиксация заголовка к верху страницы */
    var header          = $('.header'),
        headerHolder    = $('.header-holder'),
        headerOffsetTop = headerHolder.offset().top,
        windowScrollTop = $(window).scrollTop();

    function fixToTop() {
        headerOffsetTop = headerHolder.offset().top;
        windowScrollTop = $(window).scrollTop();

        if (windowScrollTop > headerOffsetTop) {
            header.addClass('fixed');
            $('#progress').addClass('fixed');
            $('#InnerCont').addClass('fixed-menu');
        } else {
            header.removeClass('fixed');
            $('#progress').removeClass('fixed');
            $('#InnerCont').removeClass('fixed-menu');
        }
    }

    $(window).on('resize', function () {
        headerOffsetTop = header.offset().top;
        windowScrollTop = $(window).scrollTop();
        fixToTop();
    });

    $(window).on('scroll', function () {
        fixToTop();
    });

    /* боковое меню в мобильном виде */
    var menu = $('.header__menu');
    $('#openSidebarMenu').on('click', function () {
        menu.toggleClass('open');
    });
    /* скрывается при клике за пределами меню */
    $(document).mouseup(function (e) {
        if (e.target != menu[0] && e.target !== $('#openSidebarMenu').get(0) && !menu.has(e.target).length) {
            menu.removeClass('open');
        }
    });

    /* прогресс чтения страницы */
    $(window).on("scroll resize", function() {
        $progress = $('progress');
        if($progress.length === 0) return;
        var o = $(window).scrollTop() / ($(document).height() - $(window).height());
        $(".progress-bar").css({
            "width": (100 * o | 0) + "%"
        });
        $progress[0].value = o;
    })

    /* прогресс чтения статьи */
//    var article = $('#article'),
//        articleOffsetTop = article.offset().top,
//        articleHeight = article.height();
//    $(window).on("scroll resize", function() {
//        var o = ($(window).scrollTop() + $(window).height()) / articleHeight;
//        $(".progress-bar").css({
//            "width": (100 * o | 0) + "%"
//        });
//        $('#progress')[0].value = o;
//    });
});

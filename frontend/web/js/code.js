(function($){
    $(window).load(function() {

        menuHeight = $('header').first().innerHeight();

        $('.bigSlider ul').bxSlider({
            slideHeight: 540,
            mode: 'fade'
        });

        $('.newsSlider ul').bxSlider({
            slideHeight: 355,
            mode: 'fade'
        });

        $('header .menu a').click(function(){
            var target = $(this).attr('href');
            if(target.indexOf("#") === 0){
                if($(target).length === 0) window.location.href = '/' + target;
                var $iasBlock = $('.list-view .more-news');
                var $iasBlockNone = $('.list-view .ias-noneleft');
                var tempHeight = ($iasBlock.length === 0) ? 85 : 0;
                tempHeight = ($iasBlockNone.length === 0) ? tempHeight : 0;
                $('html, body').animate({
                    scrollTop: $(target).offset().top - menuHeight + tempHeight
                }, 500);
            }
            return false;
        });

        $('header .logo').click(function(){
            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 500, function(){
                // window.location.href = '/';
            });
            return false;
        });

        $('.news-element').each(function(index, el) {
            var fullHeight = $(this).find('.full-content').first().innerHeight();
            if(fullHeight <= 216) $(this).find('.open-close').hide();
        });

        $(document).on('click', '.toggle-button', function(event) {
            var $target = $('#' + $(this).attr('data-target'));
            var smallHeight = 216;
            var fullHeight = $target.find('.full-content').first().innerHeight();
            if($(this).hasClass('toggle-show')) {
                $(this).removeClass('toggle-show');
                $(this).addClass('toggle-hide');
                $(this).find('span').first().text("Згорнути");
                $target.animate({
                    height: fullHeight
                }, 500);
                $('html, body').animate({
                    scrollTop: $target.offset().top - menuHeight
                }, 500);
            } else {
                $(this).removeClass('toggle-hide');
                $(this).addClass('toggle-show');
                $(this).find('span').first().text("Читати");
                $target.animate({
                    height: smallHeight
                }, 500);
                $('html, body').animate({
                    scrollTop: $target.offset().top - menuHeight
                }, 500);
            }
        });

        $('.bigSlider .link').each(function(index, el) {
            var $target = $('#' + $(this).attr('data-target'));
            if($target.length === 0) {
                $(this).text('Шукайте у більше новин');
            }
        });

        $(document).on('click', '.bigSlider .link', function(event) {
            var $target = $('#' + $(this).attr('data-target'));
            if($target.length === 0) {
                var $iasBlock = $('.list-view .more-news');
                var tempHeight = ($iasBlock.length === 0) ? 0 : 85;
                $('html, body').animate({
                    scrollTop: $('#about').offset().top - menuHeight - tempHeight
                }, 500);
            } else {
                $('html, body').animate({
                    scrollTop: $target.offset().top - menuHeight
                }, 500);
            }
        });

        $('.site-error').height($(window).innerHeight() - menuHeight - $('.site-footer').innerHeight() - 10);
        $(window).resize(function(event) {
            $('.site-error').height($(window).innerHeight() - menuHeight - $('.site-footer').innerHeight() - 10);
        });
  });
})(jQuery);
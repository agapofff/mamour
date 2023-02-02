jQuery(document).ready(function ($) {
    
    var ajaxDataSent = new $.Deferred();
    
    $.fn.isInViewport = function(offset = 0) {
        var elementTop = $(this).offset().top,
            elementBottom = elementTop + $(this).outerHeight(),
            viewportTop = $(window).scrollTop() - offset,
            viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

    
    
    // индикатор загрузки
    loading = function (show = true) {
        if (show) {
            $('#loader').show();
        } else {
            $('#loader').hide();
        }
    }
    $(document).on('pjax:start', function () {
        loading();
    });
    $(document).on('pjax:end', function () {
        loading(false);
    });
    $('form').on('beforeSubmit', function () {
        loading(false);
    });
    $(window).on('beforeunload', function () {
        loading();
        $('#fade').fadeIn('fast');
        $('.modal').modal('hide');
    });
    $(document).on('click', '#loader', function () {
        loading(false);
    });
    loading(false);
    
    
    // lazy loading
    lazyload();
    $(document).on('pjax:end', function () {
        lazyload();
    });
        

    // маски
    $.mask.definitions['_'] = "[0-9]";
    setPhoneMask = function (countryCode = '+7', phoneMask = '(___) ___-__-__') {
        $('.phone-mask').mask((countryCode + ' ' + phoneMask), {
            autoclear: false
        });
    }
    setPhoneMask();
    
    
    // клики по хэш-ссылкам
    if (location.href.includes('#')) {
        $('a[href="#' + location.href.split('#')[1] + '"]').trigger('click');
    }


    // уведомления
    toastr.options = {
        tapToDismiss: true,
        positionClass: 'toast-bottom-right',
        newestOnTop: false,
        preventDuplicates: true,
        escapeHtml: false,
        iconClass: 'd-none',
        timeOut: 3000,
    };


    // модальные окна
    $(document).on('click', '[data-toggle="lightbox"], .lightbox', function (e) {
        e.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            loadingMessage: false,
            disableExternalCheck: false,
            onShow: function () {
                this._$modalDialog.prepend('<div class="modal-loader position-absolute top-0 left-0 right-0 bottom-0 d-flex align-items-center justify-content-center bg-white" style="z-index: 3"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Загрузка...</span></div></div>');
            },
            onShown: function () {
                setTimeout(function () {
                    $('.modal-loader').remove();
                }, 500);
            }
        });
    });


    // popover
    $('[data-toggle="popover"]').popover({
        html: true,
        trigger: 'focus',
        sanitize: false,
        container: 'body',
        placement: 'auto',
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header px-1 py-0_7"></h3><div class="popover-body pl-1 pt-1 pr-1 pb-0"></div></div>',
        content: function () {
            if ($(this).is('[data-element]')) {
                return $($(this).attr('data-element')).html();
            } else {
                return $(this).attr('data-content');
            }
        },
    });
    // }).on('shown.bs.popover', function () {
        // generateOwlCarousel();
    // });

    
    // переключатель способов доставки
    $('input[name="shipping_type_switcher"]').click(function () {
        $(this).tab('show');
        $(this).removeClass('active');
        $('#order-shipping_type_id').val($(this).val()).trigger('change');
    });



    // формы

    $(document)
        .on('beforeValidate', 'form.disabling', function () {
            $(this).attr('disabled', 'disabled');
        })
        .on('afterValidate', 'form.disabling', function () {
            $(this).removeAttr('disabled');
        })
        .on('beforeSubmit', 'form.disabling', function () {
            $(this).attr('disabled', 'disabled');
        });
        
    $(document).on('submit', 'form.disabled', function (e) {
        e.preventDefault();
        return false;
    });

    $(document).on('submit', 'form.ajax', function (e) {
        e.preventDefault();
        var $form = $(this),
            url = $form.attr('action'),
            type = $form.attr('method'),
            data = $form.serialize();
        sendAjaxData($form, url, data, type, true);
    });

    // ajax-кнопки
    $(document).on('click', 'a.ajax, button.ajax', function (e) {
        e.preventDefault();
        var $link = $(this),
            url = $link.attr('data-target') ? $link.attr('data-target') : $link.attr('data-remote') ? $link.attr('data-remote') : $link.attr('href');
            if (url[0] === '/') url = location.protocol + '//' + location.hostname + url;
        sendAjaxData($link, url);
    });

    sendAjaxData = function ($element, action, params = [], method = 'get', isForm = false) {
        loading();
        $.ajax({
            url: action,
            type: method,
            data: params,
            success: function (data) {
                switch (data.status) {
                    case 'warning': toastr.warning(data.message); break;
                    case 'danger': toastr.error(data.message); break;
                    case 'error': toastr.error(data.message); break;
                    case 'success': toastr.success(data.message); break;
                    case 'info': toastr.info(data.message); break;
                }
                if (data.script && data.script != '') {
                    $('body').append('<script type="text/javascript" class="serverscript">' + data.script + ' $(".serverscript").remove();</script>');
                }
                if (data.status == 'success') {
                    $element.find('input[type="text"]').val('');
                    $('.modal').modal('hide');
                }
                if (data.error && data.error != '') {
                    console.log(data.error);
                }
            },
            error: function (data) {
                toastr.error('Ошибка! Попробуйте еще раз чуть позже');
                console.log(data);
                return false;
            },
            complete: function () {
                loading(false);
                ajaxDataSent.resolve();
            }
        });
    }
    
    $(document).on('submit', '#subscribe', function (e) {
        e.preventDefault();
        var $form = $(this),
            url = $form.attr('action'),
            message = $form.data('message'),
            data = $form.serialize();
        loading();
        toastr.success(message);
        $.post(url, data);
        loading(false);
    });
    
    
    productImageZoom = function () {
        $('.zoom').each(function () {
            var url = $(this).data('url'),
                img = $(this).find('.product-image');
            $(this).zoom({
                url: url,
                target: $('body').is('.desktop') ? '#product-zoom-container' : false,
                onZoomIn: function () {
                    $('#product-zoom-container').css('opacity', 1);
                    if ($('body').is('.mobile')) {
                        $('.product-image').css('opacity', 0);
                    }
                },
                onZoomOut: function () {
                    $('#product-zoom-container').css('opacity', 0);
                    if ($('body').is('.mobile')) {
                        $('.product-image').css('opacity', 1);
                    }
                }
            });
        });
    }
    productImageZoom();


    // OWL

    owlCarouselInit = function (item) {
        var itemCount = ($(item).attr('data-items')) ? $(item).attr('data-items').split('-') : [1,1,1,1,1,1],
            owlAutoPlay = ($(item).attr('data-autoplay') == 'true' || $(item).hasClass('owl-autoplay')) ? true : false,
            owlAutoPlayTimeout = ($(item).attr('data-speed')) ? parseFloat($(item).attr('data-speed')) : 5000,
            owlAutoplayHoverPause = ($(item).attr('data-hoverstop') == 'true' || $(item).hasClass('owl-hoverstop')) ? true : false,
            owlAutoHeight = ($(item).attr('data-autoheight') == 'true' || $(item).hasClass('owl-autoheight')) ? true : false,
            owlAutoWidth = ($(item).attr('data-autowidth') == 'true' || $(item).hasClass('owl-autowidth')) ? true : false,
            owlNav = ($(item).attr('data-nav') == 'true' || $(item).hasClass('owl-arrows')) ? true : false,
            owlDots = ($(item).attr('data-dots') == 'true' || $(item).hasClass('owl-dots')) ? true : false,
            owlLazyLoad = ($(item).attr('data-lazy') == 'true' || $(item).hasClass('owl-lazyload')) ? true : false,
            owlAnimateIn = ($(item).attr('data-animatein')) ? $(item).attr('data-animatein') : false,
            owlAnimateOut = ($(item).attr('data-animateout')) ? $(item).attr('data-animateout') : false,
            owlCenter = ($(item).attr('data-center') == 'true' || $(item).hasClass('owl-center')) ? true : false,
            owlLoop = ($(item).attr('data-loop') == 'true' || $(item).hasClass('owl-loop')) ? true : false,
            owlMargin = ($(item).attr('data-margin')) ? parseFloat($(item).attr('data-margin')) : false,
            owlRandom = ($(item).attr('data-random') == 'true' || $(item).hasClass('owl-random')) ? true : false,
            owlMouseDrag = ($(item).attr('data-mouse-drag') == 'false' || $(item).hasClass('noMouseDrag')) ? false : true,
            owlTouchDrag = ($(item).attr('data-touch-drag') == 'false' || $(item).hasClass('noTouchDrag')) ? false : true,
            owlPullDrag = ($(item).attr('data-pull-drag') == 'false' || $(item).hasClass('noPullDrag')) ? false : true,
            owlFreeDrag = ($(item).attr('data-free-drag') == 'true' || $(item).hasClass('freeDrag')) ? true : false;
            if ($(item).hasClass('owl-fade')) {
                owlAnimateIn = 'fadeIn';
                owlAnimateOut = 'fadeOut';
            }
            if ($(item).hasClass('owl-autoplay')) {
                owlAutoPlayTimeout = 3000;
            }
        $(item).owlCarousel({
            items: parseFloat(itemCount[0]),
            responsive:{
                0:{
                    items: parseFloat(itemCount[0])
                },
                480:{
                    items: parseFloat(itemCount[1])
                },
                768:{
                    items: parseFloat(itemCount[2])
                },
                992:{
                    items: parseFloat(itemCount[3])
                },
                1200:{
                    items: parseFloat(itemCount[4])
                },
                1440:{
                    items: parseFloat(itemCount[5])
                }
            },
            responsiveBaseElement: 'body',
            autoplay: owlAutoPlay,
            autoplayTimeout: owlAutoPlayTimeout,
            autoplayHoverPause: owlAutoplayHoverPause,
            autoHeight: owlAutoHeight,
            autoWidth: owlAutoWidth,
            nav: owlNav,
            dots: owlDots,
            lazyLoad: owlLazyLoad,
            animateIn: owlAnimateIn,
            animateOut: owlAnimateOut,
            center: owlCenter,
            loop: owlLoop,
            margin: owlMargin,
            checkVisibility: false,
            mouseDrag: owlMouseDrag,
            touchDrag: owlTouchDrag,
            pullDrag: owlPullDrag,
            freeDrag: owlFreeDrag,
            navText: [
                '',
                '',
            ],
            onInitialize: function (element) {
                if (owlRandom === true) {
                    $(item).children().sort(function () {
                        return Math.round(Math.random()) - 0.5;
                    }).each(function () {
                        $(this).appendTo($(item));
                    });
                }
                // imageZoom();
            },
            onDragged: function () {
                // imageZoom();
            },
            onChanged: function (event) {
                $(item).attr('data-item', event.item.index ? event.item.index-1 : event.item.index);
                // imageZoom();
            },
        });
    }

    generateOwlCarousel = function () {
        $('.owl-carousel').each(function () {
            owlCarouselInit($(this));
        });
    }

    generateOwlCarousel();
    
    owlGoTo = function (id, slide, speed = 300) {
        $(id).trigger('to.owl.carousel', [slide, speed]);
    }
    

    // SLICK
    $('.product-thumbnails')
        .slick({
            slidesToShow: 3,
            vertical: true,
            verticalSwiping: true,
            infinite: false,
            swipeToSlide: false,
            arrows: false,
        }).on('wheel', (function(e) {
            e.preventDefault();
            if (e.originalEvent.deltaY < 0) {
                $(this).slick('slickPrev');
            } else {
                $(this).slick('slickNext');
            }
        }));


    // выпадающее меню
    // $(document).on('click.bs.dropdown.data-api', '.dropdown-menu', function (event) {
        // event.stopPropagation();
    // });
    
    
    // MAIN MENU
    
    $('#menu')
        .on('show.bs.modal', function (event) {
            // if (event.target.id) {
                // $('#nav')
                    // .removeClass('navbar-light bg-white')
                    // .addClass('navbar-dark bg-gray-900');
            // }
        })
        .on('hide.bs.modal', function (event) {
            // if (event.target.id == 'menu') {
                // $('#nav')
                    // .removeClass('navbar-dark bg-gray-900')
                    // .addClass('navbar-light bg-white');
            // }
        });
        
        


    // выбор размера
    // $(document).on('click', '.dropdown-change-select', function () {
        // var $element = $(this).parents('.dropdown').find('button[data-toggle="dropdown"]'),
            // id = $(this).data('id'),
            // val = $(this).data('value'),
            // txt = $(this).text();
            
        // $('select[data-id="' + id + '"]')
            // .val(val)
            // .trigger('change');
            
        // $element
            // .text(txt)
            // .attr('aria-expanded', false)
            // .addClass('changed');
            
        // $element.dropdown('hide');
    // });

    // показ нотификации выбора размера
    // $('.dvizh-cart-buy-button, .product-buy, #product-wishlist').click(function (event) {
        // event.preventDefault();
        // if ($(this).is(':disabled') || $(this).children().is(':disabled')) {
            // $('.select-size-note').show();
            // return false;
        // }
    // });
    

    // wishlist
    $(document).on('click', '.btn-wishlist', function () {
        var $btn = $(this),
            lang = $(this).data('lang'),
            product_id = $(this).data('product'),
            action = $(this).data('action');
        
        $.ajax({
            url: '/' + lang + '/wishlist/' + action, 
            beforeRequest: loading(),
            data: {
                'product_id': product_id
            }, 
            success: function (data) {
                $('.btn-wishlist[data-product="' + product_id + '"]').replaceWith(data);
            },
            complete: function () {
                loading(false);
            }
        });
    });
    
    

    
    
    // cookies
    // setTimeout(function () {
        // $('#cookiesNotification').addClass('show');
        // if ($('#cookiesNotification').data('type') == '3'){
            // var translate = $('#cookiesNotification').height();
            // $('#nav').css({
                // '-webkit-transform': 'translateY(' + translate + 'px)',
                // '-ms-transform': 'translateY(' + translate + 'px)',
                // 'transform': 'translateY(' + translate + 'px)'
            // })
        // }
    // }, 1000);
    
    
    
    // $(document).on('click', '.cart-change-count', function () {
        // var plus = $(this).hasClass('plus'),
            // $row = $(this).parents('.cart-product'),
            // id = $row.attr('data-id'),
            // name = $row.attr('data-name'),
            // price = $row.attr('data-price'),
            // variant = $row.find('.cart-product-variant').text(),
            // currency = $row.attr('data-currency');
            
        // if (plus) {
            // ymAdd(id, name, price, variant, currency);
            // fbqAddToCart(id, name, price, variant, currency);
        // } else {
            // ymRemove(id, name, price, variant, currency);
        // }
    // });

    
});
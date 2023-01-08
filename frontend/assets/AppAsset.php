<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // '//fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,600;0,700;1,100;1,300;1,400;1,600;1,700&display=swap',
        // '//use.fontawesome.com/releases/v5.11.2/css/all.css',
        // 'bootstrap4/bootstrap4-reboot.scss',
        // 'bootstrap4/bootstrap4-grid.scss',
        // 'css/bootstrap4/bootstrap4.scss',
        
        'fonts/CourierNewCyr/stylesheet.css',
        'fonts/CourierNew/stylesheet.css',
        // 'fonts/GothamPro/stylesheet.css',
        
        'css/bootstrap.css',
        'css/owl.carousel.css',
        'css/owl.theme.default.css',
        'css/bootstrap-select.min.css',
        'css/toastr.css',
        // 'css/animate.min.css',
        // 'css/nprogress.css',
        // 'css/ekko-lightbox.css',
        // 'css/fancybox.css',
        'css/slick.css',
        // 'css/slick-theme.css',
        'css/site.css',
    ];
    public $js = [
        // '//use.fontawesome.com/9132c887d9.js',
        'js/popper.min.js',
        'js/bootstrap.min.js',
        
        // 'js/jquery.lazyload.min.js',
        'js/lazyload.min.js',
        
        // 'js/jquery.mask.min.js',
        'js/jquery.maskedinput.min.js',
        'js/bootstrap-select.min.js',
        'js/toastr.min.js',
        // 'js/mask.js',
        'js/owl.carousel.js',
        // 'js/owl.autoplay.js',
        // 'js/owl.lazyload.js',
        // 'js/ekko-lightbox.min.js',
        // 'js/wow.min.js',
        // 'js/jquery.sticky-kit.min.js',
        
        // 'js/ScrollMagic.min.js',
        // 'js/skrollr.min.js',
        // 'https://cdnjs.cloudflare.com/ajax/libs/skrollr/0.6.30/skrollr.min.js',
        
        // 'js/jquery.zoom.js',
        'js/ekko-lightbox.js',
        // 'js/fancybox.umd.js',
        // 'js/nprogress.js',
        'js/slick.js',
        // 'js/jquery.marquee.min.js',
        // 'js/headroom.min.js',
        // 'js/jQuery.headroom.js',
        
        'js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}

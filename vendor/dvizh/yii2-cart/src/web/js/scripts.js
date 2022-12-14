if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.cart = {
    init: function () {

        cartElementsCount = '[data-role=cart-element-count]';
        buyElementButton = '[data-role=cart-buy-button]';
        deleteElementButton = '[data-role=cart-delete-button]';
        truncateCartButton = '[data-role=truncate-cart-button]';

        dvizh.cart.csrf = jQuery('meta[name=csrf-token]').attr("content");
        dvizh.cart.csrf_param = jQuery('meta[name=csrf-param]').attr("content");

        jQuery(document).on('change', cartElementsCount, function () {

            var self = this,
                url = jQuery(self).data('href');

            if (jQuery(self).val() < 0) {
                jQuery(self).val('0');
                return false;
            }

            cartElementId = jQuery(self).data('id');
            cartElementCount = jQuery(self).val();

            dvizh.cart.changeElementCount(cartElementId, cartElementCount, url);
            dvizh.cart.changeElementCost(cartElementId, cartElementCount, url);
        });

        jQuery(document).on('click', buyElementButton, function () {

            var self = this,
                url = jQuery(self).data('url'),
                itemModelName = jQuery(self).data('model'),
                itemId = jQuery(self).data('id'),
                itemCount = jQuery(self).data('count'),
                itemPrice = jQuery(self).data('price'),
                itemOptions = jQuery(self).data('options'),
                itemComment = jQuery(self).attr('data-comment');
				
            dvizh.cart.addElement(itemModelName, itemId, itemCount, itemPrice, itemOptions, url, itemComment);

            return false;
        });

        jQuery(document).on('click', truncateCartButton, function () {

            var self = this,
                url = jQuery(self).data('url');

            dvizh.cart.truncate(url);
            
            return false;
        });

        jQuery(document).on('click', deleteElementButton, function (e) {

            e.preventDefault();

            var self = this,
                url = jQuery(self).data('url'),
                elementId = jQuery(self).data('id');

            dvizh.cart.deleteElement(elementId, url);

            if (lineSelector = jQuery(self).data('line-selector')) {
                jQuery(self).parents(lineSelector).last().hide('slow');
            }

            return false;
        });
        
        jQuery(document).on('click', '.dvizh-arr', this.changeInputValue);
        jQuery(document).on('change', '.dvizh-cart-element-before-count', this.changeBeforeElementCount);
        jQuery(document).on('change', '.dvizh-option-values-before', this.changeBeforeElementOptions);
        jQuery(document).on('change', '.dvizh-option-values', this.changeElementOptions);

        return true;
    },
    elementsListWidgetParams: [],
    jsonResult: null,
    csrf: null,
    csrf_param: null,
    changeElementOptions: function (e) {
        e.preventDefault();
        jQuery(document).trigger("changeCartElementOptions", this);

        var id = jQuery(this).data('id');

        var options = {};

        if (jQuery(this).is('select')) {
            var els = jQuery('.dvizh-cart-option' + id);
        }
        else {
            var els = jQuery('.dvizh-cart-option' + id + ':checked');
            console.log('radio');
        }

        jQuery(els).each(function () {
            var name = jQuery(this).data('id');

            options[id] = jQuery(this).val();
        });

        var data = {};
        data.CartElement = {};
        data.CartElement.id = id;
        data.CartElement.options = JSON.stringify(options);

        dvizh.cart.sendData(data, jQuery(this).data('href'));

        return false;
    },
    changeBeforeElementOptions: function (e) {
        // e.preventDefault();
        var id = jQuery(this).attr('data-id');
// alert(id);
        var filter_id = jQuery(this).attr('data-filter-id');
        var buyButton = jQuery('.dvizh-cart-buy-button' + id);

        var options = jQuery(buyButton).data('options');
        if (!options) {
            options = {};
        }

        options[filter_id] = jQuery(this).val();

        jQuery(buyButton).data('options', options);
        jQuery(buyButton).attr('data-options', options);

        jQuery(document).trigger("beforeChangeCartElementOptions", id);
        
        // var options = $('.dvizh-cart-buy-button'+modelId).data('options');
        // var csrfToken = yii.getCsrfToken();
        // $('.dvizh-shop-price-' + id).css('opacity', 0.3);
// console.log(id);
// console.log(options);
// console.log(csrfToken);
        // jQuery.ajax({
            // url: dvizh.modificationconstruct.dvizhShopUpdatePriceUrl,
            // type: 'post',
            // dataType: 'text',
            // async: false,
            // data: {
                // options: options, 
                // productId: id,
                // _csrf : csrfToken
            // },
            // success: function (response) {
// alert(data);
// console.log(response);
                // var data= JSON.parse(response);
                // if(data.modification && (data.modification.amount > 0 | data.modification.amount == null)) {
                    // $('.dvizh-shop-price-' + id).html(data.modification.price);
                    // $('.dvizh-cart-buy-button' + id).data('price', data.modification.price);
                // } else {
                    // $('.dvizh-shop-price-' + id).html(data.product_price);
                    // $('.dvizh-cart-buy-button' + id).data('price', data.product_price);

                    // alert('???????????? ?????????????????????? ?????? ?? ??????????????.');
                // }
                // $('.dvizh-shop-price-' + id).css('opacity', 1);
                // return false;
            // },
            // error: function(data){
// console.log(data);
// alert(print_r(data));
                // return false;
            // },
            // complete: function(){
                // return false;
            // }
        // });

        // return false;
    },
    deleteElement: function (elementId, url) {

        dvizh.cart.sendData({elementId: elementId}, url);

        return false;
    },
    changeInputValue: function () {
        var val = parseInt(jQuery(this).siblings('input').val());
        var input = jQuery(this).siblings('input');

        if (jQuery(this).hasClass('dvizh-downArr')) {
            if (val <= 0) {
                return false;
            }
            jQuery(input).val(val - 1);
        }
        else {
            jQuery(input).val(val + 1);
        }

        jQuery(input).change();

        return false;
    },
    changeBeforeElementCount: function () {
        if (jQuery(this).val() <= 0) {
            jQuery(this).val('0');
        }

        var id = jQuery(this).data('id');
        var buyButton = jQuery('.dvizh-cart-buy-button' + id);
        jQuery(buyButton).data('count', jQuery(this).val());
        jQuery(buyButton).attr('data-count', jQuery(this).val());

        return true;
    },
    changeElementCost: function(cartElementId, cartElementCount) {
        var newCost = jQuery('.dvizh-cart-element-price'+cartElementId).html() * cartElementCount;
        jQuery('.dvizh-cart-element-cost'+cartElementId).html(newCost);
    },
    changeElementCount: function (cartElementId, cartElementCount, url) {

        var data = {};
        data.CartElement = {};
        data.CartElement.id = cartElementId;
        data.CartElement.count = cartElementCount;

        dvizh.cart.sendData(data, url);

        return false;
    },
    addElement: function (itemModelName, itemId, itemCount, itemPrice, itemOptions, url, itemComment) {

        var data = {};
        data.CartElement = {};
        data.CartElement.model = itemModelName;
        data.CartElement.item_id = itemId;
        data.CartElement.count = itemCount;
        data.CartElement.price = itemPrice;
        data.CartElement.options = itemOptions;
        data.CartElement.comment = itemComment;

        dvizh.cart.sendData(data, url);

        return false;
    },
    truncate: function (url) {
        dvizh.cart.sendData({}, url);
        return false;
    },
    sendData: function (data, link) {
// console.log(data);
        if (!link) {
            link = '/cart/element/create';
        }
        
        data.lang = $('html').attr('lang');

        jQuery(document).trigger("sendDataToCart", data);

        data.elementsListWidgetParams = dvizh.cart.elementsListWidgetParams;
        data[dvizh.cart.csrf_param] = dvizh.cart.csrf;

        // jQuery('.dvizh-cart-block').css({'opacity': '0.3'});
        // jQuery('.dvizh-cart-count').css({'opacity': '0.3'});
        // jQuery('.dvizh-cart-price').css({'opacity': '0.3'});

        jQuery.ajax({
            url: link,
            data: data,
            type: 'post',
            dataType: 'json',
            // async: false,
            beforeSend: function(){
                loading();
            },
            success: function (json) {
                // jQuery('.dvizh-cart-block').css({'opacity': '1'});
                // jQuery('.dvizh-cart-count').css({'opacity': '1'});
                // jQuery('.dvizh-cart-price').css({'opacity': '1'});

                if (json.result == 'fail') {
                    console.log(json.error);
                }
                else {
                    dvizh.cart.renderCart(json);
                    $(document).trigger('dvizhCartChanged');
                }

            },
            error: function(response){
console.log(response);
                // dvizh.cart.sendData(data, link);
            },
            complete: function(){
                loading(false);
            },
        });

        return false;
    },
    renderCart: function (json) {
        if (!json) {
            var json = {};
            jQuery.ajax({
                url: '/cart/default/info',
                type: 'get',
                data: {
                    lang: $('html').attr('lang')
                },
                dataType: 'json',
                beforeSend: function(){
                    loading();
                },
                success: function(answer) {
                    json = answer;
                },
                error: function(answer){
                    console.log(answer);
                },
                complete: function(){
                    loading(false);
                },
            });
        }
        
        var qty = jQuery('.dvizh-cart-count:first').text();

        jQuery('.dvizh-cart-block').replaceWith(json.elementsHTML);
        jQuery('.dvizh-cart-count').html(json.count);
        jQuery('#mini-cart-total').toggleClass('d-none', json.count === 0);
        jQuery('.dvizh-cart-price').html(json.price);

        if (parseFloat(json.count) > parseFloat(qty) && !location.href.includes('checkout')){
            toastr.success(CART_ADD_SUCCESS);
        }

        if (parseFloat(json.count) === 0 && location.href.includes('checkout')){
            location.href = '/catalog';
        }

        $('.btn-minicart-checkout').toggleClass('d-none', $('.dvizh-cart-count:first').text() === '0');

        jQuery(document).trigger("renderCart", json);

        return true;
    },
};

$(function() {
    dvizh.cart.init();
    // dvizh.cart.renderCart();
    // $('.btn-minicart-checkout').toggleClass('d-none', $('.dvizh-cart-count:first').text() === '0');
});


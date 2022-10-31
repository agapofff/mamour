if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}
Array.prototype.diff = function(a) {
    return this.filter(function(i){return a.indexOf(i) < 0;});
};

dvizh.modificationconstruct = {
    dvizhShopUpdatePriceUrl: null,
    init: function() {
        $(document).on('change', '.product-add-modification-form .filters select', this.generateName);

        $(document).on("beforeChangeCartElementOptions", function(e, modelId) {
            dvizh.modificationconstruct.setModification(modelId);
        });
    },
    setModification: function(modelId) {
        var options = $('.dvizh-cart-buy-button'+modelId).data('options');
        var csrfToken = yii.getCsrfToken();
        // $('.dvizh-shop-price-' + modelId).css('opacity', 0.3);
        $('.dvizh-cart-buy-button').removeAttr('disabled');
        jQuery.ajax({
            url: dvizh.modificationconstruct.dvizhShopUpdatePriceUrl, 
            type: 'post',
            async: false,
            // cache: false,
            dataType: 'json',
            beforeSend: function(){
                NProgress.start();
            },
            data: {
                options: options,
                productId: modelId,
                _csrf : csrfToken
            },
            success: function(data){
                if (data.modification && (data.modification.amount > 0 | data.modification.amount == null) && data.modification.price[0] > 0) {
                    $('.dvizh-shop-price')
                        .html(data.modification.price[1]);
                        
                    $('.dvizh-cart-buy-button')
                        .attr('data-price', data.modification.price[0])
                        .attr('data-comment', data.modification.sku)
                        .removeClass('btn-outline-secondary')
                        .addClass('btn-primary btn-hover-warning')
                        .removeAttr('disabled');
						
					$('.btn-wishlist')
						.attr('data-size', data.modification.name.split(' | ')[0])
                        .addClass('text-hover-warning')
						.removeAttr('disabled');
                        
                    // $('#product-wishlist-container').find('svg').attr('fill', '#D6751C');
						
					$('.select-size-note').hide();
						
					wishlistCheck();
                } else {
                    $('.dvizh-shop-price').html(data.product_price);
					
                    $('.dvizh-cart-buy-button')
                        .attr('data-price', data.product_price)
                        .removeClass('btn-primary btn-hover-warning')
                        .addClass('btn-outline-secondary')
                        .attr('disabled', true);
						
					$('.btn-wishlist')
                        .removeClass('text-hover-warning')
                        .attr('disabled', true);
					
					$('.select-size-note').show();

                    outOfStock();
					

                }
                // $('.dvizh-shop-price-' + modelId).css('opacity', 1);
                // $('.dvizh-change-options').removeClass('d-none');

            },
            error: function(data){
console.log(data);
                // $('.dvizh-option:first .dvizh-option-values-before').trigger('click');
            },
            complete: function(){
                NProgress.done();
                
            },
        });
    },
    generateName: function() {
        var name = '',
            nameArr = [];
        $('.product-add-modification-form .filters select').each(function(i, el) {
            var val = $(this).find('option:selected').text();
            if(val) {
                nameArr.push(val);
            }
        });

        $('#modification-name').val(nameArr.join(' | '));
    }
}

dvizh.modificationconstruct.init();

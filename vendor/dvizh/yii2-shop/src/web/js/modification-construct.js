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
                loading();
            },
            data: {
                options: options,
                productId: modelId,
                _csrf : csrfToken
            },
            success: function(data){
                if (
                    data.modification 
                    && (data.modification.amount > 0 | data.modification.amount == null) 
                    && data.modification.price[0] > 0
                ) {
                    $('.dvizh-shop-price').html(data.modification.price[1]);
                        
                    $('.dvizh-cart-buy-button')
                        .attr('data-price', data.modification.price[0])
                        .attr('data-comment', data.modification.sku)
                        .removeClass('btn-outline-secondary')
                        .addClass('btn-primary btn-hover-warning')
                        .removeAttr('disabled');
                } else {
                    $('.dvizh-shop-price').html(data.product_price);
					
                    $('.dvizh-cart-buy-button')
                        .attr('data-price', data.product_price)
                        .removeClass('btn-primary btn-hover-warning')
                        .addClass('btn-outline-secondary')
                        .attr('disabled', true);
					
                    outOfStock();
                }
            },
            error: function(data){
                console.log(data);
            },
            complete: function(){
                loading(false);
            },
        });
    },
    generateName: function() {
        var name = '',
            nameArr = [];
            
        $('.product-add-modification-form .filters select').each(function(i, el) {
            var val = $(this).find('option:selected').text();
            if (val) {
                nameArr.push(val);
            }
        });

        $('#modification-name').val(nameArr.join(' | '));
    }
}

dvizh.modificationconstruct.init();

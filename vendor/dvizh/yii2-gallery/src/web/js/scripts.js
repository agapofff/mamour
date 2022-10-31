if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.gallery = {
    init: function () {
        $('.dvizh-gallery-item a.delete').on('click', this.deleteProductImage);
        $('.dvizh-gallery-item a.write').on('click', this.callModal);
        $('.dvizh-gallery img').on('click', this.setMainProductImage);
        $('.noctua-gallery-form').on('submit', this.writeProductImage);
    },
    setMainProductImage: function () {
console.log('setMainProductImage');
        dvizh.gallery._sendData($(this).data('action'), $(this).parents('li').data());
        $('.dvizh-gallery > li').removeClass('main');
        $(this).parents('li').addClass('main');
        return false;
    },

    writeProductImage: function (event) {
        event.preventDefault();
        var modalContainer = $('#noctua-gallery-modal');
        var form = $(this).find('form');
        var data = form.serialize();
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (result) {
                var json = $.parseJSON(result);
                if (json.result == 'success') {
                    modalContainer.modal('hide');
                }
                else {
                    alert(json.error);
                }
            }
        });
    },

    callModal: function (event) {
        event.preventDefault();
        var modalContainer = $('#noctua-gallery-modal');
        var url = $(this).data('action');
        modalContainer.modal({show:true});
        data = $(this).parents('.dvizh-gallery-item').data();
        delete data.sortableItem;        
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (data) {
                $('.noctua-gallery-form').html(data);
            }
        });
    },
    deleteProductImage: function () {
        if (confirm($(this).data('confirm'))) {
            dvizh.gallery._sendData($(this).data('action'), $(this).parents('.dvizh-gallery-item').data());
            $(this).parents('.dvizh-gallery-item').hide('slow');
        }
        return false;
    },
    _sendData: function (action, data) {
        return $.post(
            action,
            {image: data.image, id: data.id, model: data.model},
            function (answer) {
                var json = $.parseJSON(answer);
                if (json.result == 'success') {
console.log(answer);
                }
                else {
                    alert(json.error);
                }
            }
        );
    },
    setSort: function(event, ui){
        $('.dvizh-gallery').each(function(){
            var items = [];
            $(this).find('.dvizh-gallery-item').each(function(){
                items.push(parseFloat($(this).attr('data-image')));
            });
            $.ajax({
                data: {
                    items: items
                },
                type: 'POST',
                url: '/admin/gallery/default/sort',
                success: function(data){
                    console.log(data);
                }
            });
        });
    }
};

dvizh.gallery.init();
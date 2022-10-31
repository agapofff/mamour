if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.relations = {
    init: function () {
        $('.dvizh-relations-variant').on('click', this.addRelation);
        $(document).on('click', '.dvizh-relation-delete', this.deleteRelation)
    },
    deleteRelation: function() {
        $(this).parent('.dvizh-delete-column').parent('.row').remove();
        return false;
    },
    addRelation: function() {
        $(this).addClass('selected');
        
        setTimeout(function() { $('.selected').removeClass('selected') }, 200);

        dvizh.relations.renderRow($(this).data('model'), $(this).data('id'), $(this).data('name'), $(this).data('image'));

        return false;
    },
    renderRow: function(model, id, name, img) {
        var idColumn = '<div class="col-xs-2"><img src="'+img+'"><input type="hidden" name="relations_models[]" value="'+model+'" /><input type="hidden" name="relations_ids[]" value="'+id+'" /></div>';
        var nameColumn = '<div class="col-xs-9">'+name+'</div>';
        var deleteColumn = '<div class="col-xs-1 dvizh-delete-column"><a href="#" class="dvizh-relation-delete glyphicon glyphicon-trash"></a></div>';

        $('.dvizh-relations', window.parent.document).append('<div class="row dvizh-relation-row">'+idColumn+nameColumn+deleteColumn+'</div>');
        
        return true;
    }
}

dvizh.relations.init();
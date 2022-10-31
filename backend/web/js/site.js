function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// анимация загрузки
function showLoader(){
    // $('#loader').show();
    // setTimeout(function(){
        // hideLoader();
    // }, 3000);
}
function hideLoader(){
    $('#loader').hide();
}
$(document).ready(function(){
    hideLoader();
});
$(document).on('click', '#loader', function(){
    hideLoader();
});
window.addEventListener('beforeunload', function(e){
    showLoader();
}, false);
$(document).on('pjax:send', function(){
	showLoader();
});
$(document).on('pjax:end', function(){
	hideLoader();
	tableSortInit();
    editableMultilang();
});



// вставка видео

function getEmbedVideo(id){
    return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' + id + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
}

function setEmbedVideo(id){
    if ($('#' + id).val() && $('#' + id).val().includes('youtu')){
        var videoId = $('#' + id).val().split('?')[0].split('/').pop(),
            embedVideo = getEmbedVideo(videoId);
        $('#' + id + '-embed').html(embedVideo);    
    } else {
        $('#' + id + '-embed').empty();
    }
}

$(document).ready(function(){
    $('.video-input').each(function(){
        setEmbedVideo($(this).attr('id'));
    })
});

$(document).on('change', '.video-input', function(){
    var id = $(this).attr('id');
    setEmbedVideo(id);
});

$(document).on('click', '.video-remove', function(){
    $('#product-videoFile').val('');
    $('#product-video-embed').remove();
    $('#product-video-form').show();
});



// вставка изображений при изменении инпута

function setEmbedImage(input){
    if (input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
            $('#' + $(input).attr('id') + '-embed').html('<img src="' + e.target.result + '" class="img-responsive">');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#' + $(input).attr('id') + '-embed').empty();
    }
}

$(document).on('change', '.image-input', function(){
    setEmbedImage(this);
});



// модальные окна по ссылке
/*
$(document).delegate('*[data-toggle="lightbox"], .lightbox', 'click', function(a){
    a.preventDefault();
    $(this).ekkoLightbox();
});
*/



// сохранить и закрыть
$(document).on('click', 'button.saveAndExit', function () {
    $('input[name="saveAndExit"]').val(1).parents('form').submit();
});



// pjax-ссылки

$(document).on('click', '.pjax', function (e) {
    e.preventDefault();
    var url = $(this).attr('href'),
        pjaxId = $(this).parents('[data-pjax-container]').attr('id');
    $.get(url, function(){
        $.pjax.reload({
            container: '#' + pjaxId,
            async: false
        });
    });
});


// JSON-поля в форме товара
multilangFields =  function () {
    $('.is_sub_json').each(function () {
        var $field = $(this),
            id = $(this).attr('id'),
            fields = {};
        $('.sub_json_field[data-field="' + id + '"]').each(function () {
            var key = $(this).attr('data-key'),
                text = $(this).val();
            fields[key] = text;
        });
        $field.val(JSON.stringify(fields));
    });
    
    $('.is_json').each(function () {
        var $field = $(this),
            id = $(this).attr('id'),
            fields = {},
            required = $(this).parent().hasClass('required'),
            isCorrect = true;
        $('.json_field[data-field="' + id + '"]').each(function () {
            var lang = $(this).attr('data-lang'),
                text = $(this).val();
            // if (!text && required){
                // isCorrect = false;
            // }
            fields[lang] = text;
        });
        $field.val(isCorrect ? JSON.stringify(fields) : '');
    });
}

$(document).on('input', '.json_field', function () {
    multilangFields();
});

$('form').on('beforeValidate', function (event) {
    event.preventDefault();
    multilangFields();
    // return false;
});

editableJsonText = function (el) {
    if (isJson($(el).text())) {
        $(el).text(JSON.parse($(el).text()).ru);
    }
}

editableMultilang = function () {
    $('.editable').bind('DOMSubtreeModified', function () {
        editableJsonText(this);
    });

    $('.editable').each(function () {
        editableJsonText(this);
    });
    
    $('.editable').on('shown', function (event, editable) {
        if (isJson(editable.value)) {
            var $formGroup = $(this).parent().find('.form-group'),
                id = $formGroup.find('input').attr('id'),
                vals = JSON.parse(editable.value),
                pills = tabs = '';
            
            $formGroup.find('input').addClass('is_json').hide();
            
            $.each(vals, function (key, val) {
                pills += '<li' + (key == 'ru' ? ' class="active"' : '') + '><a href="#' + id + '_' + key + '_tab" aria-controls="' + id + '_' + key + '_tab" role="tab" data-toggle="tab">' + key.toUpperCase() + '</a></li>';
                
                tabs += '<div id="' + id + '_' + key + '_tab" class="tab-pane' + (key === 'ru' ? ' active' : '') + '" role="tabpanel"><input type="text" id="' + id + '_' + key + '" class="form-control json_field" name="' + id + '_' + key + '" value="' + val + '" data-field="' + id + '" data-lang="' + key + '" oninput="multilangFields"></div>';
            });
            
            $formGroup.prepend('<ul class="nav nav-pills">' + pills + '</ul><div class="tab-content">' + tabs + '</div>');
        }
    // console.log(event);
    // console.log(editable);
    });
}
$(window).on('load',function () {
    editableMultilang();
});



// модификации

$(document).on('click', '#modification-add-btn', function () {
    var lang = $('#tab-mod > li.active > a').attr('data-lang'),
        store = $($('#tab-mod > li.active a').attr('href') + ' > .nav > li.active > a').attr('data-store'),
        store_name = $($('#tab-mod > li.active a').attr('href') + ' > .nav > li.active > a').text();
    $('#modification-add-window').contents().find('#modification-lang').val(lang);
    $('#modification-add-window').contents().find('#modification-store_type').val(store);
    $('#modification-add-window').contents().find('#filterValue3 option:contains("' + lang + '")').prop('selected', true);
    $('#modification-add-window').contents().find('#filterValue3').trigger('change');
    $('#modification-add-window').contents().find('#filterValue4 option:contains("' + store_name + '")').prop('selected', true);
    $('#modification-add-window').contents().find('#filterValue4').trigger('change');
});

$(document).on('pjax:beforeSend', '#product-modifications', function (event) {
    $('#product-modifications').attr('data-lang', $('#product-modifications ul li.active a').attr('href'));
    $('#product-modifications').attr('data-type', $('#product-modifications .tab-content .tab-pane.active ul li.active a').attr('href'));
});

$(document).on('pjax:end', '#product-modifications', function (event) {
    // hideLoader();
    $('a[href="' + $('#product-modifications').attr('data-lang') + '"]').click();
    $('a[href="' + $('#product-modifications').attr('data-type') + '"]').click();
});

$(document).on('hidden.bs.modal', '#modification-add-modal', function(event){
    $.pjax.reload({
        container: '#product-modifications',
        async: false
    });
});

function modificationsRefresh() {
    $('.modal').modal('hide');
    $.pjax.reload('#product-modifications');
}


$(document).ready(function () {
	tableSortInit();
});

tableSortInit = function () {   
	$('.sortable').each(function () {
        var $el = $(this),
            url = $(this).data('sort'),
            isTable = $(this).is('table') ? true : false;
        
        $(this).sortable({
            items: isTable ? 'tr' : 'li',
            handle: '.sort-handler',
            cursor: 'move',
            start: function (e, ui) {
                ui.placeholder.html(ui.item.html());
            },
            // containment: $el,
            helper: function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function () {
                var elements = [];
                    rows = isTable ? ($('.sortable').hasClass('desc') ? $('.sortable tbody tr').get().reverse() : $('.sortable tbody tr').get()) : $('.sortable li').get();
                    
                $(rows).each(function (key, element) {
                    elements.push($(this).data('key'));
                });

                $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        elements: elements.join(',')
                    },
                    success: function (response) {
                        $.pjax.reload({
                            container: '#' + $('[data-pjax-container]').attr('id'),
                        });                        
                    },
                    error: function (response) {
                        console.log(response);
                        alert('Ошибка обновления данных. Подробности в консоли');
                    }
                });
            }
        });
    });
}

if ($('button').is('.saveAndExit')) {
    $('button.saveAndExit').parent('div').addClass('save-panel');
    $('section.content').append('<br><br><br>');
}
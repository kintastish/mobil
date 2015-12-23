<h4>Меню</h4>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\widgets\bootstrap\Collapse;
use yii\bootstrap\Modal;
?>

<?php 
$buttons = 
    '<a href="#" class="btn btn-sm" data-op="up" title="Передвинуть вверх">'
        .'<i class="glyphicon glyphicon-arrow-up text-primary"></i>'
    .'</a>'
    .'<a href="#" class="btn btn-sm" data-op="dn" title="Передвинуть вниз">'
        .'<i class="glyphicon glyphicon-arrow-down text-primary"></i>'
    .'</a>'
    .'<a href="#" class="btn btn-sm" data-op="del" title="Удалить">'
        .'<i class="glyphicon glyphicon-remove text-warning"></i>'
    .'</a>';
$menu_item_tpl =
    '<div class="row">'
        .'<div class="col-md-8">{title} '
            .'<a href="#w0" class="add-link-btn" title="Добавить пункт в подменю" data-toggle="modal" data-menulevel="2">'
                .'<i class="glyphicon glyphicon-plus-sign text-success"></i>'
            .'</a>'
        .'</div>'
        .'<div class="col-md-4">'.$buttons.'</div>'
        .'<input type="hidden" name="title[]" value="{title}">'
        .'<input type="hidden" name="url[]" value="{url}">'
    .'</div>';
$submenu_item_tpl = 
    '<div class="col-md-11 col-offset-1">'
        .'{title}'
    .'</div>'
?>
<div class="dynblock-menu">
    <?php echo Html::beginForm(); ?>
    
    <?php $this->beginBlock('panel2'); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Элементы меню</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a href="#w0" class="add-link-btn btn btn-primary" title="Добавить пункт меню" data-toggle="modal" data-menulevel="1">
                                <i class="glyphicon glyphicon-plus-sign"></i> Добавить пункт в меню
                            </a>
                        </div>
                        <div class="panel-body">
                            <div id="menu-items">
                            <?php
                            foreach ($model->links as $ind => $v) {
                                echo str_replace('{url}', $v['url'], str_replace('{title}', $v['title'], $menu_item_tpl));
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php 
        echo $this->blocks['panel2'];
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?= Html::endForm(); ?>
</div>

<?php 
Modal::begin([
    'header' => '<h2>Новый пункт меню</h2>',
    'footer' => '<button type="button" class="btn btn-primary" id="modal-save">Добавить</button>'.
                '<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>',
    'toggleButton' => false,
]);
?>
    <div class="input-group">
        <span class="input-group-addon">
            <a title="Заголовок пункта меню"><i class="glyphicon glyphicon-tag"></i></a>
        </span>
        <input type="text" id="link-title" class="form-control">
    </div>
    <p></p>
    <div class="input-group">
        <span class="input-group-addon">
            <a title="URL"><i class="glyphicon glyphicon-link"></i></a>
        </span>
        <input type="text" id="link-url" class="form-control">
    </div>
    <p id="route"> </p>
    <div class="form-group">
        <select id="res-list" size="5" class="form-control"></select>
    </div>
<?php Modal::end(); ?>

<?php 
$linkExplorer = Url::to(['explore/expand']);
$jsLinks = <<<jsLinks
var menuInsertionPoint;
$('#w0').on('show.bs.modal', function (e) {
    var menulevel = $(event.relatedTarget).data('whatever');
    if (menulevel == "1") {
        menuInsertionPoint = $('#menu-items');
    }
    refreshForm();
});
$('#res-list').bind('dblclick', function(){
    var op = $('#res-list option:selected');
    if (op.val() != undefined) {
        $('#link-title').val( op.text() );
        if (op.data('node')) {
            appendRoute(op.val(), op.text(), op.data('url'));
            $.get('$linkExplorer', {'node': op.val()}, refreshSelect);
        }
    }
});

$('#res-list').on('change', function(ev){
    var op = $('#res-list option:selected');
    $('#link-url').val(op.data('url'));
    $('#link-title').val(op.text());
});

$('body').on('click', '#route>a', function(ev){
    var node = $(ev.target).data('id');
    loaddata(node);
    var i = $(ev.target).index();
    $('#route>a').slice( i+1 ).hide(400, function(){
        $(this).remove();
    });
    $('#link-url').val( $(ev.target).data('url') );
    $('#link-title').val( $(ev.target).text() );
});

$('#modal-save').on('click', function(ev){
    $('#w0').modal('hide');
    var tpl = '$menu_item_tpl';
    var title = $('#link-title').val();
    var url = $('#link-url').val();
    var row = $(tpl.replace(/{title}/g, title).replace('{url}', url));
    $('#menu-items').append(row);
})

$('body').on('click', 'a[data-op]', function(ev){
    var item = $(this).parent().parent();
    var item_count = $('#menu-items>div.row').length;
    var op = $(this).attr('data-op');
    if (item.length == 1) {
        switch( op ) {
        case 'up':
            if ($(item).index() > 0) {
                var i = $(item).prev();
                $(i).before($(item));
            }
        break;
        case 'dn':
            if ( $(item).index() < item_count-1 ) {
                var i = $(item).next();
                $(i).after($(item));
            }
        break;
        case 'del':
            $(item).remove();
        break;
        }
    }
});

function appendRoute(node_id, title, url)
{
    var link = $('<a href="#">' + title + '</a>');
    link.data('id', node_id);
    link.data('url', url);
    $('#route').append(link);
};

function loaddata(id) {
    if (id == undefined) id = 0;
    $.get('$linkExplorer', {'node':id}, refreshSelect);
};

function refreshSelect(data) {
    var select = $('#res-list');
    select.empty();
    if (data.length == 0) {
        select.append('<option value="default" disabled="disabled">Раздел пуст</option>');
    }
    else {
        $.each(data, function (key, val) {
            var option = $('<option value="' + val.id + '">' + val.title + '</option>');
            if (val.node) {
                option.addClass('node');
            };

            option.data('node', val.node);
            option.data('url', val.url);
            select.append(option);
        });
    }
};

function refreshForm() {
    $('#route').text(' ');
    $('#link-title').val('');
    $('#link-url').val('');
    appendRoute(0, 'Главная', '/');
    loaddata(0);
}

jsLinks;
$this->registerJs($jsLinks, \yii\web\View::POS_END);
?>
<?php 
$css = <<<CSS
#route {
    margin: 3px;
}
#route a {
    margin-right: 3px;
}

#route a:after {
    content: ">";
    margin-left: 2px;
}

#res-list option:before{
    content: " -";
    margin-right: 4px;
}

#res-list option.node:before{
    content: "[+]";
    margin-right: 4px;
}
CSS;
$this->registerCss($css);
?>
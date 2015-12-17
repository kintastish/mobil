<h4>Меню</h4>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\widgets\bootstrap\Collapse;
?>

<div class="dynblock-menu">
    <?php echo Html::beginForm(); ?>
    
    <?php $this->beginBlock('panel1'); ?>
    <div class="row form-group">
        <div class="col-md-12"><?= Html::activeLabel( $model, 'beginTemplate') ?></div>
        <div class="col-md-8">
            <?= Html::activeTextarea($model, 'beginTemplate', ['class'=>'form-control', 'rows'=>2]) ?>
        </div>
        <div class="col-md-4">
            HTML-код, который будет выведен <strong>до</strong> элементов меню.
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-12"><?= Html::activeLabel( $model, 'itemTemplate') ?></div>
        <div class="col-md-8">
            <?= Html::activeTextarea($model, 'itemTemplate', ['class'=>'form-control', 'rows'=>2]) ?>
        </div>
        <div class="col-md-4">
            <div>HTML-шаблон элемента меню. Переменные для шаблона: </div>
            <div><b>{Текст}</b> - текст ссылки</div>
            <div><b>{URL}</b> - веб-адрес страницы</div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-12"><?= Html::activeLabel( $model, 'endTemplate') ?></div>
        <div class="col-md-8">
            <?= Html::activeTextarea($model, 'endTemplate', ['class'=>'form-control', 'rows'=>2]) ?>
        </div>
        <div class="col-md-4">
        	HTML-код, который будет выведен <strong>после</strong> элементов меню.
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('panel2'); ?>
    <div class="row">
		<div class="col-md-6">
			<div class="input-group">
				<span class="input-group-addon"><a title="Заголовок пункта меню"><i class="glyphicon glyphicon-tag"></i></a></span>
				<input type="text" id="link-title" class="form-control">
                <a href="#" id="add-link-btn" class="input-group-addon" title="Добавить ссылку в меню"><i class="glyphicon glyphicon-arrow-right"></i></a>
			</div>
			<p></p>
			<div class="input-group">
				<span class="input-group-addon"><a title="URL"><i class="glyphicon glyphicon-link"></i></a></span><input type="text" id="link-url" class="form-control">
			</div>
			<p id="route"> </p>
			<div class="form-group">
				<select id="res-list" size="5" class="form-control"></select>
			</div>
		</div>
		<div class="col-md-6">
			<label>Элементы меню</label>
			<div id="menu-items">
            <?php 
            $buttons = '<a href="#" class="btn btn-sm" data-op="up" title="Передвинуть вверх"><i class="glyphicon glyphicon-arrow-up text-primary"></i></a>'
                    .'<a href="#" class="btn btn-sm" data-op="dn" title="Передвинуть вниз"><i class="glyphicon glyphicon-arrow-down text-primary"></i></a>'
                    .'<a href="#" class="btn btn-sm" data-op="del" title="Удалить"><i class="glyphicon glyphicon-remove text-warning"></i></a>';
            $row_tpl = '<div class="row">'
                    .'<div class="col-md-8">{title}</div><div class="col-md-4">'.$buttons.'</div>'
                    .'<input type="hidden" name="title[]" value="{title}"><input type="hidden" name="url[]" value="{url}">'
                    .'</div>';
            foreach ($model->links as $ind => $v) {
                echo str_replace('{url}', $v['url'], str_replace('{title}', $v['title'], $row_tpl));
            }
            ?>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php 
    echo Collapse::widget([
        'items' => [
            [
                'label' => 'Настройка шаблона',
                'content' => $this->blocks['panel1'],
            ],
            [
                'label' => 'Элементы меню',
                'content' => $this->blocks['panel2'],
                'expanded' => true
            ],
        ]
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?= Html::endForm(); ?>
 
</div>
<?php 
$linkExplorer = Url::to(['explore/expand']);
$jsLinks = <<<jsLinks
appendRoute(0, 'Главная', '/');
loaddata(0);
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

$('#add-link-btn').on('click', function(ev){
    var tpl = '$row_tpl';
    var title = $('#link-title').val();
    var url = $('#link-url').val();
    var row = $(tpl.replace(/{title}/g, title).replace('{url}', url));
    $('#menu-items').append(row);
});

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
    //select.trigger('change');
};

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
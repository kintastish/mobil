<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\file\FileInput;
?>

<div class="page-image-upload">
<?php
$editAction = Url::to(['image/edit']).'&id={id}';
$jsUploaded = <<<fileuploaded
function(event, data, previewId, index) {
    $("div[class$=file-preview-success]").remove();
    if( $("div.file-preview-frame").length == 0 ) {
        $(this).fileinput("clear");
    }
    var tpl = '<div class = "col-md-4"><div class="thumbnail"><p>'
            + '<a href="$editAction" class="imgaction blue" title="Редактировать">'
            + '<i class="glyphicon glyphicon-pencil"></i></a>'
            + '<a href="#" class="imgaction red pull-right" title="Удалить" data-id="{id}">'
            + '<i class="glyphicon glyphicon-trash"></i></a></p>'
            + '<img src="{thumb}">'
            + '<p><a href="#" class="imgaction grey" title="Сделать главным" data-id="{id}">'
            + '<i class="glyphicon glyphicon-ok"></i></a></p></div></div>';
    for(var i in data.response) {
        var el = tpl.replace('{id}', data.response[i].id).
                    replace('{thumb}', data.response[i].thumb);
        $(el).appendTo('#page-images');
    }
}
fileuploaded;

echo FileInput::widget([
	'name' => 'files',
	'options' => [
		'accept' => 'image/*',
		'multiple' => true,
	],
    'pluginOptions' => [
        'uploadUrl' => Url::to(['image/ajax-upload', 'table'=>$model::$tableId, 'id' => $model->id]),
        'maxFileCount' => 5,
        'browseClass' => 'btn btn-success',
        'uploadClass' => 'btn btn-info',
        'removeClass' => 'btn btn-danger',
        'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> ',
        'dropZoneEnabled' => false,
    ],
    'pluginEvents' => [
    	'fileuploaded' => $jsUploaded
    ]
]) ?>
</div>
<hr>
<div id="page-images" class="row">
<?php 
if ($files != null) {
    foreach ($files as $f) {
        $f->attachBehavior('imageBehavior', ['class'=> \app\components\imageBehavior::className()]);?>
        <div class = "col-md-4">
            <div class="thumbnail">
                <p>
                    <a href="<?= Url::to(['image/edit', 'id'=>$f->id]) ?>" class="imgaction blue" title="Редактировать"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a href="#" class="imgaction red pull-right" title="Удалить" data-id="<?= $f->id ?>"><i class="glyphicon glyphicon-trash"></i></a>
                </p>
                <?= Html::img($f->thumbnailUrl) ?>
                <p>
                    <a href="#" class="imgaction grey" title="Сделать главным" data-id="<?= $f->id ?>"><i class="glyphicon glyphicon-ok"></i></a>
                </p>
            </div>
        </div><?php
    }
}
?>
</div>
<p class="alert alert-warning"><i class="glyphicon glyphicon-exclamation-sign"></i> При удалении изображения не забудьте удалить ссылки на него из текста</p>
<?php 
$css = <<<CSS
.imgaction {
  display: inline-block;
  padding: 1px 5px;
  margin-bottom: 0;
  font-size: 11px;
  font-weight: normal;
  line-height: 1.5;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -ms-touch-action: manipulation;
      touch-action: manipulation;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 3px;  
}
.blue {
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
}
.blue:hover {
  color: #fff;
  background-color: #286090;
  border-color: #204d74;
}
.red {
  color: #fff;
  background-color: #d9534f;
  border-color: #d43f3a;
}
.red:hover {
  color: #fff;
  background-color: #c9302c;
  border-color: #ac2925;
}
.green {
  color: #fff;
  background-color: #5cb85c;
  border-color: #4cae4c;
}
.green:hover {
  color: #fff;
  background-color: #449d44;
  border-color: #398439;
}
.grey {
  color: #333;
  background-color: #fff;
  border-color: #ccc;
}
.grey:hover {
  color: #333;
  background-color: #e6e6e6;
  border-color: #adadad;
}
CSS;
$this->registerCss($css);
$deleteAction = Url::to(['image/ajax-delete']);
$makePrimaryAction = Url::to(['image/mark']);
$jsImgActions = <<<img_actions
$('body').on('click', 'a[class*=red]', function(){
    $.get( "$deleteAction", {"id": $(this).attr('data-id')}, function(data) {
        var d = eval(data);
        var img = $("a[class*=red][data-id=" + d.id + "]").parent().parent().parent();
        $(img).fadeOut(300, function(){
            $(this).remove();
        });
        if( $('#page-images').children().length == 1 ) {
            $('.alert-warning').hide();
        }
    });
});
$('body').on('click', 'a[class*=grey]', function(){
    $.get( "$makePrimaryAction", {"id": $(this).attr('data-id'), "mark":1}, function(data) {
        var d = eval(data);
        $("a[class*=green]").removeClass('green').addClass('grey');
        $("a[class*=grey][data-id=" + d.id + "]").removeClass('grey').addClass('green');
    });
});
$('body').on('click', 'a[class*=green]', function(){
    $.get( "$makePrimaryAction", {"id": $(this).attr('data-id'), "mark":0}, function(data) {
        var d = eval(data);
        $("a[class*=green][data-id=" + d.id + "]").removeClass('green').addClass('grey');
    });
});

img_actions;
$this->registerJs($jsImgActions, \yii\web\View::POS_END);
?>

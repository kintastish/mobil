<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<div>
    <div id="param-form">
		<?= Html::hiddenInput('table_id', $target::$tableId) ?>
		<?= Html::hiddenInput('item_id', $target->id) ?>
		<div class="row form-group">
			<div class="col-md-4">
			<?= Html::dropDownList(
				'param_type', null,
				ArrayHelper::map(\app\models\ParamTypes::getAllParams(), 'id', 'name'),
				['prompt' => 'Выберите параметр',
				'class' => 'form-control']
			) ?>
			</div>
			<div class="col-md-7">
	    	<?= Html::textInput(
	    		'param_value',
	    		null,
	    		['class'=>'form-control',
	    		'placeholder'=>'Введите значение']) ?>
	    	</div>
	    	<div class="col-md-1">
	    	<a href="#" id="add-param-btn" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></a>
	    	</div>
    	</div>
    </div>
</div>
<div id="resource-params">
<div class="row"><div class="col-md-12">&nbsp;</div></div>
<?php 
if ($target->params != null) {
	foreach ($target->params as $p) {
		$a = Html::a('<i class="glyphicon glyphicon-remove text-danger"></i>', '#', ['data-id'=>$p->id, 'class'=>'remove_btn']);
		echo '<div class="row">';
		echo Html::tag('div', '<strong>'.$p->type->comment.'</strong>', ['class'=>'col-md-2 col-md-offset-2']);
		echo Html::tag('div', $p->value, ['class'=>'col-md-7']);
		echo Html::tag('div', $a, ['class'=>'col-md-1']);
		echo '</div>';
	}
}
?>
</div>
<?php
$addUrl = Url::to(['param/add']);
$removeUrl = Url::to(['param/remove']);
$jsAddParam = <<<jsAddParam
var param_select = $('select[name=param_type]');
var value_input = $('input[name=param_value]');
$(param_select).change(function(){
	$(value_input).val('');
});
$('#add-param-btn').click(function(){
	var val = $(value_input).val();
	var param = $(param_select).val();
	if (val != "" && param != "") {
		var tbl = $('input[name=table_id]').val();
		var it = $('input[name=item_id]').val();
		$.post("$addUrl", "param="+param+"&val="+val+"&table="+tbl+"&item="+it, function(data){
			var d = eval(data);
			if (d.length != 0) {
				var row = '<div class="row"><div class="col-md-2 col-md-offset-2"><strong>' + d.param_name + '</strong></div>'
						+     '<div class="col-md-7">' + d.value + '</div>'
						+     '<div class="col-md-1"><a href="#" class="remove_btn" data-id="' + d.id + '"><i class="glyphicon glyphicon-remove text-danger"></i></a></div>'
						+ '</div>';
				$(row).appendTo('#resource-params');
				$(value_input).val('');
				$(param_select).val('');
			}
		});
	}
});
$('body').on('click', 'a.remove_btn', function(){
	var btn = $(this);
	$.get( "$removeUrl", "id="+$(this).attr('data-id'), function(data) {
		var d = eval(data);
		var row = $("a[data-id=" + d.id + "]").parent().parent();
		$(row).fadeOut(300, function(){
			$(this).remove();
		});
	});
});
jsAddParam;
$this->registerJs($jsAddParam, \yii\web\View::POS_END);
?>

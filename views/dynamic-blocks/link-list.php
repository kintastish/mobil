<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\widgets\bootstrap\Collapse;
?>

<div class="dynblock-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php 
    $panel1 = $form->field($model, 'blockBeginTemplate')->textarea(['rows'=>2]);
    $panel1 .= '<div class="row">';
    $panel1 .= Html::tag('div', $form->field($model, 'itemTemplate')->textarea(['rows'=>6]), ['class'=>'col-md-8']);
    $tvars = $model::templateVars();
    $panel1 .= '<div class="col-md-4">';
    $panel1 .= '<label>Вставить в шаблон</label>';
    $panel1 .= '<div id="template-vars">';

    foreach ($tvars as $var => $info) {
        $panel1 .= Html::tag('div', Html::a($info[1], '#', ['title' => '{'.$info[0].'}']));
    }
    $panel1 .= '</div></div></div>';
    $panel1 .= $form->field($model, 'blockEndTemplate')->textarea(['rows'=>2]);

    $panel2 = '<div class="row">'."\n".
                '<div class="col-md-5">'."\n".
                '<p class="lead">Выберите разделы</p>'."\n";
    $panel2 .= '<ul type="none" data-parent="0"><li>'."\n";
    $cats = $model->getCategoryList();
    $level = 0;
    $fc = $model->filterCategories;
    foreach ($cats as $c) {
        if ($level > $c['level']) {
            $panel2 .= '</ul>'."\n";
            $level = $c['level'];
        }
        if ($level < $c['level']) {
            $level = $c['level'];
            $panel2 .= '<ul type="none" data-parent="'.$c['parent'].'">'."\n";
        }
        if ($level == $c['level']) {
            $panel2 .= '</li><li>';
        }
        if ($c['child_count']) {
            $panel2 .= Html::label($c['title'], null, ['data-id'=>$c['id']]);
        }
        else {
            $panel2 .= Html::checkbox('_fc'.$c['id'], isset($fc[$c['id']]), ['label' => $c['title']]);
        }
    }
    while ($level-- != 0) {
        $panel2 .= '</li></ul>';
    }
    $panel2 .= '</ul>';
    $panel2 .= '</div></div>';
    $panel2 .= $form->field($model, 'countMax')->textInput();
    ?>

	<?php 
	echo Collapse::widget([
	    'items' => [
	        [
	            'label' => 'Настройка шаблона',
	            'content' => $panel1,
            ],
            [
                'label' => 'Настройка выборки',
                'content' => $panel2,
	            'expanded' => true
	        ],
	    ]
	]);
	?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 
</div>
<?php 
$js = <<<JS
function color(e)
{
    if ( $(this).attr('data-checked') == "false" ) {
        $(this).css('border-bottom', "2px dotted #d3f0f0");
    }
    else {
        $(this).css('border-bottom', "2px dotted transparent");
    }
}
function decolor(e)
{
    if ( $(this).attr('data-checked') == "false" ) {
        $(this).css('border-bottom', "2px dotted transparent");
    }
    else {
        $(this).css('border-bottom', "2px dotted #d3f0f0");
    }
}
var labels = $("label[data-id]");
$(labels).attr('data-checked', false);
$(labels).css('border-bottom', "2px dotted transparent");
$(labels).hover(color, decolor);
$(labels).click( function(e){
    var parent_id = $(this).attr('data-id');
    var check = $(this).attr('data-checked')=="false" ? true : false;
    $("ul[data-parent="+parent_id+"]").find('input:checkbox').prop('checked', check);
    $(this).attr('data-checked', check);
});
$("#template-vars>div>a").click(function (e){
    var el = $("textarea[id$='itemtemplate']")[0];
    var val = el.value;
    var endIndex, range;
    if (typeof el.selectionStart == "number"
            && typeof el.selectionEnd == "number") {
        endIndex = el.selectionEnd;
        el.value = val.slice(0, endIndex) + $(this).attr('title') + val.slice(endIndex);
        el.selectionStart = el.selectionEnd = endIndex + text.length+(offset?offset:0);
    }
});

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>
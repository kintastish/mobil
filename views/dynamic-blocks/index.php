<?php 

use yii\helpers\Html;

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<div class="panel-title">
			<h4>Динамические блоки</div>
		</h4>
	</div>
	<div class="panel-body">
	<div class="row">
	<?php foreach($blocks as $b) {
		echo Html::tag( 'div', 
			Html::tag('div', 
				Html::tag('div',
					Html::tag('h4', Html::a($b['id'], ['config', 'id'=>$b['id']]))
					.Html::tag('p', $b['comment']),
				['class'=>'caption']),
			['class'=>'thumbnail']),
		['class'=>'col-md-4']);
	}
	?>
	</div>
	</div>
</div>
<div class="panel panel-success">
	<div class="panel-heading">
		<div class="panel-title">
			<h4>Виды блоков</div>
		</h4>
	</div>
	<div class="panel-body">
		<?php
		$counter = 0;
		$content = '';
		foreach ($widgets as $id => $info) { 
			$content .= 
				'<div class="col-md-4">'.
					'<div class="thumbnail">'.
						'<div class="caption">'.
							'<h4>'.$info['label'].'</h4>'.
							'<p>'.$info['description'].'</p>'.
							'<p>'.Html::a('Создать', ['create', 'id' =>$id], ['class'=>'btn btn-primary']).'</p>'.
						'</div>'.
					'</div>'.
				'</div>';
			if (++$counter == 3) {
				echo '<div class="row">'.$content.'</div>';
				$counter = 0;
				$content = '';
			}
		}
		if ($counter) {
			echo '<div class="row">'.$content.'</div>';
		}
		?>
	</div>
</div>

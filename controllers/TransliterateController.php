<?php

namespace app\controllers;

use app\components\TransliterateAction;

class TransliterateController extends \yii\web\Controller
{
	public function actions()
	{
		return [
			'do' => [
				'class' => TransliterateAction::className()
			]
		];
	}
}

<?php 

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\web\Response;
use app\models\DynamicBlockModel;

/**
* 
*/
class FeedbackController extends \yii\web\Controller
{
	
	public function actionSend()
	{
		if (!Yii::$app->request->isAjax) {
			return '';
		}
        Yii::$app->response->format = Response::FORMAT_JSON;		
		$post = Yii::$app->request->post();
		$model = DynamicBlockModel::loadBlockConfigModel($post['formid']);
		if ($model !== null) {
			$msgModel = $this->buildModel($model, $post);
			if ($msgModel->validate()) {
	            Yii::$app->mailer->compose()
	                ->setTo($model->email)
	                ->setFrom('test@send.ru')
	                ->setSubject($model->subject)
	                ->setTextBody($this->getMessageBody($model, $post))
	                ->send();
	            return ['status' => 'ok'];
			}
			return ['errors' => $msgModel->errors];
		}
		return ['errors' => ['Ошибка при отправке формы']];
	}

	/* проверку на длину не делаем - просто режем */
	private function buildModel($fb, $data)
	{
		$model = new DynamicModel;
		for ($i=0; $i < count($fb->fieldName); $i++) { 
			$name = 'f'.$i;
			$model->defineAttribute($name, $data[$name]);
			if ($fb->fieldRequired[$i]) {
				$model->addRule($name, 'required', ['message'=>'Необходимо заполнить поле "'.$fb->fieldName[$i].'"']);
			}
			else {
				$model->addRule($name, 'safe');
			}
		}
		if ($fb->useCaptcha) {
			$model->defineAttribute('captcha', $data['captcha']);
			$model->addRule('captcha', 'captcha');
		}
		return $model;
	}

	private function getMessageBody($fb, $data)
	{
		mb_internal_encoding('UTF-8');
		$s = 'Поступило сообщение ('.$fb->comment.')'."\n"."\n";
		for ($i=0; $i < count($fb->fieldName); $i++) { 
			$s.= "\t".$fb->fieldName[$i].': ';
			switch ($fb->fieldType[$i]) {
				case 'input':
					$s.= mb_substr($data['f'.$i], 0, 50);
					break;
				case 'textarea':
					$s.= mb_substr($data['f'.$i], 0, 500);
					break;
			}
			$s.= "\n";
		}

		return $s."\n\n\n\nКонец сообщения";
	}
}


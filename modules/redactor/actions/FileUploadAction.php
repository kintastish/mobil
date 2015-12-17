<?php

namespace app\modules\redactor\actions;

use Yii;
use yii\base\Action;
use app\modules\redactor\models\FileUploadModel;

/**
 * @author Nghia Nguyen <yiidevelop@hotmail.com>
 * @since 2.0
 */
class FileUploadAction extends Action
{
    function run()
    {
        if (isset($_FILES)) {
            $model = new FileUploadModel();
            if ($model->upload()) {
                return $model->getResponse();
            } else {
                return ['error' => 'Unable to save file'];
            }
        }
    }

}

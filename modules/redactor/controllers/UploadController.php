<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\redactor\controllers;

use yii\web\Response;

/**
 * @author Nghia Nguyen <yiidevelop@hotmail.com>
 * @since 2.0
 */
class UploadController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'file'      => 'app\modules\redactor\actions\FileUploadAction',
            'image'     => 'app\modules\redactor\actions\ImageUploadAction',
            'image-json'=> 'app\modules\redactor\actions\ImageManagerJsonAction',
            'file-json' => 'app\modules\redactor\actions\FileManagerJsonAction',
        ];
    }

}

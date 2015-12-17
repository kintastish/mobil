<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;


class ParamTypes extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'param_types';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['comment'], 'default', 'value' => ''],
            [['name', 'comment'], 'string', 'max' => 20],
            [['name'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название параметра',
            'comment' => 'Комментарий',
        ];
    }

    public static function getAllParams()
    {
    	return ParamTypes::find()->asArray()->all();
    }
}

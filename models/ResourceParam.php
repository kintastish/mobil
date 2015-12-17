<?php

namespace app\models;

use Yii;


class ResourceParam extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'params';
    }

    public function rules()
    {
        return [
            [['type_id', 'table_id', 'item_id', 'value'], 'required'],
            [['value'], 'string', 'max' => 200],
        ];
    }

    public function getType()
    {
        return $this->hasOne(ParamTypes::className(), ['id' => 'type_id']);
    }

}

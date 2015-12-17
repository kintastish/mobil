<?php

namespace app\models;

use Yii;


class Files extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'files';
    }

    public function rules()
    {
        return [
            [['attach_table', 'attach_id'], 'integer'],
            [['alias', 'filename', 'title'], 'string', 'max' => 50],
            [['path'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 200],
            [['description', 'base_url', 'base_dir'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Псевдоним',
            'filename' => 'Имя файла',
            'path' => 'Путь',
            'title' => 'Название',
            'description' => 'Описание',
            'attach_table' => 'ID присоединенной таблицы',
            'attach_id' => 'ID записи',
        ];
    }

    public function getUrl()
    {
        return $this->base_url.$this->path.$this->filename;
    }

    public function getPath()
    {
        return $this->base_dir.$this->path.$this->filename;
    }

    public function getAttachedModel()
    {
        switch ($this->attach_table) {
            case Resources::$tableId:
                $class = Resources;
                break;
            case Categories::$tableId:
                $class = Categories;
                break;
            default:
                throw new \yii\base\NotSupportedException('Неизвестный ID таблицы (Files@id:'.$this->id);
                break;
        }
        if (($model = $class::findOne($this->attach_id)) !== null) {
            return $model;
        } else {
            throw new \yii\db\IntegrityException('Связанный ресурс не найден (Files@id:'.$this->id);
        }
    }

    public static function getAttachedFiles($table_id, $id)
    {
        return Files::find()
            ->where(['attach_table' => $table_id,
                'attach_id' => $id
            ])->all();
    }
}

<?php

namespace app\models;

use Yii;

class Categories extends \yii\db\ActiveRecord
{
    public static $tableId = 1;

    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['alias', 'title'], 'required'],
            [['id'], 'integer'],
            [['alias'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 30],
            [['title'], 'trim'],
            [['alias'], 'match',
                'pattern' => '/^([a-z0-9]-?)+$/',
                'message' => 'Псевдоним может состоять только из латинских букв, цифр и дефисов и не должен начинаться с дефиса'
            ],
            [['alias'], 'unique',
                'targetAttribute' => ['alias', 'parent_id'],
                'message' => 'В текущем разделе уже есть элемент с таким псевдонимом. Выберите другой.'],
//            [['show_single'], 'default', 'value' => 1],
            // [['parent_id', 'handler', 'show_single'], 'safe'],
            [['parent_id', 'handler'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'ID родительского каталога',
            'alias' => 'Псевдоним',
            'title' => 'Название',
            'handler' => 'Тип содержимого',
        ];
    }

    // TODO заглушка вместо поля в БД. 
    public function getShow_single()
    {
        return 1;
    }

    public function getResources()
    {
        return $this->hasMany(Resources::className(), ['category_id' => 'id']);
    }

    public function getSubcategories()
    {
        return $this->hasMany(Categories::className(), ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_id']);
    }

    public function getIsEmpty()
    {
        $count = Categories::find()->where(['parent_id'=>$this->id])->count();
        if ($count == 0) {
            $count = Resources::find()->where(['category_id'=>$this->id])->count();
        }
        return ($count == 0);
    }

    public static function findByAlias($alias, $parent_id = 0)
    {
        return Categories::find()
            ->where(['alias' => $alias])
            ->andWhere(['parent_id' => $parent_id])
            ->one();
    }

    public static function findByHandler($h)
    {
        return Categories::find()
            ->where(['handler' => $h])->all();
    }

    public static function getHierarchy($id)
    {
        $cat = Categories::findOne($id);
        $hier = [];
        while ($cat !== null) {
            $hier[] = $cat;
            $cat = Categories::findOne($cat->parent_id);
        }
        return $hier;
    }

    public function getRoute()
    {
        $rt = '';
        $parents = Categories::getHierarchy($this->id);
        foreach ($parents as $p) {
            $rt = ($p->alias !== '0' ? $p->alias.'/' : '').$rt;
        }
                
        return $rt;
    }
    //TODO добавить выборку доступных разделов для создания ресурса
    // нужно чтобы пользователь мог создать страницу не заходя в раздел, выбрав его из списка
}

<?php

namespace app\models;

use Yii;

class Resources extends \yii\db\ActiveRecord
{
    public static $tableId = 2;

    public static function tableName()
    {
        return 'resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'alias', 'title', 'content'], 'required'],
            [['id', 'category_id', 'created'], 'integer'],
            [['content', 'description', 'keywords'], 'safe'],
            [['alias', 'title'], 'string', 'max' => 50],
            [['alias'], 'match',
                'pattern' => '/^([a-z0-9]-?)+$/',
                'message' => 'Псевдоним может состоять только из латинских букв, цифр и дефисов и не должен начинаться с дефиса'
            ],
            [['description'], 'string', 'max' => 500],
            [['keywords'], 'string', 'max' => 200]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
    
        $scenarios['page_create'] = ['category_id', 'title', 'alias', 'description'];
        $scenarios['album'] = ['category_id', 'title', 'alias'];

        return $scenarios;
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'ID раздела',
            'category' => 'Раздел',
            'created' => 'Дата создания',
            'alias' => 'Псевдоним',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'content' => 'Содержимое',
            'keywords' => 'Ключевые слова',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    public function getFiles()
    {
        return Files::find()
            ->where([
                'attach_table' => self::$tableId,
                'attach_id' => $this->id,
            ])->all();
    }

    public function getParams()
    {
        return ResourceParam::find()
            ->where([
                'table_id' => self::$tableId,
                'item_id' => $this->id
            ])->all();
    }

    public static function findByAlias($alias, $cat_id = 0)
    {
        return Resources::find()
            ->where(['alias' => $alias])
            ->andWhere(['category_id' => $cat_id])
            ->one();
    }

    public function getRoute()
    {
        $rt = $this->alias;
        $rt = $this->category->route.$rt.'/';
        return $rt;
    }

    public function getIsMainPage()
    {
        return $this->category->alias == '0';
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        $this->created = time();
        return true;
    }

    public function getParamValue($id)
    {
        $p = ResourceParam::find()->where([
            'table_id' => self::$tableId,
            'item_id' => $this->id,
            'type_id' => $id
        ])->one();

        if ($p !== null) {
            return $p->value;
        }
        return null;
    }    
}

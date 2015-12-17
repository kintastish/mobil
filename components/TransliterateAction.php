<?php

namespace app\components;

use Yii;
use yii\base\Action;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\web\Response;

class TransliterateAction extends Action
{
    public function init()
    {
    }

    public function run($t='')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->transliterate($t);
    }

    protected function transliterate($str)
    {
        $table = [
            'А' => 'a',
            'Б' => 'b',
            'В' => 'v',
            'Г' => 'g',
            'Д' => 'd',
            'Е' => 'e',
            'Ё' => 'e',
            'Ж' => 'zh',
            'З' => 'z',
            'И' => 'i',
            'Й' => 'j',
            'К' => 'k',
            'Л' => 'l',
            'М' => 'm',
            'Н' => 'n',
            'О' => 'o',
            'П' => 'p',
            'Р' => 'r',
            'С' => 's',
            'Т' => 't',
            'У' => 'u',
            'Ф' => 'f',
            'Х' => 'kh',
            'Ц' => 'c',
            'Ч' => 'ch',
            'Ш' => 'sh',
            'Щ' => 'shh',
            'Ъ' => '',
            'Ы' => 'y',
            'Ь' => '',
            'Э' => 'e',
            'Ю' => 'yu',
            'Я' => 'ya',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'kh',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shh',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya'
        ];

        $pat[] = '/[^\w\-\x20]+/';  // если символы не является буквой, цифрой, подчеркиванием, пробелом или дефисом
        $replace[] = '';            // удаляем
        $pat[] = '/\W+/';           // все кроме букв, цифр и подчеркиванием
        $replace[] = '-';           // заменяем на дефис
        $tr = preg_replace($pat, $replace, strtolower(str_replace(array_keys($table), array_values($table), $str)));
        return ['tr' => $tr];
    }

}

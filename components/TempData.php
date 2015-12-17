<?php

namespace app\components;

class TempData
{
    public static $contentTypes = [
        'cat' => 'Разделы',
        'page' => 'Веб-страницы',
        'gallery' => 'Галерея изображений',
        'file' => 'Файлы',
    ];
    public static $thumbDimension = [
    	'width' => 200,
    	'height' => 200,
    ];
}
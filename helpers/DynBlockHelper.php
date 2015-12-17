<?php
namespace app\helpers;

use Yii;
use yii\helpers\FileHelper;

class DynBlockHelper
{
    const WIDGET_SUFFIX = 'Widget';
    const WIDGET_NS = '\\app\\widgets\\dynblocks\\';
    const MODEL_SUFFIX = 'WidgetModel';
    const MODEL_NS = '\\app\\models\\';
    const BLOCK_EXT = '.conf';
    const WIDGET_DIR = '@app/widgets/dynblocks';
    const BLOCK_DIR = '@app/views/dynblocks';


    public static function widgetIdByClassName($class)
    {
        $s = str_replace([self::WIDGET_NS, self::WIDGET_SUFFIX, '\\'], '', $class);
        $s = strtolower(preg_replace('/(\\B[A-Z])/', '-$1', $s));
        return $s;
    }

    public static function widgetClassById($id, $full = false)
    {
        $class = str_replace(' ', '', ucwords(str_replace('-', ' ', $id))).self::WIDGET_SUFFIX;
        return ($full ? self::WIDGET_NS : '').$class;
    }

    public static function widgetModelClassNameById($id)
    {
        $widgetClass = self::widgetClassById($id);
        $class = self::MODEL_NS.str_replace(self::WIDGET_SUFFIX, self::MODEL_SUFFIX, $widgetClass);
        return $class;
    }

    public static function readConfig($id)
    {
        $fn = self::configFile($id);
        if (file_exists($fn)) {
            $conf = require($fn);
            return $conf;
        }
        return null;
    }

    public static function widgetList()
    {
        $dir = Yii::getAlias(self::WIDGET_DIR);
        $files = FileHelper::findFiles($dir, [
            'only' => ['*'.self::WIDGET_SUFFIX.'.php']
        ]);
        $a = [];
        foreach ($files as $f) {
            $widgetClass = self::WIDGET_NS.str_replace('.php', '', pathinfo($f, PATHINFO_BASENAME));
//            $w = new $widgetClass;
            $id = self::widgetIdByClassName($widgetClass);
            $a[$id] = $widgetClass::getInfo();
        }
        return $a;
    }

    public static function blockList()
    {
        $dir = Yii::getAlias(self::BLOCK_DIR);
        $files = FileHelper::findFiles($dir, [
            'only' => ['*'.self::BLOCK_EXT]
        ]);
        $a = [];
        foreach ($files as $f) {
            $blockId = str_replace(self::BLOCK_EXT, '', pathinfo($f, PATHINFO_BASENAME));
            $conf = self::readConfig($blockId);
            $conf = $conf['__block'];
            $class = $conf['widgetClass'];
            $info = $class::getInfo();
            $label = $info['label'];
            $a[] = [
                'id' => $blockId,
                'comment' => $conf['comment'],
                'label' => $label
            ];
        }
        return $a;      
    }

    public static function configFile($id)
    {
        return FileHelper::normalizePath(Yii::getAlias(self::BLOCK_DIR).DIRECTORY_SEPARATOR.$id.self::BLOCK_EXT);
    }
}

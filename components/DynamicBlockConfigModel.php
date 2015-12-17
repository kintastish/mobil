<?php
namespace app\components;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\base\UnknownPropertyException;

class DynamicBlockConfigModel extends Model
{
    const WIDGET_DIR = '@app/widgets/dynblocks';
    const WIDGET_NS = '\\app\\widgets\\dynblocks\\';
    const BLOCKS_DIR = '@app/views/dynblocks';
    const SUFFIX = 'Widget';
    private $_file = '';
    private $_params = [];
    private $_w;
    private $_class;
    private $_attr = [];

    public $widgetId = '';          //краткое наименование класса виджета
    public $blockName = '';
    public $blockComment = '';

    public function init()
    {
        parent::init();
        if ($this->widgetId !== '') {
            $this->loadConfigParams();
        }
        if ($this->blockName !== '') {
            $this->loadBlockConfiguration();
        }
    }

    public function createBlock()
    {
        $this->loadConfigParams();
        $this->setAttributes([]);
        $this->saveBlockConfiguration();
    }

    public function getBlockExists()
    {
        if ($this->blockName == '') return true;
        $dir = Yii::getAlias(self::BLOCKS_DIR);
        return file_exists( $dir.'/'.$this->blockName.'.php' );
    }

    /*
        Выборка информации о всех виджетах
    */
    public static function getWidgetList()
    {
        $dir = Yii::getAlias(self::WIDGET_DIR);
        $files = FileHelper::findFiles($dir, [
            'only' => ['*'.self::SUFFIX.'.php']
        ]);
        $a = [];
        foreach ($files as $path) {
            $className = str_replace('.php', '', pathinfo($path, PATHINFO_BASENAME));
            $widgetId = self::getWidgetIdByClass($className);
            $a[$widgetId] = self::getWidgetInfo($widgetId);
            $a[$widgetId]['namespace'] = self::WIDGET_NS;
        }
        return $a;
    }

    /*Получение списка всех используемых блоков*/
    public static function getBlockList()
    {
        $dir = Yii::getAlias(self::BLOCKS_DIR);
        $files = FileHelper::findFiles($dir, [
            'only' => ['*.php']
        ]);
        $a = [];
        foreach ($files as $f) {
            $blockName = str_replace('.php', '', pathinfo($f, PATHINFO_BASENAME));
            $a[] = [
                'id' => $blockName
            ];
        }
        return $a;
    }

    public function getAttributes($names = null, $except = [])
    {
        return $this->_attr;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        foreach ($this->_params as $par => $config) {
            $this->_attr[$par] = $config['default'];
            if ( isset($config['set']) ) {
                if ( array_key_exists($par, $values) ) {
                    if ( is_array($values[$par]) ) {
                        foreach ($values[$par] as $arr) {
                            foreach ($config['set'] as $p => $label) { $t[$p] = null; }

                            foreach ($arr as $p => $v) {
                                if ( array_key_exists($p, $t)!==false ) {
                                    $t[$p] = $v;
                                }
                            }
                            $this->_attr[$par][] = $t;
                        }
                    }
                }
            }
            else {
                if (isset($values[$par]) ) {                     //TODO а если $values[$par] массив?
                    $this->_attr[$par] = $values[$par];
                }
            }
        }
    }

    public function attributeLabels($value='')
    {
        $labels = [];
        foreach ($this->_params as $param => $conf) {
            if (isset($conf['label'])) {
                $labels[$param] = $conf['label'];
            }
        }
        return $labels;
    }

    public function saveBlockConfiguration()
    {
        $fn = $this->getBlockConfFilename();
        $conf = $this->getAttributes();
        $conf['__info'] = ['comment' => $this->blockComment, 'widgetId' => $this->widgetId];
        $s = VarDumper::export( $conf );
        $s = "<?php\nreturn ".$s.";";
        file_put_contents($fn, $s);
    }

    public function loadBlockConfiguration()
    {
        $fn = $this->getBlockConfFilename();
        if (file_exists($fn)) {
            $conf = require($fn);
            $this->widgetId = $conf['__info']['widgetId'];
            $this->loadConfigParams();
            $this->setAttributes($conf);
        }
        else {
            $this->setAttributes([]);
        }
    }

    public function loadConfigParams()
    {
        if ($this->widgetId == '') {
            throw new \yii\base\InvalidConfigException('Не задан параметр widgetId');
        }
        $this->_class = $this->getWidgetClassById($this->widgetId, true);
        $this->_w = new $this->_class;
        $this->_params = $this->_w->getConfigParams();
    }

    private function getBlockConfFilename()
    {
        return FileHelper::normalizePath(Yii::getAlias(self::BLOCKS_DIR).DIRECTORY_SEPARATOR.$this->blockName.'.conf');
    }

    private static function getWidgetIdByClass($class)
    {
        $s = str_replace(self::SUFFIX, '', $class);
        $s = strtolower(preg_replace('/(\\B[A-Z])/', '-$1', $s));
        return $s;
    }

    private static function getWidgetClassById($id, $full = false)
    {
        $class = str_replace(' ', '', ucwords(str_replace('-', ' ', $id))).self::SUFFIX;
        return ($full ? self::WIDGET_NS : '').$class;
    }

    private static function getWidgetInfo($wid)
    {
        $class = self::getWidgetClassById($wid, true);
        return $class::getInfo();
    }

    public function __get($name)
    {
        try {
            parent::__get($name);
        } catch (UnknownPropertyException $e) {
            if (isset($this->_attr[$name])) {
                return $this->_attr[$name];
            }
        }
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (UnknownPropertyException $e) {
            if (isset($this->_attr[$name])) {
                $this->_attr[$name] = $value;
            }
        }
    }    
}
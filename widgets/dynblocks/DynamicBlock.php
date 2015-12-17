<?php 

namespace app\widgets\dynblocks;

use yii\base\Widget;
use yii\helpers\FileHelper;
use app\helpers\DynBlockHelper;

class DynamicBlock extends Widget
{
	public $blockId;

    protected $_config = [];
	protected $_info = [];

/*	public function getConfigParams()
	{
		return [];
	}
*/
	public function init()
	{
		$this->loadConfig();
	}

	public static function getInfo()
	{
		return [
			'label' => 'Динамический блок',
			'description' => 'Это блок ничего не умеет делать'
		];
	}

    public static function loadWidget($id)
    {
        $c = DynBlockHelper::readConfig($id);
        if ($c !== null) {
            $class = $c['__block']['widgetClass'];
            return new $class(['blockId' => $id]);
        }
        return null;
    }

    private function loadConfig()
    {
    	$c = DynBlockHelper::readConfig($this->blockId);
    	if ($c != null) {
            $this->_info = $c['__block'];
            unset($c['__block']);
            $this->_config = $c;
    	}
    }

}
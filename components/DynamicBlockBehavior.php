<?php 

namespace app\components;

use Yii;
use yii\base\View;
use yii\base\Behavior;
use app\widgets\dynblocks\DynamicBlock;

/**
* Поведения для View. Обрабатывает динамические блоки {*название_блока*}
*/
class DynamicBlockBehavior extends Behavior
{
	private $_output = [];

    public function events()
    {
        return [
            View::EVENT_AFTER_RENDER => 'processContent'
        ];
    }

    public function processContent($event)
    {
    	$m = [];
        $this->_output = [];
    	if ( preg_match_all('/\{\*([a-z][a-z0-9_]+)\*\}/', $event->output, $m) ) {
            if (isset($m[1])) {
    			$this->prepareBlocks($m[1]);
    			$this->renderBlocks($event->output, $m);
    		}
    	}
    }

    private function prepareBlocks($blocks)
    {
        foreach ($blocks as $id) {
            if (!isset($this->_output[$id])) {
	    		$b = DynamicBlock::loadWidget($id);
	    		if ($b != null) {
	    			$this->_output[$id] = $b::widget(['blockId' => $id]);
	    		}
	    		else {
	    			$this->_output[$id] = '&#123;&#42;'.$id.'&#42;&#125;';
	    		}
    		}
    	}
    }

    private function renderBlocks(&$text, $matches)
    {
    	// foreach ($placeholders[0] as $ind => $p) {
    	// 	$id = $placeholders[1][$ind];
    	// 	if ( isset($this->_output[$id]) ) {
    	// 		$text = str_replace($p, $this->_output[$id], $text);
    	// 	}
    	// }
    	$text = str_replace(array_values($matches[0]), $this->_output, $text);
    }
}
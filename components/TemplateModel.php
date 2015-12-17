<?php 
namespace app\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownPropertyException;
use yii\helpers\VarDumper;
/**
* 
*/
class TemplateModel extends \yii\base\Model
{
	public $view = '';
	public $params = [];
	public $values = [];

	public $content;
	public $placeholders = [];

	private $_file = '';
	private $_file_content;
	private $_block_def;
	private $_block_val;
	private $_block_implement;

	public function init()
	{
		if ($this->view == '') {
			throw new InvalidConfigException('Параметр $view не задан');
		}
		$this->findFile();
		$this->loadTemplate();
		$this->getVarDefinitions();
		$this->getVarValues();
		$this->prepareContent();
	}

	private function loadTemplate()
	{
		$this->_file_content = file_get_contents($this->_file);

		$blocks = preg_split('/(\/\/\s\?>\r?\n?<\?php|<\?php|\/\/\s\?>)/U', $this->_file_content);
		unset($blocks[0]);
		foreach ($blocks as $b) {
			preg_match('/\/\/VAR_(DEF|VAL|IMPLEMENT)/', $b, $m);
			if (isset($m[1])) {
				$block_name = '_block_'.strtolower($m[1]);
				$this->$block_name = $b;
			}
			else {
				$this->content = $b;
			}
		}
		
		$this->_file_content = '';		
	}

	private function getVarDefinitions()
	{
		eval($this->_block_def);
		$this->params = $param_vars;
		$this->placeholders = $content_vars;
	}

	private function getVarValues()
	{
		eval($this->_block_val);
		foreach ($this->params as $var => $value) {
			if (isset($$var)) {
				$this->values[$var] = $$var;
			}
		}
	}

	private function prepareContent($save = false)
	{
		$search = [];
		$replace = [];
		foreach ($this->placeholders as $name => $label) {
			if ($save) {
				$search[] = '[*'.$label.'*]';
				$replace[] = '<?= $'.$name.' ?>';
			}
			else {
				$search[] = '<?= $'.$name.' ?>';
				$replace[] = '[*'.$label.'*]';
			}
		}
		if (count($search)) {
			$this->content = str_replace($search, $replace, $this->content);
		}
	}

	public function save()
	{
		$this->buildDefinitionsBlock();
		$this->buildValueBlock();
		$this->buildImplementationBlock();
		$this->buildContent();
		file_put_contents($this->_file, $this->_file_content);
	}
	
	private function buildDefinitionsBlock()
	{
		$b = $this->openBlock('VAR_DEF');
		$b.= '$content_vars = '.VarDumper::export($this->placeholders).";\n";
		$b.= '$param_vars = '.VarDumper::export($this->params).";\n";
		$b.= $this->closeBlock();
		$this->_file_content .= $b;
	}

	private function buildValueBlock()
	{
		$b = $this->openBlock('VAR_VAL');
		foreach ($this->params as $var => $label) {
			$v = $this->values[$var];
			if (!is_numeric($this->values[$var])) {
				$v = "'".$v."'";
			}
			$b .= "\t".'$'.$var.' = '.$v.";\n";
		}
		$b.= $this->closeBlock();
		$this->_file_content .= $b;
	}

	private function buildImplementationBlock()
	{
		$b = '<?php ';
		$b.= $this->_block_implement;
		$b.= $this->closeBlock();
		$this->_file_content .= $b;
	}

	private function buildContent()
	{
		$this->prepareContent(true);
		$this->_file_content .= "\n".$this->content;
	}

	private function openBlock($name)
	{
		return '<?php //'.$name."\n";
	}

	private function closeBlock()
	{
		return $b.= '// ?>'."\n";
	}

	private function findFile()
	{
		$f = Yii::getAlias( $this->view ).'.php';
		if (!file_exists($f)) {
			throw new InvalidConfigException('Файл <b>'.$this->view.'</b> не найден');
		}
		$this->_file = $f;
	}

	public function __get($name)
	{
		try {
			parent::__get($name);
		} catch (UnknownPropertyException $e) {
			if (isset($this->params[$name])) {
				return $this->values[$name];
			}
		}
	}

	public function __set($name, $value)
	{
		try {
			parent::__set($name, $value);
		} catch (UnknownPropertyException $e) {
			if (isset($this->params[$name])) {
				$this->values[$name] = $value;
			}
		}
	}

	public function attributeLabels()
    {
    	return \yii\helpers\ArrayHelper::merge($this->params, ['content' => 'Текст']);
    }

	public function setAttributes($values, $safeOnly = false)
    {
        if (is_array($values)) {
            foreach ($values as $name => $value) {
                if (isset($this->$name)) {
                	$this->$name = $value;
                }
                elseif (isset($this->params[$name])) {
                	$this->values[$name] = $value;
                }
            }
        }
    }    
}
<?php 
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use app\widgets\dynblocks\DynamicBlock;
use app\helpers\DynBlockHelper;

class DynamicBlockModel extends Model
{
	public $widgetId;
	public $blockId;
	public $comment;

	private $_attr;	

	public function init()
	{
		$this->loadDefaults();
		if ($this->blockId != null) {
			$this->loadConfig();
		}
	}

	public function rules()
	{
		return [
			[['blockId', 'comment'], 'required', 'message'=>'Поле обязательно для заполнения'],
			[['blockId'], 'string', 'max'=>20],
			[['comment'], 'string', 'max'=>50],
			[['blockId'], 'match', 'pattern' => '/^[a-z][a-z0-9_]+$/i', 'message' => 'Имя может содержать только латинские символы и цифры без пробелов и должно начинаться с буквы'],
			[['widgetId', 'comment'], 'safe'],
		];
	}

	public function afterValidate()
	{
		parent::afterValidate();
		$fn = DynBlockHelper::configFile($this->blockId);
		if (file_exists($fn)) {
			$this->addError('blockId', 'Такой идентификатор уже используется');
		}
	}

    public function createBlock()
    {
        $this->saveConfig();
    }

	public function setAttributes($values, $safeOnly = false)
	{
		parent::setAttributes($values, $safeOnly);
	}

	public function beforeSave()
	{
	}

	public function saveConfig()
	{
        $this->beforeSave();
        $conf = $this->getAttributes();
        unset($conf['blockId'], $conf['widgetId'], $conf['comment']);
        $conf['__block'] = [
        	'widgetId' => $this->widgetId,
        	'comment' => $this->comment,
        	'widgetClass' => DynBlockHelper::widgetClassById($this->widgetId, true),
        	'modelClass' => DynBlockHelper::widgetModelClassNameById($this->widgetId)
        ];

        $s = VarDumper::export( $conf );
        $s = "<?php\nreturn ".$s.";";
        
        $fn = DynBlockHelper::configFile($this->blockId);
        file_put_contents($fn, $s);
	}

	public function loadConfig()
	{
		$c = DynBlockHelper::readConfig($this->blockId);
		if ($c != null) {
			$this->widgetId = DynBlockHelper::widgetIdByClassName($c['__block']['widgetClass']);
			$this->comment = $c['__block']['comment'];
			unset($c['__block']);
			$this->setAttributes($c, false);
		}
		$this->afterLoadConfig();
	}

	public function afterLoadConfig()
	{
	}

	public function loadDefaults()
	{
		$this->blockId = 'dynblock';
		$this->comment = '';
	}

    public static function loadBlockConfigModel($id)
    {
    	$c = DynBlockHelper::readConfig($id);
    	if ($c != null) {
            $modelClass = $c['__block']['modelClass'];
	        return new $modelClass(['blockId' => $id]);
    	}
    	return null;
    }
}
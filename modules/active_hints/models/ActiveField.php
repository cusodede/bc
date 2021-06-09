<?php
declare(strict_types = 1);

namespace app\modules\active_hints\models;

use app\modules\active_hints\ActiveHintsModule;
use app\modules\active_hints\widgets\active_hints\ActiveHints;
use Exception;
use Throwable;
use Yii;
use yii\bootstrap4\ActiveField as ActiveFieldBs4;

/**
 * Class ActiveField
 */
class ActiveField extends ActiveFieldBs4 {

	/**
	 * @var bool whether to render the active hint. Default is `true`.
	 */
	public bool $enabled = true;
	/**
	 * @var bool whether to render the active hint editor or just show the hint contents. Default is `true`.
	 */
	public $editable = false;

	public $template = "{label}{activeHint}\n{input}\n{error}";

	public function init():void {
		parent::init();
		$this->template = ActiveHintsModule::getConfigParameter('template');
		$this->enabled = ActiveHintsModule::getConfigParameter('enabled');
		if (is_callable($this->enabled)) $this->enabled = call_user_func($this->enabled, Yii::$app->user->identity);
		$this->editable = ActiveHintsModule::getConfigParameter('editable');
		if (is_callable($this->editable)) $this->editable = call_user_func($this->editable, Yii::$app->user->identity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function render($content = null):string {
		if (null === $content && $this->enabled) {
			$this->parts['{activeHint}'] = $this->getActiveHintContents();
		}
		return parent::render($content);
	}

	/**
	 * @return string
	 * @throws Exception
	 * @throws Throwable
	 */
	private function getActiveHintContents():string {
		return ActiveHints::widget([
			'model' => $this->model,
			'attribute' => $this->attribute,
			'editable' => $this->editable,
			'editAction' => ActiveHintsModule::to(['default/edit-hint', 'model' => get_class($this->model), 'attribute' => $this->attribute])
		]);
	}

}
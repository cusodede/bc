<?php
declare(strict_types = 1);

namespace app\modules\active_hints\models;

use app\modules\active_hints\ActiveHintsModule;
use app\modules\active_hints\widgets\active_hints\ActiveHints;
use Exception;
use Throwable;
use yii\bootstrap4\ActiveField as ActiveFieldBs4;

/**
 * Class ActiveField
 */
class ActiveField extends ActiveFieldBs4 {

	/**
	 * @var bool whether to render the active hint. Default is `true`.
	 */
	public bool $enableActiveHint = true;
	/**
	 * @var bool whether to render the active hint editor or just show the hint contents. Default is `true`.
	 */
	public $editable = true;

	public $template = "{label}{activeHint}\n{input}\n{error}";

	/**
	 * {@inheritdoc}
	 */
	public function render($content = null) {
		if ($content === null) {
			$this->parts['{activeHint}'] = $this->getActiveHintContents();
		}
		return parent::render($content);
	}

	/**
	 * @return string
	 * @throws Exception
	 * @throws Throwable
	 */
	private function getActiveHintContents() {
		return ActiveHints::widget([
			'model' => $this->model,
			'attribute' => $this->attribute,
			'editable' => $this->enableActiveHint,
			'editAction' => ActiveHintsModule::to(['default/edit-hint', 'model' => $this->model, 'attribute' => $this->attribute])
		]);
	}

}
<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use app\assets\AppAsset;
use app\assets\ModalHelperAsset;
use app\modules\active_hints\ActiveHintsModule;
use app\modules\active_hints\models\ActiveStorage;
use kartik\popover\PopoverX;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap4\BootstrapAsset;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * @property bool $editable Редактируемая подсказка
 * @property Model $model Модель подсказки
 * @property string $attribute Атрибут модели подсказки
 * @property string $editAction Url экшена для сохранения подсказки
 * @property-read string $toggleButtonClass -- сгенерированный класс кнопки
 */
class ActiveHints extends Widget {
	private string $_for;
	private $_record;

	public bool $editable = false;
	public string $footer = '';
	public ?Model $model = null;
	public ?string $attribute = null;
	public ?string $editAction = null;
	public ?string $modalAction = null;

	private array $_toggleButtonClass = ['main' => 'hint-button', 'float' => 'pull-right']; //набор классов для кнопки, массив генерится в рантайме.

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		parent::init();
		ActiveHintsAssets::register($this->getView());
		if ($this->editable) ModalHelperAsset::register($this->getView());
		$this->_record = ActiveStorage::findActiveAttribute($this->model, $this->attribute);
		$this->editAction = $this->editAction??ActiveHintsModule::to(['default/set-hint', 'model' => $this->_record->model, 'attribute' => $this->_record->attribute]);
		$this->_for = "{$this->model->formName()}-{$this->attribute}";
	}

	/**
	 * @inheritDoc
	 */
	public function run():string {
		$output = '';
		$this->_toggleButtonClass['hint'] = (null === $this->_record->content)?'no-hint':'has-hint';
		if ($this->editable) {//Если у хинта нет контента, то показываем его только для редактирования
			$output = Html::button('<i class="fa fa-comment-alt-edit"></i>', [
				'class' => $this->toggleButtonClass,
				'onclick' => new JsExpression("AjaxModal('$this->editAction', '{$this->_for}-modal');event.preventDefault();"),
			]);
		}
		if (null !== $this->_record->content) {
			$output .= PopoverX::widget([
				'content' => $this->_record->content,
				'header' => $this->_record->header??'Подсказка',
				'placement' => $this->_record->placement??ActiveHintsModule::getConfigParameter('defaultPlacement'),
				'toggleButton' => $this->toggleButton??['label' => '<i class="fa fa-question-circle"></i>', 'class' => $this->toggleButtonClass],
				'footer' => $this->footer
			]);

		}

		return $output;
	}

	/**
	 * @return string
	 */
	public function getToggleButtonClass():string {//todo use Html::addCssClass instead
		return implode(' ', array_unique($this->_toggleButtonClass));
	}
}

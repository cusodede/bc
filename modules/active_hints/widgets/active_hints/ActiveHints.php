<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use app\modules\active_hints\ActiveHintsModule;
use app\modules\active_hints\models\ActiveStorage;
use kartik\editable\Editable;
use kartik\popover\PopoverX;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

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

	private array $_toggleButtonClass = ['main' => 'hint-button', 'float' => 'pull-right']; //набор классов для кнопки, массив генерится в рантайме.

	private static $popoverStack = [];

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		parent::init();
		ActiveHintsAssets::register($this->getView());
		$this->_record = ActiveStorage::findActiveAttribute($this->model, $this->attribute);
		$this->editAction = $this->editAction??ActiveHintsModule::to(['default/set-hint', 'model' => $this->_record->model, 'attribute' => $this->model->attribute]);
	}

	/**
	 * @inheritDoc
	 */
	public function run():string {
		$output = '';

		$this->_for = "{$this->model->formName()}-{$this->attribute}";

		$this->_toggleButtonClass['hint'] = (null === $this->_record->content)?'no-hint':'has-hint';
		if ($this->editable) {//Если у хинта нет контента, то показываем его только для редактирования
			$output = Html::button('<i class="fa fa-question-circle"></i>', ['class' => $this->toggleButtonClass, 'data-toggle' => 'popover-x', 'data-target' => "#hint-{$this->_for}-popover"]);
			/*todo: здесь нельзя рендерить Editable as is, потому что контент будет выводиться в форму. Нужно сделать ajax-загрузку в попап, рендерящийся в конце страницы*/
			$widget = Editable::widget([
				'formOptions' => [
					'action' => $this->editAction
				],
				'containerOptions' => [
					'style' => 'display:none'
				],
				'name' => $this->_for,
				'asPopover' => true,
				'value' => $this->_record->content,
				'preHeader' => 'Редактировать подсказку ',
				'header' => $this->_for,
				'size' => 'md',
				'inputType' => Editable::INPUT_TEXTAREA,
				'format' => Editable::FORMAT_BUTTON,
				'options' => [
					'class' => 'form-control',
					'placeholder' => 'Отредактируйте подсказку',
					'id' => "hint-{$this->_for}"//container id сгенерится как hint-{$this->for}-cont, popover id - hint-{$this->for}-popover
				],
				'displayValue' => false,
				'displayValueConfig' => [//при успехе вернётся ноль, вместо ноля выведем ничего
					0 => ''
				]
			]);

			/*self::$popoverStack[] =*/ $output .= $widget;//мы не можем вывести форму внутри формы, поэтому контент редакторов сохраняется в стеке и рендерится после вывода всего

		} elseif (null !== $this->_record->content) {//Если у хинта нет контента, то показываем его только для редактирования
			$output = PopoverX::widget([
				'content' => $this->_record->content,
				'header' => $this->_record->header??'Подсказка',
				'placement' => $this->_record->placement??PopoverX::ALIGN_RIGHT,
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

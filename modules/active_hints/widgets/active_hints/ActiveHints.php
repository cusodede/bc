<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use kartik\editable\Editable;
use kartik\popover\PopoverX;
use ReflectionClass;
use ReflectionProperty;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class PopoverHints
 * Общая логика: в связанной модели запрашиваются данные для тега в $for, и если такой тег есть - данные выводятся в хинте.
 * Если $editable - true, то нужно показать редактор, который запостит изменение в $this->action. И тут начинается пляска:
 * Editable widget генерирует редактор внутри ActiveForm (для использования механизмов валидации и пр.). Если вызывать Editable внутри другой открытой ActiveForm,
 * то рендерер виджетов Yii не позволит вывести одну форму внутри другой (что корректно), поля инпутов просто "подвиснут" без формы. Попытки захачить это поведение в JS
 * (подменив форму на div с теми же параметрами, а функцию отправки заставить думать, что это форма) не проканали: блокируется либо одна форма, либо другая, и вообще -- это костыль.
 * Editable в текущем состоянии без ActiveForm работать не умеет (в коде есть намёки, что это планировалось, но так и не реализовано).
 *
 * Нашлось такое решение: если виджет вызывается внутри формы, то внутри её ставить только кнопку вызова, а сам блок с формой и полями выводить уже после формы.
 * Editable не умеет отделять кнопку от контейнера. Но он основан на Bootstrap Popover, поэтому мы знаем, как сгенерировать отдельную кнопку там, где надо (а у Editable её просто спрячем).
 *
 * @property string $for -- идентификатор подсказки
 * @property ActiveStorageInterface|null $attachedStorage -- модель хранения данных, если не указана, используется self::DEFAULT_STORAGE
 * @property bool $editable -- true для отображения редактора, по умолчанию проверяем доступы
 * @property Model $model -- если виджет привязывается к ActiveField-полю в форме, то передаём модель
 * @property string $attribute -- и атрибут
 * @property string|null $action -- url постинга при сохранении, по умолчанию - постим в targets/ajax/set-hint
 * @property-read string $toggleButtonClass -- сгенерированный класс кнопки
 */
class ActiveHints extends Widget {
	/** @var ActiveStorageInterface */
	public const DEFAULT_STORAGE = ActiveStorage::class;

	public $for;
	public $editable;
	public $attachedStorage = self::DEFAULT_STORAGE;
	public $footer = '';
	public $model;
	public $attribute;

	private $_toggleButtonClass = ['main' => 'hint-button', 'float' => 'pull-right']; //набор классов для кнопки, массив генерится в рантайме.
	private $isFormLabelHint = false;
	private $outputFlag = false;//true, если контент уже выведен, чтобы run() не дублировал вывод

	private static $popoverStack = [];

	/**
	 * @return array
	 */
	private function attributes():array {
		$class = new ReflectionClass($this);
		$names = [];
		foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			if (!$property->isStatic()) {
				$names[] = $property->getName();
			}
		}
		return $names;
	}

	/**
	 * @param array $values
	 */
	private function applyAttributes(array $values):void {
		$attributes = array_flip($this->attributes());
		foreach ($values as $name => $value) {
			if (isset($attributes[$name])) {
				$this->$name = $value;
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		parent::init();
		ActiveHintsAssets::register($this->getView());
	}

	/**
	 * @param array $config -- виджет может быть вызван
	 * @return string
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function activeLabel(array $config):string {
		$output = '';
		$this->outputFlag = true;
		$this->applyAttributes($config);
		$this->isFormLabelHint = (null !== $this->model && null !== $this->attribute);

		if ($this->isFormLabelHint) {
			$this->for = "{$this->model->formName()}-{$this->attribute}";
		}

		if (null === $record = $this->attachedStorage::find()->where(['for' => $this->for])->one()) {
			$record = new $this->attachedStorage([
				'for' => $this->for
			]);
		}
		/** @var ActiveStorageInterface $record */
		$this->_toggleButtonClass['hint'] = (null === $record->content)?'no-hint':'has-hint';
		if ($this->editable) {//Если у хинта нет контента, то показываем его только для редактирования
			$output = Html::button('<i class="fa fa-question-circle"></i>', ['class' => $this->toggleButtonClass, 'data-toggle' => 'popover-x', 'data-target' => "#hint-{$this->for}-popover"]);

			$widget = Editable::widget([
				'formOptions' => [
					'action' => $this->action
				],
				'containerOptions' => [
					'style' => 'display:none'
				],
				'name' => $this->for,
				'asPopover' => true,
				'value' => $record->content,
				'preHeader' => 'Редактировать подсказку ',
				'header' => $this->for,
				'size' => 'md',
				'inputType' => Editable::INPUT_TEXTAREA,
				'format' => Editable::FORMAT_BUTTON,
				'options' => [
					'class' => 'form-control',
					'placeholder' => 'Отредактируйте подсказку',
					'id' => "hint-{$this->for}"//container id сгенерится как hint-{$this->for}-cont, popover id - hint-{$this->for}-popover
				],
				'displayValue' => false,
				'displayValueConfig' => [//при успехе вернётся ноль, вместо ноля выведем ничего
					0 => ''
				]
			]);

			if ($this->isFormLabelHint) {
				self::$popoverStack[] = $widget;//сохраним для вывода в ::end
			} else {
				$output .= $widget;//выведем сейчас
			}

		} elseif (null !== $record->content) {//Если у хинта нет контента, то показываем его только для редактирования
			$output = PopoverX::widget([
				'content' => $record->content,
				'header' => $record->header??'Подсказка',
				'placement' => $record->placement??PopoverX::ALIGN_RIGHT,
				'toggleButton' => $this->toggleButton??['label' => '<i class="fa fa-question-circle"></i>', 'class' => $this->toggleButtonClass],
				'footer' => $this->footer
			]);

		}
		if ($this->isFormLabelHint) {
			$output = '<div style="width: max-content">'.$this->model->getAttributeLabel($this->attribute).$output.'</div>';
		}
		return $output;
	}

	/**
	 * @inheritDoc
	 */
	public function run():string {
		return ($this->outputFlag)?'':$this->activeLabel($this->attributes());
	}

	/**
	 * {@inheritDoc}
	 */
	public static function end():Widget {
		foreach (self::$popoverStack as $renderedPopover) {
			echo $renderedPopover;
		}
		self::$popoverStack = [];
		return parent::end();
	}

	/**
	 * @param string $for
	 * @return array
	 */
	public static function actionSetHint(string $for):array {
		if (null !== Yii::$app->request->post('hasEditable')) {
			$popover = ActiveStorageInterface::getInstance(['for' => $for]);
			$popover->loadArray([
				'for' => $for,
				'content' => Yii::$app->request->post($for)
			]);
			if (!$popover->save()) {
				return ['output' => '', 'message' => $popover->errors];
			}
		}

		return ['output' => '0', 'message' => ''];//0 - Для displayValueConfig в виджете
	}

	/**
	 * @return string
	 */
	public function getToggleButtonClass():string {//todo use Html::addCssClass instead
		return implode(' ', array_unique($this->_toggleButtonClass));
	}
}

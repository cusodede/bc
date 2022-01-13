<?php
declare(strict_types = 1);

namespace app\components\grid;

use app\components\helpers\Html;
use app\components\helpers\TemporaryHelper;
use Exception;
use kartik\grid\ActionColumn as KartikActionColumn;
use kartik\grid\GridView;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class ActionColumn
 * Пример переопределения параметров во вьюхе:
 * 'columns' =>
 *    [
 *        'class' => ActionColumn::class,
 *        'hideDisabledButtons' => true,
 *        'showTooltip' => false,
 *        'deleteOptions' => [
 *            'data-confirm' => 'Ты хорошо подумал???',
 *        ],
 *        'buttons' => [...],
 *    ]
 */
class ActionColumn extends KartikActionColumn {
	/** @var string */
	public $hAlign = GridView::ALIGN_LEFT;

	/**
	 * @var GridView Меняем определение родительского метода - тут мы подвязаны на картиковский компонент.
	 */
	public $grid;

	/** @var bool */
	public $dropdown = false;

	/** @var bool
	 * Флаг отвечает за скрытие кнопок к которым нет доступа
	 * true - кнопок не будет
	 * false - кнопки статут disabled
	 */
	public $hideDisabledButtons = true;

	/** @var bool
	 * Флаг отвечает за отображение тултипов
	 */
	public $showTooltip = true;

	/** @var array[]
	 * Общие параметры для кнопок
	 */
	public $buttonOptions = [
		'class' => 'btn btn-sm btn-outline-primary',
		'useAjaxModal' => Html::CONFIG_OPTION,
		'data' => [
			'toggle' => 'tooltip',
			'trigger' => 'hover',
			'placement' => 'top',
			'pjax' => 0,
		],
	];

	public $editOptions = [
		'title' => 'Редактировать',
	];

	public $viewOptions = [];

	public $updateOptions = [];

	public $deleteOptions = [
		'data-confirm' => 'Are you sure to delete this {item}?',
		'useAjaxModal' => Html::NO,
	];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = []) {
		if (!empty($config)) {
			foreach ($config as $name => $value) {
				if (isset($this->{$name}) && is_array($this->{$name})) {
					$config[$name] = array_replace_recursive($this->{$name}, $value);
				}
			}
		}
		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	protected function initDefaultButtons():void {
		if (!$this->showTooltip) {
			$this->buttonOptions['data']['toggle'] = 'disable';
		}
		parent::initDefaultButtons();
		$this->setDefaultButton('edit', $this->editOptions['title'], 'edit');
	}

	/**
	 * Копипаст метода картика, с адаптацией под наши нужды
	 * Sets a default button configuration based on the button name (bit different than [[initDefaultButton]] method)
	 *
	 * @param string $name button name as written in the [[template]]
	 * @param string $title the title of the button
	 * @param string $icon the meaningful glyphicon suffix name for the button
	 * @throws Exception
	 */
	protected function setDefaultButton($name, $title, $icon):void {
		if (isset($this->buttons[$name])) {
			return;
		}

		$this->buttons[$name] = function($url) use ($name, $title, $icon) {
			$name = TemporaryHelper::DashesToCamelCase($name);
			$opts = "{$name}Options";
			$options = ['title' => ($this->{$opts}['title'])??$title];

			if ($this->grid->enableEditedRow && 'delete' !== $name) {
				Html::addCssClass($options, 'enable-edited-row');
			}

			if (isset($this->{$opts}['data-confirm'])) {
				$item = $this->grid->itemLabelSingle?:Yii::t('kvgrid', 'item');
				$options['data']['confirm'] = Yii::t('kvgrid', $this->{$opts}['data-confirm'], ['item' => $item]);
			}

			$options = array_replace_recursive($options, $this->buttonOptions, $this->$opts);
			$label = $this->renderLabel($options, $title, ['class' => $this->grid->getDefaultIconPrefix().$icon, 'aria-hidden' => 'true']);

			$link = Html::link($label, $url, $options, $this->{$opts}['useAjaxModal']??Html::CONFIG_OPTION);

			if (!$this->hideDisabledButtons && empty($link)) {
				Html::addCssClass($options, 'disabled');
				$link = Html::a($label, '#', $options);
			}

			return $link;
		};
	}
}
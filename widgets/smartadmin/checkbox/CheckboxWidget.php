<?php
declare(strict_types = 1);

namespace app\widgets\smartadmin\checkbox;

use yii\base\Widget as YiiBaseWidget;
use yii\helpers\Html;
use yii\widgets\ActiveField;

/**
 * Class CheckboxWidget
 * @package app\widgets\smartadmin\checkbox
 */
class CheckboxWidget extends YiiBaseWidget
{
	public ActiveField $field;

	public function init(): void
	{
		parent::init();

		$this->field->options  = ['class' => 'form-group text-left'];
		$this->field->template = Html::tag(
			'div',
			"{input}\n{label}\n{hint}\n{error}",
			['class' => 'custom-control custom-checkbox']);
	}

	public function run(): string
	{
		return (string)$this->field->checkbox(['class' => 'custom-control-input'], false)->label(null, ['class' => 'custom-control-label']);
	}
}
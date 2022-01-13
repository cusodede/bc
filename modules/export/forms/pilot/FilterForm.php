<?php
declare(strict_types = 1);

namespace app\modules\export\forms\pilot;

use yii\base\Model;

/**
 * Class FilterForm
 * @package app\modules\export\forms\pilot
 * @property null|string $region_bee
 * @property null|string $date_range
 */
class FilterForm extends Model {
	public ?int $region_bee = null;
	public ?string $date_range = null;

	/**
	 * @return array
	 */
	public function rules():array {
		return [
			[['region_bee'], 'integer'],
			[['date_range'], 'string']
		];
	}

	/**
	 * @return array
	 */
	public function attributeLabels():array {
		return [
			'region_bee' => 'Регион',
			'date_range' => 'Дата продажи'
		];
	}

}

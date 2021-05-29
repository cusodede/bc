<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\regions\active_record\references\RefRegions;
use app\models\store\active_record\references\RefSellingChannels;
use app\models\store\Stores;
use app\models\store\StoresSearch;

/**
 * Class StoresController
 */
class StoresController extends DefaultController {

	public string $modelClass = Stores::class;
	public string $modelSearchClass = StoresSearch::class;

	public function getMappingRules():array {
		return [
			/*номер столбца => правило сопоставления*/
			0 => [
				'attribute' => 'region',
				'foreign' => [
					'class' => RefRegions::class,
					'attribute' => 'name',//в какое поле вставить значение
					'key' => 'id'//что получить. Можно не указывать, тогда используется primaryKey
				]
			],
			1 => [
				'attribute' => 'branch',
				'foreign' => [
					'match' => static function(string $attributeValue) {
						if (null === $id = RefRegions::find()->select('id')->where(['like', 'name' => $attributeValue])->one()) {
							return null;
						}
						return (int)$id;
					},
					'class' => RefRegions::class,
					'attribute' => 'name',//в какое поле вставить значение
					'key' => 'id'//что получить. Можно не указывать, тогда используется primaryKey
				]
			],
			6 => ['attribute' => 'name'],
			7 => [
				'attribute' => 'type',
				'foreign' => [
					'class' => RefRegions::class,
					'attribute' => 'name',//в какое поле вставить значение
					'key' => 'id'//что получить. Можно не указывать, тогда используется primaryKey
				]
			],
			8 => [
				'attribute' => 'selling_channel',
				'foreign' => [
					'class' => RefSellingChannels::class,
					'attribute' => 'name',//в какое поле вставить значение
					'key' => 'id'//что получить. Можно не указывать, тогда используется primaryKey
				]
			],

		];
	}

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/stores';
	}

}
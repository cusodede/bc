<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;
use yii\db\ActiveQuery;

/**
 * Class Abonents
 * @package app\models\abonents
 *
 * @property RelAbonentsToProducts[] $relatedAbonentsToProducts
 */
class Abonents extends ActiveRecordAbonents
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasMany(RelAbonentsToProducts::class, ['abonent_id' => 'id']);
	}
}
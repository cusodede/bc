<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as AbonentsAR;
use yii\db\ActiveQuery;

/**
 * Class Abonents
 * @package app\models\abonents
 */
class Abonents extends AbonentsAR
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasMany(RelAbonentsToProducts::class, ['abonent_id' => 'id']);
	}
}
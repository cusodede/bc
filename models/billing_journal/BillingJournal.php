<?php
declare(strict_types = 1);

namespace app\models\billing_journal;

use app\models\abonents\RelAbonentsToProducts;
use app\models\billing_journal\active_record\BillingJournal as ActiveRecordBillingJournal;
use yii\db\ActiveQuery;

/**
 * Class BillingJournal
 * @package app\models\billing_journal
 */
class BillingJournal extends ActiveRecordBillingJournal
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}
}
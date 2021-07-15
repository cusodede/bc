<?php
declare(strict_types = 1);

namespace app\models\abonents\active_query;

use app\components\db\ActiveQuery;

/**
 * Class AbonentsActiveQuery
 * @package app\models\abonents\active_query
 */
class AbonentsActiveQuery extends ActiveQuery
{
	/**
	 * @param string $phone
	 * @return AbonentsActiveQuery
	 */
	public function withPhone(string $phone): self
	{
		return $this->andWhere(["{$this->_alias}.phone" => $phone]);
	}
}
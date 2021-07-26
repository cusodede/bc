<?php
declare(strict_types = 1);

namespace app\models\products\active_query;

use app\components\db\ActiveQuery;
use app\components\helpers\DateHelper;
use yii\db\Expression;

/**
 * Class ProductsActiveQuery
 * @package app\models\products\active_query
 */
class ProductsActiveQuery extends ActiveQuery
{
	/**
	 * У нас есть дата старта и дата окончания продукта, которые могут быть как даты в формате 'Y-m-d H:i:s',
	 * так и null, если допустим продукт бессрочный. Нам надо фильтровать активные и не активные продукты,
	 * если даты null считаем продукт активным бессрочно.
	 * @param bool $active
	 * @return $this
	 */
	public function whereActivePeriod(bool $active = true): self
	{
		$exp = new Expression(':now');

		if ($active) {
			$where = [
				'and',
				[
					'or',
					['<=', "{$this->_alias}.start_date", $exp],
					['IS', "{$this->_alias}.start_date", null]
				],
				[
					'or',
					['>=', "{$this->_alias}.end_date", $exp],
					['IS', "{$this->_alias}.end_date", null]
				]
			];
		} else {
			$where = ['or', ['>', "{$this->_alias}.start_date", $exp], ['<', "{$this->_alias}.end_date", $exp]];
		}

		return $this->andWhere($where, [':now' => DateHelper::lcDate()]);
	}
}
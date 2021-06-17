<?php
declare(strict_types = 1);

namespace app\models\abonents\active_query;

use pozitronik\core\models\LCQuery;

/**
 * Class AbonentsActiveQuery
 * @package app\models\abonents\active_query
 */
class AbonentsActiveQuery extends LCQuery
{
	private string $_alias;

	/**
	 * {@inheritdoc}
	 */
	public function init(): void
	{
		parent::init();

		[, $this->_alias] = $this->getTableNameAndAlias();
	}

	/**
	 * {@inheritdoc}
	 */
	public function alias($alias): AbonentsActiveQuery
	{
		$this->_alias = $alias;
		return parent::alias($alias);
	}

	/**
	 * @param string $phone
	 * @return AbonentsActiveQuery
	 */
	public function withPhone(string $phone): AbonentsActiveQuery
	{
		return $this->andWhere(["{$this->_alias}.phone" => $phone]);
	}
}
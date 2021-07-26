<?php
declare(strict_types = 1);

namespace app\components\db;

use app\models\sys\permissions\traits\ActiveQueryPermissionsTrait;
use pozitronik\traits\models\ActiveQuery as VendorActiveQuery;

/**
 * Trait ActiveQueryTrait
 * Каст расширения запросов
 */
class ActiveQuery extends VendorActiveQuery {
	use ActiveQueryPermissionsTrait;

	protected ?string $_alias = null;

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
	public function alias($alias): self
	{
		$this->_alias = $alias;
		return parent::alias($alias);
	}
}
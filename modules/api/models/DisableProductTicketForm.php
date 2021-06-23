<?php
declare(strict_types = 1);

namespace app\modules\api\models;

/**
 * Class DisableProductTicketForm
 * @package app\modules\api\models
 */
class DisableProductTicketForm extends ProductTicketForm
{
	public function validateProductActivity(): void
	{
		if ($this->product->actualStatus->isDisabled) {
			$this->addError('productId', 'Отключение продукта невозможно.');
		}
	}
}
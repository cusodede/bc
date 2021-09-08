<?php
declare(strict_types = 1);

namespace app\modules\api\models;

/**
 * Class DisableSubscriptionTicketForm
 * @package app\modules\api\models
 */
class DisableSubscriptionTicketForm extends ProductTicketForm
{
	/**
	 * {@inheritDoc}
	 */
	public function validateProductActivity(): void
	{
		if ((null === $this->product) || (null === $this->product->actualStatus) || $this->product->actualStatus->isDisabled) {
			$this->addError('productId', 'Отключение продукта невозможно.');
		}
	}
}
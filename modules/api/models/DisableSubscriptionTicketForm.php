<?php
declare(strict_types = 1);

namespace app\modules\api\models;

/**
 * Class DisableSubscriptionTicketForm
 * @package app\modules\api\models
 */
class DisableSubscriptionTicketForm extends SubscriptionTicketForm
{
	/**
	 * {@inheritDoc}
	 */
	public function validateProductJournalStatus(): void
	{
		if ($this->product->actualStatus?->isDisabled) {
			$this->addError('productId', 'Отключение продукта невозможно.');
		}
	}
}
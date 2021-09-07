<?php
declare(strict_types = 1);

namespace app\modules\api\models;

/**
 * Class ConnectSubscriptionTicketForm
 * @package app\modules\api\models
 */
class ConnectSubscriptionTicketForm extends ProductTicketForm
{
	/**
	 * {@inheritDoc}
	 */
	public function validateProductActivity(): void
	{
		if ((null !== $this->product) && (null !== $this->product->actualStatus) && (false === $this->product->actualStatus->isDisabled)) {
			$this->addError('productId', 'Подключение услуги уже было произведено.');
		}
	}
}
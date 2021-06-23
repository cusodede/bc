<?php
declare(strict_types = 1);

namespace app\modules\api\models;

/**
 * Class EnableProductTicketForm
 * @package app\modules\api\models
 */
class EnableProductTicketForm extends ProductTicketForm
{
	public function validateProductActivity(): void
	{
		if (!$this->product->actualStatus->isDisabled) {
			$this->addError('productId', 'Подключение услуги уже было произведено.');
		}
	}
}
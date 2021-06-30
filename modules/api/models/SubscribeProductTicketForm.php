<?php
declare(strict_types = 1);

namespace app\modules\api\models;

/**
 * Class SubscribeProductTicketForm
 * @package app\modules\api\models
 */
class SubscribeProductTicketForm extends ProductTicketForm {
	public function validateProductActivity():void {
		if (!$this->product->actualStatus->isDisabled) {
			$this->addError('productId', 'Подключение услуги уже было произведено.');
		}
	}
}
<?php
declare(strict_types = 1);

namespace app\modules\api\models;

use app\models\abonents\Abonents;

/**
 * Class ConnectSubscriptionTicketForm
 * @package app\modules\api\models
 */
class ConnectSubscriptionTicketForm extends SubscriptionTicketForm
{
	/**
	 * {@inheritDoc}
	 */
	public function rules(): array
	{
		$rules = parent::rules();
		//При подключении подписки информация об абоненте может отсутствовать, поэтому проверку на наличие в БД игнорируем.
		unset($rules['abonentPresence']);

		return $rules;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validateProductJournalStatus(): void
	{
		if ($this->product->actualStatus?->isActive) {
			$this->addError('productId', 'Подключение услуги уже было произведено.');
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate($attributeNames = null, $clearErrors = true): bool
	{
		$statusIsOk = parent::validate($attributeNames, $clearErrors);
		if ($statusIsOk && null === $this->abonent) {
			$abonent = new Abonents(['phone' => $this->phone]);
			$abonent->save();
		}

		return $statusIsOk;
	}
}
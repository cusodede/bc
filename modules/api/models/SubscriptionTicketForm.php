<?php
declare(strict_types = 1);

namespace app\modules\api\models;

use app\models\phones\PhoneNumberValidator;
use app\models\products\Products;
use app\models\abonents\Abonents;
use app\models\ticket\TicketSubscription;
use Exception;
use yii\base\Model;

/**
 * @property Products|null $product
 * @property Abonents|null $abonent
 */
abstract class SubscriptionTicketForm extends Model
{
	/**
	 * @var int|null идентификатор продукта.
	 */
	public ?int $productId = null;
	/**
	 * @var string|null телефон абонента.
	 */
	public ?string $phone = null;
	/**
	 * @var Products|null
	 */
	private ?Products $_product = null;
	/**
	 * @var Abonents|null
	 */
	private ?Abonents $_abonent = null;

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['productId', 'phone'], 'required'],
			[['phone'], PhoneNumberValidator::class],
			'abonentPresence' => [
				['phone'], function () {
					if (null === $this->abonent) {
						$this->addError('phone', 'Некорректный номер');
					}
				}
			],
			[['productId'], 'integer'],
			[['productId'],
				'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['productId' => 'id']
			],
			[['productId'], function () {
				$subscriptionModel = $this->findLatterSubscriptionTicket();
				if (null !== $subscriptionModel && !$subscriptionModel->isCompleted) {
					$this->addError('productId', 'В данный момент нет возможности для обработки запроса.');
				}
			},
				'skipOnError' => true, 'when' => static function (self $model) {
					return !$model->hasErrors('phone');
				}
			],
			[['productId'], 'validateProductJournalStatus',
				'skipOnError' => true, 'when' => static function (self $model) {
					return !$model->hasErrors('phone');
				}
			]
		];
	}

	/**
	 * Проверка на доступность управления услугой.
	 */
	abstract public function validateProductJournalStatus(): void;

	/**
	 * @return Abonents|null
	 */
	public function getAbonent(): ?Abonents
	{
		return $this->_abonent ??= Abonents::findByPhone($this->phone);
	}

	/**
	 * @return Products
	 * @throws Exception
	 */
	public function getProduct(): Products
	{
		return $this->_product ??= ($this->abonent?->findExistentProductById($this->productId) ?? Products::findOne($this->productId));
	}

	/**
	 * @return TicketSubscription|null
	 * @noinspection PhpIncompatibleReturnTypeInspection сигнатура метода one() в данном случае обязывает к заглушке инспекции.
	 */
	public function findLatterSubscriptionTicket(): ?TicketSubscription
	{
		if (null === $this->abonent) {
			return null;
		}

		return TicketSubscription::find()
			->joinWith(['relatedAbonentsToProducts rel', 'relatedTicket rel_ticket'])
			->andWhere(['rel.product_id' => $this->productId])
			->andWhere(['rel.abonent_id' => $this->abonent->id])
			->orderBy(['rel_ticket.created_at' => SORT_DESC])
			->limit(1)
			->one();
	}
}
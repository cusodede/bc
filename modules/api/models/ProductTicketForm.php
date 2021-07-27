<?php
declare(strict_types = 1);

namespace app\modules\api\models;

use app\models\phones\PhoneNumberValidator;
use app\models\products\Products;
use app\models\abonents\Abonents;
use Exception;
use yii\base\Model;

/**
 * Class ProductTicketRequestForm
 * @package app\components\tickets
 *
 * @property Products|null $product
 * @property Abonents|null $abonent
 * @property int|null $productId
 * @property string|null $phone
 */
abstract class ProductTicketForm extends Model
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
			[['productId'], 'integer'],
			[['productId'],
				'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['productId' => 'id']
			],
			[['phone'], PhoneNumberValidator::class],
			[['phone'],
				'exist', 'skipOnError' => true, 'targetClass' => Abonents::class, 'targetAttribute' => ['phone' => 'phone']
			],
			[['productId'], 'validateProductActivity', 'skipOnError' => true]
		];
	}

	abstract public function validateProductActivity(): void;

	/**
	 * @return Abonents|null
	 */
	public function getAbonent(): ?Abonents
	{
		return $this->_abonent ?? $this->_abonent = Abonents::findByPhone($this->phone);
	}

	/**
	 * @return Products|null
	 * @throws Exception
	 */
	public function getProduct(): ?Products
	{
		if ((null === $this->_product) && null !== $this->abonent) {
			$this->_product = $this->abonent->findExistentProductById($this->productId);
		}

		return $this->_product;
	}
}
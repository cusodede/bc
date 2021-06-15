<?php
declare(strict_types = 1);

namespace app\models\sales\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\core\prototypes\RelationValidator;
use app\models\product\Product;
use app\models\sys\users\Users;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sales".
 *
 * @property int $id
 * @property int $product Товар
 * @property int $seller Продавец
 * @property string $create_date Дата регистрации
 * @property int $status Статус
 * @property int $deleted
 *
 * @property Product $relatedProduct Связанный проданный продукт
 * @property Users $relatedSeller Связанный продавец
 */
class Sales extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sales';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['product', 'seller'], 'required'],
			[['product', 'seller', 'status', 'deleted'], 'integer'],
			[['create_date'], 'safe'],
			[['product'], 'unique'],
			[['relatedProduct', 'relatedSeller'], RelationValidator::class],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'product' => 'Товар',
			'seller' => 'Продавец',
			'create_date' => 'Дата регистрации',
			'status' => 'Статус',
			'deleted' => 'Deleted',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct():ActiveQuery {
		return $this->hasOne(Product::class, ['id' => 'product']);
	}

	/**
	 * @param mixed $product
	 * Универсальный сеттер: в $product может придти как модель, так и её ключ (строкой или цифрой).
	 */
	public function setRelatedProduct($product):void {
		if (null === $product = self::ensureModel(Product::class, $product)) {
			throw new InvalidArgumentException("Невозможно обнаружить соответствующую модель");
		}
		$this->link('product', $product);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedSeller():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'seller']);
	}

	/**
	 * @param mixed $seller
	 */
	public function setRelatedSeller($seller):void {
		if (null === $seller
				= self::ensureModel(Users::class, $seller)) {
			throw new InvalidArgumentException("Невозможно обнаружить соответствующую модель");
		}
		$this->link('seller', $seller);
	}
}

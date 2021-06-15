<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use app\models\product\Product;
use app\models\product\ProductInterface;
use app\models\reward\Rewards;
use pozitronik\references\models\CustomisableReference;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class RefRewardsRules
 * Справочник правил расчета вознаграждения
 */
class RefRewardsRules extends CustomisableReference {

	public $menuCaption = "Справочник правил расчета вознаграждения";
	public $moduleId = "Вознаграждения";

	/*Прототипирую правила*/
	private const RULES = [
		/*product_id => [[rule1],[rule2]] */
		1 => [
			[
				'reason' => Rewards::REASON_SALE_REGISTERED,
				'status' => Rewards::STATUS_APPLY,
				'quantity' => 50,
				'waiting' => null
			],
			[
				'reason' => Rewards::REASON_SALE_CONFIRMED,
				'status' => Rewards::STATUS_WAIT,
				'quantity' => 50,
				'waiting' => Product::EVENT_CONFIRM
			],
		]
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_rewards_rules';
	}

	public static function findRules(ProductInterface $product):array {
		return new Model(ArrayHelper::getValue(self::RULES, $product->id));
	}

}
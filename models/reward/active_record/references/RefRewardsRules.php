<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use app\models\products\ProductsInterface;
use app\models\reward\Rewards;
use Exception;
use pozitronik\references\models\CustomisableReference;
use yii\base\DynamicModel;
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
		1 => [//simcard
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
				'waiting' => ProductsInterface::EVENT_CONFIRM
			],
		]
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_rewards_rules';
	}

	/**
	 * Пока вместо нормального генератора правил используем такую заглушку
	 * @param ProductsInterface $product
	 * @return DynamicModel[]
	 * @throws Exception
	 */
	public static function findRules(ProductsInterface $product):array {
		$rules = ArrayHelper::getValue(self::RULES, $product->type);
		$result = [];
		foreach ($rules as $rule) {
			$result[] = new DynamicModel($rule);
		}
		return $result;
	}

}
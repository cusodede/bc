<?php
declare(strict_types = 1);

namespace app\models\sales;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\product\ProductInterface;
use app\models\reward\active_record\references\RefRewardsRules;
use app\models\reward\Rewards;
use app\models\sales\active_record\Sales as SalesAR;
use app\models\sys\users\Users;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;

/**
 * Class Sales prototype
 *
 * @property-read Rewards[] $rewards Вознаграждения, начисляемые за продажу товара
 */
class Sales extends SalesAR {
	use ActiveRecordTrait;

	public const STATUS_REGISTERED = 0;

	/**
	 * @param ProductInterface $product
	 * @param Users|null $user
	 * @return static
	 * @throws Exception
	 * @throws ForbiddenHttpException
	 */
	public static function register(ProductInterface $product, ?Users $user = null):self {
		$user = $user??Users::Current();
		$sale = new self([
			'relatedProduct' => $product,
			'relatedSeller' => $user,
			'status' => self::STATUS_REGISTERED
		]);
		if ($sale->save()) return $sale;
		/*todo: log errors*/
		throw new Exception("Cannot register sale");
	}

	/**
	 * @return Rewards[]
	 */
	public function getRewards():array {
		$rules = RefRewardsRules::findRules($this->relatedProduct);
		$rewards = [];
		foreach ($rules as $rule) {
			$rewards = new Rewards([
				'relatedProduct' => $this->relatedProduct,//товар, за который начисляется бонус
				'relatedRule' => $rule,
				'operation' => Rewards::OPERATION_SELL,//инициирующая операция
				'reason' => $rule->rewardReason,//причина начисления
				'status' => $rule->rewardStatus,//статус
				'quantity' => $rule->quantity,
				'waiting' => $rule->waiting
			]);
		}
		return $rewards;

	}
}
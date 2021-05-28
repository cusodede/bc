<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefRewardsRules
 * Справочник правил расчета вознаграждения
 */
class RefRewardsRules extends CustomisableReference {

	public $menuCaption = "Справочник правил расчета вознаграждения";

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_rewards_rules';
	}
}
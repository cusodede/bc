<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefRewardRules
 * Справочник правил расчета вознаграждения
 */
class RefRewardRules extends CustomisableReference {

	public $menuCaption = "Справочник правил расчета вознаграждения";

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_reward_rule';
	}
}
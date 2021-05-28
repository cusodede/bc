<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefRewardOperations
 * Справочник операций
 */
class RefRewardOperations extends CustomisableReference {

	public $menuCaption = "Справочник операций";
	public $moduleId = "Вознаграждения";

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_reward_operation';
	}
}
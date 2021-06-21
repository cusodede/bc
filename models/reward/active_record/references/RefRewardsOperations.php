<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefRewardsOperations
 * Справочник операций
 */
class RefRewardsOperations extends CustomisableReference {

	public string $menuCaption = "Справочник операций";
	public $moduleId = "Вознаграждения";

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_rewards_operations';
	}
}
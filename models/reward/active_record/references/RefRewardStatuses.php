<?php
declare(strict_types = 1);

namespace app\models\reward\active_record\references;

use pozitronik\references\models\CustomisableReference;

/**
 * Class RefRewardStatuses
 * Справочник статусов
 */
class RefRewardStatuses extends CustomisableReference {

	public $menuCaption = "Справочник статусов";

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_reward_status';
	}
}
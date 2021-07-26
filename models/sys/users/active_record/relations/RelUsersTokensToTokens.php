<?php
declare(strict_types = 1);

namespace app\models\sys\users\active_record\relations;

use app\models\sys\users\active_record\UsersTokens;
use pozitronik\relations\traits\RelationsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class RelUsersTokensToTokens
 * @package app\models\sys\users\active_record\relations
 *
 * @property-read UsersTokens|null $relatedParentToken
 * @property-read UsersTokens|null $relatedChildToken
 */
class RelUsersTokensToTokens extends ActiveRecord {
	use RelationsTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_relation_users_tokens_to_tokens';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['parent_id', 'child_id'], 'required'],
			[['parent_id', 'child_id'], 'integer'],
			[['parent_id', 'child_id'], 'unique', 'targetAttribute' => ['parent_id', 'child_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'parent_id' => 'Parent ID',
			'child_id' => 'Child ID',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedParentToken():ActiveQuery {
		return $this->hasOne(UsersTokens::class, ['id' => 'parent_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedChildToken():ActiveQuery {
		return $this->hasOne(UsersTokens::class, ['id' => 'child_id']);
	}
}
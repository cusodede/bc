<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\permissions\PermissionsCollections;
use app\models\sys\permissions\PermissionsCollectionsSearch;
use yii\helpers\ArrayHelper;

/**
 * Class PermissionsCollectionsController
 */
class PermissionsCollectionsController extends DefaultController {

	public string $modelClass = PermissionsCollections::class;
	public string $modelSearchClass = PermissionsCollectionsSearch::class;
	public bool $enablePrototypeMenu = false;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => PermissionFilter::class
			]
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/permissions-collections';
	}

}
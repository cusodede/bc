<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\sys\permissions\PermissionsCollections;
use app\models\sys\permissions\PermissionsCollectionsSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class PermissionsCollectionsController
 */
class PermissionsCollectionsController extends DefaultController {
	use ControllerTrait;

	public string $modelClass = PermissionsCollections::class;
	public string $modelSearchClass = PermissionsCollectionsSearch::class;

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/permissions-collections';
	}

}
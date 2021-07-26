<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\billing_journal\BillingJournal;
use app\models\billing_journal\BillingJournalSearch;

/**
 * Class BillingJournalController
 * @package app\controllers
 */
class BillingJournalController extends DefaultController
{
	/**
	 * {@inheritdoc}
	 */
	public string $modelClass = BillingJournal::class;
	/**
	 * {@inheritdoc}
	 */
	public string $modelSearchClass = BillingJournalSearch::class;
	/**
	 * {@inheritdoc}
	 */
	public bool $enablePrototypeMenu = false;

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath(): string
	{
		return '@app/views/billing-journal';
	}
}
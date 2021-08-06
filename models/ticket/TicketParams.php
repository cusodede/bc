<?php
declare(strict_types = 1);

namespace app\models\ticket;

use yii\base\BaseObject;

/**
 * Class TicketParams
 * @package app\models\ticket
 */
class TicketParams extends BaseObject
{
	public ?string $id = null;
	public ?int $type = null;
	public ?int $createdBy = null;
}
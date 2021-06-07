<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use yii\db\ActiveRecordInterface;

/**
 * Interface ActiveStorageInterface
 *
 * @property int $id
 * @property string $for
 * @property string $header
 * @property string $content
 * @property string $placement
 * @property int|null $daddy
 * @property string $create_date
 */
interface ActiveStorageInterface extends ActiveRecordInterface {

}
<?php
declare(strict_types = 1);
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 *
 * @var $className string the new migration class name without namespace
 * @var $namespace string the new migration class namespace
 * @var $table string the name table
 * @var $fields array the fields
 * @var $foreignKeys array the foreign keys
 */

echo "<?php\ndeclare(strict_types = 1);\n";
if (!empty($namespace)) {
	echo "\nnamespace {$namespace};\n";
}
?>

use app\components\db\Migration;

/**
 * Handles dropping columns from table `<?= $table ?>`.
<?= $this->render('_foreignTables', [
	'foreignKeys' => $foreignKeys,
]) ?>
 */
class <?= $className ?> extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
<?= $this->render('_dropColumns', [
	'table' => $table,
	'fields' => $fields,
	'foreignKeys' => $foreignKeys,
])
?>
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
<?= $this->render('_addColumns', [
	'table' => $table,
	'fields' => $fields,
	'foreignKeys' => $foreignKeys,
])
?>
	}
}

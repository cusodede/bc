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
 * @var $tableComment string the comment table
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
 * Handles the creation of table `<?= $table ?>`.
<?= $this->render('_foreignTables', [
	'foreignKeys' => $foreignKeys,
]) ?>
 */
class <?= $className ?> extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
<?= $this->render('_createTable', [
	'table' => $table,
	'fields' => $fields,
	'foreignKeys' => $foreignKeys,
])
?>
<?php if (!empty($tableComment)) {
	echo $this->render('_addComments', [
		'table' => $table,
		'tableComment' => $tableComment,
	]);
}
?>
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
<?= $this->render('_dropTable', [
	'table' => $table,
	'foreignKeys' => $foreignKeys,
])
?>
	}
}

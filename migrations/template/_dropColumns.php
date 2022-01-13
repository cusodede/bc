<?php
declare(strict_types = 1);
/**
 * @var $fields array the fields
 * @var $foreignKeys array the foreign keys
 * @var $table string the name table
 */

echo  $this->render('_dropForeignKeys', [
	'table' => $table,
	'foreignKeys' => $foreignKeys,
]);

foreach ($fields as $field): ?>
		$this->dropColumn('<?= $table ?>', '<?= $field['property'] ?>');
<?php endforeach;

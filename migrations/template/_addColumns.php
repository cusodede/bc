<?php
declare(strict_types = 1);
/**
 * @var $fields array the fields
 * @var $foreignKeys array the foreign keys
 * @var $table string the name table
 */

foreach ($fields as $field): ?>
		$this->addColumn('<?=
			$table
		?>', '<?=
			$field['property']
		?>', $this-><?=
			$field['decorators']
		?>);
<?php endforeach;

echo $this->render('_addForeignKeys', [
	'table' => $table,
	'foreignKeys' => $foreignKeys,
]);

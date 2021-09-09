<?php
declare(strict_types = 1);

/**
 * @var $foreignKeys array the foreign keys
 * @var $table string the name table
 */

foreach ($foreignKeys as $column => $fkData): ?>

		// creates index for column `<?= $column ?>`
		$this->createIndex(
			'<?= $fkData['idx']  ?>',
			'<?= $table ?>',
			'<?= $column ?>'
		);

		// add foreign key for table `<?= $fkData['relatedTable'] ?>`
		$this->addForeignKey(
			'<?= $fkData['fk'] ?>',
			'<?= $table ?>',
			'<?= $column ?>',
			'<?= $fkData['relatedTable'] ?>',
			'<?= $fkData['relatedColumn'] ?>',
			'CASCADE'
		);
<?php endforeach;

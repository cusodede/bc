<?php
declare(strict_types = 1);

/**
 * @var $foreignKeys array the foreign keys
 * @var $table string the name table
 */

foreach ($foreignKeys as $column => $fkData): ?>
		// drops foreign key for table `<?= $fkData['relatedTable'] ?>`
		$this->dropForeignKey(
			'<?= $fkData['fk'] ?>',
			'<?= $table ?>'
		);

		// drops index for column `<?= $column ?>`
		$this->dropIndex(
			'<?= $fkData['idx'] ?>',
			'<?= $table ?>'
		);

<?php endforeach;

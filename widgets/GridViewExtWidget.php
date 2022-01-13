<?php
declare(strict_types = 1);

namespace app\widgets;

use kartik\grid\GridView;
use Yii;
use yii\base\InvalidConfigException;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Расширенный GridView может строить, например, мульти заголовки
 * @property array|Column[] $columns До инициализации колонки - массив конфигураций, после инициализации - массив объектов.
 * @property array $extHeaderColumn
 * @package app\widgets
 */
class GridViewExtWidget extends GridView {
	public const TABLE_BORDER_TOP = 'border-top: 1px solid #e9e9e9 !important;';

	public array $extHeaderColumn = [];

	/**
	 * @inheritDoc
	 */
	public function renderTableHeader():string {
		return $this->renderTablePart('thead', $this->tableHeader());
	}

	/**
	 * Рендер мульти шапки таблицы
	 * @return string
	 */
	public function tableExtHeader():string {
		$content = '';
		foreach ($this->extHeaderColumn as $columns) {
			$cells = [];
			foreach ($columns as $item) {
				$cells[] = $item->renderHeaderCell();
			}
			$content .= Html::tag('tr', implode('', $cells), $this->headerRowOptions);
		}

		return $content;
	}

	/**
	 * Обработка шапки таблицы
	 * @return string
	 */
	public function tableHeader():string {
		$cells = [];
		foreach ($this->columns as $column) {
			$cells[] = $column->renderHeaderCell();
		}
		$content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
		if (self::FILTER_POS_HEADER === $this->filterPosition) {
			$content = $this->renderFilters().$content;
		}
		if (self::FILTER_POS_BODY === $this->filterPosition) {
			$content .= $this->renderFilters();
		}

		return "<thead>\n".$this->tableExtHeader().$content."\n</thead>";
	}

	/**
	 * @inheritDoc
	 */
	protected function initColumns():void {
		if ([] === $this->columns) {
			$this->guessColumns();
		}
		foreach ($this->columns as $i => $column) {
			if (is_string($column)) {
				$column = $this->createDataColumn($column);
			} else {
				$column = Yii::createObject(array_merge([
					'class' => $this->dataColumnClass?:DataColumn::class,
					'grid' => $this,
				], $column));
			}
			if (!$column->visible) {
				unset($this->columns[$i]);
				continue;
			}
			$this->columns[$i] = $column;
		}
		$this->initExtHeaderColumn();
	}

	/**
	 * Инициализация расширенных колонок шапки таблицы
	 * @return void
	 * @throws InvalidConfigException
	 */
	private function initExtHeaderColumn():void {
		foreach ($this->extHeaderColumn as $headerKey => $item) {
			foreach ($item as $i => $column) {
				if (is_string($column)) {
					$column = $this->createDataColumn($column);
				} else {
					$column = Yii::createObject(array_merge([
						'class' => $this->dataColumnClass?:DataColumn::class,
						'grid' => $this,
					], $column));
				}
				if (!$column->visible) {
					unset($this->extHeaderColumn[$i]);
					continue;
				}
				$this->extHeaderColumn[$headerKey][$i] = $column;
			}
		}
	}
}

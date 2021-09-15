<?php
declare(strict_types = 1);

namespace models\phones;

use app\models\phones\Phones;
use Codeception\Test\Unit;

/**
 * Class PhonesTest
 * @package models\phones
 */
class PhonesTest extends Unit {

	// tests
	public function testNationalFormat():void {
		$this->assertEquals("9061601001", Phones::nationalFormat("+79061601001"));
		$this->assertEquals("9061601001", Phones::nationalFormat("89061601001"));
	}
}
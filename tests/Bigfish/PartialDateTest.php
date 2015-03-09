<?php

use Bigfish\PartialDate;

class PartialDateTest extends PHPUnit_Framework_TestCase {

	private $ausToSql = array(
		'2015' => '2015-00-00',
		'12/2015' => '2015-12-00',
		'1/12/2015' => '2015-12-01',
		'1/12/15' => '2015-12-01',
		'12/15' => '2015-12-00',
	);

	private $sqlToAus = array(
		'2015-00-00' => '2015',
		'2015-12-00' => '12/2015',
		'2015-12-01' => '01/12/2015',
	);

	private $ausToAus = array(
		'2015' => '2015',
		'12/2015' => '12/2015',
		'1/12/2015' => '01/12/2015',
		'1/12/15' => '01/12/2015',
		'12/15' => '12/2015',
	);

	private $sqlToSql = array(
		'2015-00-00' => '2015-00-00',
		'2015-12-00' => '2015-12-00',
		'2015-12-01' => '2015-12-01',
	);

	public function testSetDate() {
		$date = new PartialDate();
		$date->setDate('2014', '4');
		
		$result = $date->toSQLFormat();
		$this->assertEquals('2014-04-00', $result);

		$result = $date->toAusFormat();
		$this->assertEquals('04/2014', $result);
	}

	public function testAusToSql() {
		foreach ($this->ausToSql as $aus => $sql) {
			$date = new PartialDate($aus);
			$result = $date->toSQLFormat();
			$this->assertEquals($sql, $result);
		}
	}

	public function testSqlToAus() {
		foreach ($this->sqlToAus as $sql => $aus) {
			$date = new PartialDate($sql);
			$result = $date->toAusFormat();
			$this->assertEquals($aus, $result);
		}
	}

	public function testSqlToSql() {
		foreach ($this->sqlToSql as $sql => $sql2) {
			$date = new PartialDate($sql);
			$result = $date->toSQLFormat();
			$this->assertEquals($sql2, $result);
		}
	}

	public function testAusToAus() {
		foreach ($this->ausToAus as $aus => $aus2) {
			$date = new PartialDate($aus);
			$result = $date->toAusFormat();
			$this->assertEquals($aus2, $result);
		}
	}

}
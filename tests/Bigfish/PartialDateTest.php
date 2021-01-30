<?php

use Bigfish\PartialDate;
use PHPUnit\Framework\TestCase;

class PartialDateTest extends TestCase
{
	private $ausToSql = [
		'2015' => '2015-00-00',
		'12/2015' => '2015-12-00',
		'1/12/2015' => '2015-12-01',
		'1/12/15' => '2015-12-01',
		'12/15' => '2015-12-00',
	];

	private $sqlToAus = [
		'2015-00-00' => '2015',
		'2015-12-00' => '12/2015',
		'2015-12-01' => '01/12/2015',
	];

	private $ausToAus = [
		'2015' => '2015',
		'12/2015' => '12/2015',
		'1/12/2015' => '01/12/2015',
		'1/12/15' => '01/12/2015',
		'12/15' => '12/2015',
	];

	private $sqlToSql = [
		'2015-00-00' => '2015-00-00',
		'2015-12-00' => '2015-12-00',
		'2015-12-01' => '2015-12-01',
	];

	public function testSetDate()
	{
		$date = new PartialDate();
		$date->setDate('2014', '4');

		$result = $date->toSQLFormat();
		$this->assertEquals('2014-04-00', $result);

		$result = $date->toAusFormat();
		$this->assertEquals('04/2014', $result);
	}

	public function testAusToSql()
	{
		foreach ($this->ausToSql as $aus => $sql) {
			$date = new PartialDate($aus);
			$result = $date->toSQLFormat();
			$this->assertEquals($sql, $result);
		}
	}

	public function testSqlToAus()
	{
		foreach ($this->sqlToAus as $sql => $aus) {
			$date = new PartialDate($sql);
			$result = $date->toAusFormat();
			$this->assertEquals($aus, $result);
		}
	}

	public function testSqlToSql()
	{
		foreach ($this->sqlToSql as $sql => $sql2) {
			$date = new PartialDate($sql);
			$result = $date->toSQLFormat();
			$this->assertEquals($sql2, $result);
		}
	}

	public function testAusToAus()
	{
		foreach ($this->ausToAus as $aus => $aus2) {
			$date = new PartialDate($aus);
			$result = $date->toAusFormat();
			$this->assertEquals($aus2, $result);
		}
	}

	public function testGetters()
	{
		$date = new PartialDate();
		$date->setDate('2020', '12', '04');

		$this->assertEquals(2020, $date->getYear());
		$this->assertEquals(12, $date->getMonth());
		$this->assertEquals(4, $date->getDay());
		
		$date->setDate(2021);

		$this->assertEquals(2021, $date->getYear());
		$this->assertNull($date->getMonth());
		$this->assertNull($date->getDay());
	}

	public function testParseInvalid()
	{
		$date = new PartialDate('not a date');
		$this->assertTrue($date->isEmpty());
	}

	public function testJsonSerialize()
	{
		foreach ($this->ausToAus as $aus => $aus2) {
			$date = new PartialDate($aus);
			$result = $date->jsonSerialize();
			$this->assertEquals($aus2, $result);
		}
	}

	public function testToString()
	{
		foreach ($this->ausToAus as $aus => $aus2) {
			$date = new PartialDate($aus);
			$result = (string) $date;
			$this->assertEquals($aus2, $result);
		}
	}

	public function testIsEmpty()
	{
		$date = new PartialDate();
		$this->assertTrue($date->isEmpty());
	
		$date->setDate('2003');
		$this->assertFalse($date->isEmpty());
	}

	public function testNullIfEmpty()
	{
		$date = new PartialDate();
		$this->assertNull($date->toAusFormat());
		$this->assertNull($date->toSQLFormat());
		$this->assertNull($date->jsonSerialize());
		$this->assertEquals('', (string) $date);
	}
}

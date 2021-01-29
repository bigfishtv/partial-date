<?php
/**
 * @copyright 2021, Bigfish.tv
 * @license http://opensource.org/licenses/bsd-license.php
 */

namespace Bigfish;

/**
 * Simple date parser and formatter for partial dates (day/month optional)
 *
 * @package Bigfish
 */

class PartialDate implements \JsonSerializable
{
	protected $year;
	protected $month;
	protected $day;

	/**
	 * Initialise a new partial date object.
	 * 
	 * @param string $dateString  A date string in a format PartialDate::parse() understands.
	 */
	public function __construct($dateString = null)
	{
		$this->parse($dateString);
	}

	/**
	 * Set a date manually into partial date object. This will clear all previous values.
	 * 
	 * @param  string $year  The year in 2 or 4 digits
	 * @param  string|null $month The month 1 or 2 digits (optional)
	 * @param  string|null $day   The day in 1 or 2 digits (optional)
	 * @return PartialDate        self
	 */
	public function setDate($year, $month = null, $day = null)
	{
		$this->year = strlen($year) < 4 ? substr(date('Y') - ($year - date('y') > 15 ? 100 : 0), 0, 2) . $year : $year;
		$this->month = $month;
		$this->day = $day;
		return $this;
	}

	/**
	 * Parse a date string into partial date object.
	 * 
	 * Currently accepts YYYY-MM-DD, DD/MM/YYYY, MM/YYYY, YYYY.
	 * 
	 * @param  string $dateString The date string to be parsed
	 * @return PartialDate             self
	 */
	public function parse($dateString)
	{
		$dateString = trim($dateString);

		// matches SQL format YYYY-MM-DD
		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateString, $matches)) {
			$this->setDate($matches[1], $matches[2], $matches[3]);
			return $this;
		}

		// matches Aus Date format YYYY
		if (preg_match('/^\d{4}$/', $dateString, $matches)) {
			$this->setDate($dateString);
			return $this;
		}

		// matches Aus Date format MM/YY and MM/YYYY
		if (preg_match('/^(\d{1,2})\/(\d\d(\d\d)?)$/', $dateString, $matches)) {
			$year = $matches[2];
			$month = $matches[1];
			$this->setDate($year, $month);
			return $this;
		}

		// matches Aus Date format DD/MM/YYYY
		if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d\d(\d\d)?)$/', $dateString, $matches)) {
			$year = $matches[3];
			$month = $matches[2];
			$day = $matches[1];
			$this->setDate($year, $month, $day);
			return $this;
		}

		return $this;
	}

	/**
	 * Format a partial date into Australian date format. DD/MM/YYYY, MM/YYYY, YYYY.
	 * 
	 * @return string|null The date in Aus date format
	 */
	public function toAusFormat()
	{
		if ($this->isEmpty()) return null;
		$date = sprintf('%s/%s/%s', str_pad($this->day, 2, '0', STR_PAD_LEFT), str_pad($this->month, 2, '0', STR_PAD_LEFT), $this->year);
		$date = str_replace('00/', '', $date);
		return $date;
	}

	/**
	 * Is date empty?
	 *
	 * @return boolean
	 */
	public function isEmpty()
	{
		return !$this->day && !$this->month && !$this->year;
	}

	/**
	 * Format a partial date into SQL Format. YYYY-MM-DD.
	 * 
	 * @return string|null The date in SQL date format
	 */
	public function toSQLFormat()
	{
		if ($this->isEmpty()) return null;
		$date = sprintf('%s-%s-%s', $this->year, str_pad($this->month, 2, '0', STR_PAD_LEFT), str_pad($this->day, 2, '0', STR_PAD_LEFT));
		return $date;
	}

	/**
	 * @inheritDoc
	 *
	 * @return string|null
	 */
	public function jsonSerialize()
	{
		if ($this->isEmpty()) return null;
		return (string) $this;
	}

	/**
	 * Returns the date as Australian formatted string
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->toAusFormat() ?: '';
	}
}

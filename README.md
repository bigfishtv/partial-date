[![Build Status](https://travis-ci.org/bigfishtv/partial-date.svg?branch=master)](https://travis-ci.org/bigfishtv/partial-date)

# Partial Date

A simple PHP date parser and formatter for dealing with partial dates. 
This is handy when you want to store a year or year/month in MySQL date format without
specifying a month or day.

```php
<?php

use Bigfish\PartialDate;

// from Australian date format to SQL
$date = new PartialDate('2/2015');
echo $date->toSQLFormat(); // outputs 2015-02-00

// from SQL date format to Australian
$date = new PartialDate('2015-02-00');
echo $date->toAusFormat(); // outputs 02/2015

// parse method
$data = new PartialDate();
$date->parse('12/12/14');

// setDate method
$data = new PartialDate();
$date->setDate(2015, 2, 3);

```

<?php
include 'vendor/autoload.php';

use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Match\Detail;

echo "Welcome to T-Regx playground! :) \n\n";

// Test your code here...

// Instantiate your pattern
$pattern = Pattern::of('(origin/)?master');

// match pattern against a subject
$match = $pattern->match('origin/master');

// get first match details
$detail = $match->first();

$group = $detail->group(1);

/**
 * Type "php EDIT_HERE.php" in the console on the right, to run.
 *
 * T-Regx version: 0.38.0
 */

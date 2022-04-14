<?php
include 'vendor/autoload.php';

use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;

echo "Welcome to T-Regx playground! :) \n\n";

// Test your code here...

// Instantiate your pattern
$pattern = Pattern::of('(origin/)?master');

// match pattern against a subject
$match = $pattern->match('origin/master');

// get first match details
$match->first(function (Detail $detail) {

});

/**
 * Type "php EDIT_HERE.php" in the console on the right, to run.
 *
 * T-Regx version: 0.23.0
 */

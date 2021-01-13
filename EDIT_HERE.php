<?php
include 'vendor/autoload.php';

use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;

echo "Welcome to T-Regx playground! :) \n\n";

// Test your code here...

pattern('(origin/)?master')->match('origin/master')->first(function (Detail $detail) {

});

/**
 * Type "php EDIT_HERE.php" in the console on the right, to run.
 * You can rename the file, as you please.
 */

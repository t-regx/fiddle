<?php
include 'vendor/autoload.php';

use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;

echo "Welcome to T-Regx playground! :) \n\n";

/**
 * Click "Run" at the top to run EXAMPLES.php
 */

function yourTestCode()
{
    // Test your code here...
}

# Run examples
yourTestCode();
exampleTest();
exampleAll();
exampleFirst();
exampleFindFirst();
exampleCount();
exampleAllGroup();
exampleCapturingGroups();
exampleOldSchoolPatterns();
exampleUserInput();

# Feel free to edit the example, if you like :)

function exampleTest()
{
    echo "# Test a subject against a pattern all:\n";

    if (pattern('\d{3,}')->test('year 2020')) {
        echo "Match!";
    } else {
        echo "No match :/";
    }
}

function exampleAll()
{
    echo "\n\n# Match all:\n";

    $subject = "I'll have two number 9s, a number 9 large, " .
        "a number 6 with extra dip, a number 7, two number " .
        "45s, one with cheese, and a large soda.";

    $orders = pattern("\d+(s)?")->match($subject)->all();
    var_dump($orders);
}

function exampleFirst()
{
    echo "\n# Find the first element:\n";

    echo pattern("(John|Brian)")
        ->match("My name is John")
        ->first(function (Detail $detail) {
            return "I found a name: $detail!";
        });
}

function exampleFindFirst()
{
    echo "\n\n# Find the first element, or fallback:\n";

    echo pattern("(John|Brian)")
        ->match("My name is Mark")
        ->findFirst(function (Detail $detail) {
            return "I found a name $detail!";
        })
        ->orElse(function (NotMatched $notMatched) {
            $subject = $notMatched->subject();
            return "Subject: '$subject' didn't contain the name :/";
        });
}

function exampleCount()
{
    echo "\n\n# Count occurrences:\n";

    $subject = "I'll have two number 9s, a number 9 large, " .
        "a number 6 with extra dip, a number 7, two number " .
        "45s, one with cheese, and a large soda.";

    $count = pattern('\d+')->count($subject);
    echo "The subject contains $count orders";
}

function exampleAllGroup()
{
    echo "\n\n# Match all:\n";

    $orders = pattern('(?<value>\d+)(?<unit>[cm]?m)?')
        ->match("14mm 18m 17 19m")
        ->group('unit')
        ->all();
    var_dump($orders);
}

function exampleCapturingGroups()
{
    echo "\n# Working with capturing groups:\n";

    pattern('(?<value>\d+)(?<unit>[cm]?m)?')
        ->match('12cm 14 13mm 19m 2m!')
        ->forEach(function (Detail $detail) {
            echo "Match: '$detail' (";
            if ($detail->matched('unit')) {
                echo "Unit: {$detail->group('unit')}";
            } else {
                echo "No unit";
            }
            $phrase = $detail->group('value')->isInt() ? 'is' : 'is not';
            echo ") - value $phrase an integer\n";
        });
}

function exampleOldSchoolPatterns()
{
    echo "\n# Example of simple and old-school patterns:\n";

    $simplePattern = Pattern::of('\d+/\d+[cm]?m');
    $oldSchoolPattern = Pattern::pcre()->of('/\d+\/\d+[cm]?m/');

    echo "Simple match:     " . matchFirst($simplePattern) . "\n";
    echo "Old-school match: " . matchFirst($oldSchoolPattern);
}

function matchFirst(Pattern $pattern): string
{
    return $pattern->match('A ruler is 14/2cm long')->first();
}

function exampleUserInput()
{
    echo "\n\n# Working with unsafe user input:\n";

    $etcPasswd = 'My /u/mark:.* /a/tom:riddle /a/jake:todd /u/robert:cooper';

    # Imitated user-input (potential ReDoS attack)
    $user = 'mark';
    $surname = '.*'; // user types wildcards

    # Examples with prepared patterns
    $pattern1 = Pattern::of("/[ua]/($user:$surname)");             # Adding strings
    $pattern2 = Pattern::inject("/[ua]/(@:@)", [$user, $surname]); # Prepared pattern

    # Run
    echo $pattern1->match($etcPasswd)->group(1)->first() . " - Attack successful\n";
    echo $pattern2->match($etcPasswd)->group(1)->first() . " - Correct\n";
}

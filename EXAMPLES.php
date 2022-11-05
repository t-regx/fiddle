<?php
include 'vendor/autoload.php';

use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;

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
exampleCount();
exampleCapturingGroups();
exampleOldSchoolPatterns();
exampleUserInput();
exampleUserInputTemplate();
exampleUserInputBuilder();
exampleUserInputMask();

# Feel free to edit the example, if you like :)

function exampleTest()
{
    echo "# Test a subject against a pattern all:\n";

    if (pattern('\d{3,}')->test('year 2020')) {
        echo 'Match!';
    } else {
        echo 'No match :/';
    }
}

function exampleAll()
{
    echo "\n\n# Match all:\n";

    $subject = "I'll have two number 9s, a number 9 large, " .
        "a number 6 with extra dip, a number 7, two number " .
        "45s, one with cheese, and a large soda.";

    $orders = pattern("\d+(s)?")->search($subject)->all();
    var_dump($orders);
}

function exampleFirst()
{
    echo "\n# Find the first element:\n";

    $pattern = pattern("(John|Brian)");
    $matcher = $pattern->match("My name is John");

    echo "I found a name: {$matcher->first()}!";
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

function exampleCapturingGroups()
{
    echo "\n# Working with capturing groups:\n";

    $pattern = pattern('(?<value>\d+)(?<unit>[cm]?m)?');
    $matcher = $pattern->match('12cm 14 13mm 19m 2m!');

    foreach ($matcher as $detail) {
        echo "Match: '$detail' (";
        if ($detail->matched('unit')) {
            echo 'Unit: ' . $detail->group('unit');
        } else {
            echo 'No unit';
        }
        $phrase = $detail->group('value')->isInt() ? 'is' : 'is not';
        echo ") - value $phrase an integer\n";
    }
}

function exampleOldSchoolPatterns()
{
    echo "\n# Example of simple and old-school patterns:\n";

    $simplePattern = Pattern::of('\d+/\d+[cm]?m');
    $oldSchoolPattern = PcrePattern::of('/\d+\/\d+[cm]?m/');

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
    echo $pattern1->match($etcPasswd)->first()->get(1) . " - Attack successful\n";
    echo $pattern2->match($etcPasswd)->first()->get(1) . " - Correct\n";
}

function exampleUserInputTemplate()
{
    echo "\n\n# Working with templates:\n";

    $template = Pattern::template('^:(@)?');
    $pattern = $template->literal('*/({');
    $matched = $pattern->test(':*/({');

    echo "Matched a templated pattern:\n";
    var_dump($matched);
}

function exampleUserInputBuilder()
{
    echo "\n\n# Working with templates (multiple placeholders):\n";

    $pattern = Pattern::builder('^@:(@)?')
        ->literal('*/({')
        ->pattern('foo:\w+')
        ->build();

    $matched = $pattern->test('*/({:foo:12');

    echo "Matched a templated pattern:\n";
    var_dump($matched);
}

function exampleUserInputMask()
{
    echo "\n\n# Working with mask:\n";

    $userInput = '%e.%f => "%s"'; // Edit this user input as you like

    $pattern = Pattern::mask($userInput, [
        '%e' => '\$[a-z]+',
        '%f' => '[A-Z]+',
        '%s' => '\s*',
    ]);

    $matched = $pattern->test('$abc.FCD => "  "');

    echo "Matched a masked pattern:\n";
    var_dump($matched);
}

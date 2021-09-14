<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;

class GroupFacadeMatched
{
    /** @var Subject */
    private $subject;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Signatures */
    private $signatures;

    public function __construct(Subject              $subject,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory      $allFactory,
                                GroupHandle          $groupHandle,
                                Signatures           $signatures)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
        $this->signatures = $signatures;
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup, Entry $entry): MatchedGroup
    {
        [$text, $offset] = $forGroup->getGroupTextAndOffset($this->groupHandle->groupHandle($group));
        $groupEntry = new GroupEntry($text, $offset, $this->subject);
        return $this->factoryStrategy->matched(
            $this->subject,
            new GroupDetails($this->signatures->signature($group), $group, $this->allFactory),
            $groupEntry,
            new SubstitutedGroup($entry, $groupEntry));
    }
}

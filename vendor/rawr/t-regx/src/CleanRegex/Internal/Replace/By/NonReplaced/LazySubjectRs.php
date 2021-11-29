<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

interface LazySubjectRs extends SubjectRs
{
    public function useExceptionMessage(NotMatchedMessage $message): void;
}

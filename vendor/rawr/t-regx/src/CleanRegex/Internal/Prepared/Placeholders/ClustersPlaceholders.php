<?php
namespace TRegx\CleanRegex\Internal\Prepared\Placeholders;

use TRegx\CleanRegex\Internal\Prepared\Cluster\CountedClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;

class ClustersPlaceholders implements Placeholders
{
    /** @var ExpectedClusters */
    private $clusters;

    public function __construct(CountedClusters $clusters)
    {
        $this->clusters = new ExpectedClusters($clusters);
    }

    public function consumer(): PlaceholderConsumer
    {
        return new FiguresPlaceholderConsumer($this->clusters);
    }

    public function meetExpectation(): void
    {
        $this->clusters->meetExpectation();
    }
}

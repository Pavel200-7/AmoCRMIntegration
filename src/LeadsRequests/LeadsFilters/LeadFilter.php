<?php

require_once __DIR__ . '/LeadsSumFilter.php';
class LeadFilter
{
    private LeadsSumFilter $leadsSumFilter;

    public function __construct()
    {
        $this->leadsSumFilter = new LeadsSumFilter();
    }

    public function SumMore(array $leads, int $sum): array
    {
        return $this->leadsSumFilter->getLeadsWithSumMore($leads, $sum);
    }

    public function SumEqual(array $leads, int $sum): array
    {
        return $this->leadsSumFilter->getLeadsWithSumEqual($leads, $sum);
    }

    public function SumLess(array $leads, int $sum): array
    {
        return $this->leadsSumFilter->getLeadsWithSumLess($leads, $sum);
    }



}
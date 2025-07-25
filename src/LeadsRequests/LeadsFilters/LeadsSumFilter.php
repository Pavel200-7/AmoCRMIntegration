<?php

class LeadsSumFilter
{
    public function getLeadsWithSumMore(array $leads, int $sum): array
    {
        return array_filter($leads, function ($lead) use ($sum) {
            return $lead['price'] > $sum;
        });
    }

    public function getLeadsWithSumEqual(array $leads, int $sum): array
    {
        return array_filter($leads, function ($lead) use ($sum) {
            return $lead['price'] == $sum;
        });
    }

    public function getLeadsWithSumLess(array $leads, int $sum): array
    {
        return array_filter($leads, function ($lead) use ($sum) {
            return $lead['price'] < $sum;
        });
    }

}
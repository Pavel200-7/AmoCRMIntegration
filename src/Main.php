<?php

require_once __DIR__ . '/LeadsRequests/LeadsGetter.php';
require_once __DIR__ . '/LeadsRequests/LeadsPatcher.php';
require_once __DIR__ . '/LeadsRequests/LeadsPoster.php';
require_once __DIR__ . '/LeadsRequests/LeadsFilters/LeadFilter.php';

class Main
{
    private LeadsGetter $leadsGetter;
    private LeadsPatcher $leadsPatcher;
    private LeadsPoster $leadsPoster;
    private LeadFilter $leadFilter;

    public function __construct()
    {
        try {
            $this->leadsGetter = new LeadsGetter();
            $this->leadsPatcher = new LeadsPatcher();
            $this->leadsPoster = new LeadsPoster();

            $this->leadFilter = new LeadFilter();
        } catch (Exception $ex) {
            var_dump($ex);
            file_put_contents("ERROR_LOG.txt", 'Ошибка: ' . $ex->getMessage() . PHP_EOL . 'Код ошибки:' . $ex->getCode());
        }


    }

    public function moveLeadFromBidToWaitingIfItsSumMore5000(): void
    {
        $sum = 5000;
        $leads = $this->leadsGetter->getLeadsOnBidInVaronca();
        $leads = $this->leadFilter->SumMore($leads, $sum);
        $this->leadsPatcher->moveLeadToWaiting($leads);
    }


}
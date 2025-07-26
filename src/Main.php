<?php

require_once __DIR__ . '/LeadsRequests/LeadsGetter.php';
require_once __DIR__ . '/LeadsRequests/LeadsPatcher.php';
require_once __DIR__ . '/LeadsRequests/LeadsPoster.php';
require_once __DIR__ . '/LeadsRequests/LeadsFilters/LeadFilter.php';


/*
 * Класс для хранения исходных функций
 */
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

    /*
     * Перетягивает сделки в воронке "Воронка" на этап "Ожидание клиента"
     * из этапа "Заявка", если у сделки бюджет > 5000,
     */
    public function moveLeadFromBidToWaitingIfItsSumMore5000(): void
    {
        $sum = 5000;
        $leads = $this->leadsGetter->getLeadsInVaroncaOnBid();
        $leads = $this->leadFilter->SumMore($leads, $sum);
        $this->leadsPatcher->moveLeadToWaiting($leads);
    }

    /*
     * Копирует сделки на этапе “Клиент подтвердил” при условии, что бюджет сделки = равен 4999
     */
    public function copyLeadFromInClientApproveIfSumEqual4999(): void
    {
        $sum = 4999;
        $leads = $this->leadsGetter->getLeadsInVaroncaOnClientApproveFull();
        $leads = $this->leadFilter->SumEqual($leads, $sum);
        $leads = $this->leadsGetter->addTasksAndNotes($leads);
        $this->leadsPoster->createLeadsOnWaitingWithTasksAndNotes($leads);
    }
}
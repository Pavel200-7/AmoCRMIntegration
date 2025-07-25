<?php

require_once dirname(__DIR__) . '/AmoCrmDescriptor.php';

class LeadsPoster extends AmoCrmDescriptor
{

    private const CLIENT_WAITING_STATUS = 78705298;

    public function createLeadsOnWaitingWithTasksAndNotes(array $leads): void
    {
        $params = $this->getParamsForCreatingLeads($leads, self::CLIENT_WAITING_STATUS);
//        $params = $leads;
        $this->POSTRequestApi('leads', $params, 'POST');



    }

//    public function getParamsForCreatingLeads($leads, int $status): array
//    {
//        foreach ($leads as &$lead) {
//            unset($lead['id']);
//            unset($lead['_embedded']);
//            unset($lead['_links']);
//            unset($lead['_links']);
//            $lead['status'] = $status;
//        }
//        var_dump($leads);
//        return $leads;
//    }


}
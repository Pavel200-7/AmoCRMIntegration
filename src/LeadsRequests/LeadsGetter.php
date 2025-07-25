<?php


require_once dirname(__DIR__) . '/AmoCrmDescriptor.php';
require_once __DIR__ . '/LeadsFilters/LeadsSumFilter.php';


class LeadsGetter extends AmoCrmDescriptor
{
    private const VARONKA_ID = 9901142;
    private const Bid_STATUS = 78688246;
    private const CLIENT_APPROVE_STATUS = 78705022;

    public function getLeadsInVaroncaOnBid(): array
    {
        return $this->getLeadsInVaroncaOnStatus(self::Bid_STATUS);
    }

    public function getLeadsInVaroncaOnClientApprove(): array
    {
        return $this->getLeadsInVaroncaOnStatus(self::CLIENT_APPROVE_STATUS);
    }

    private function getLeadsInVaroncaOnStatus(int $status): array
    {
        $params = [
            'filter[statuses][0][pipeline_id]' => self::VARONKA_ID,
            'filter[statuses][0][status_id]' => $status,
            'with' => 'catalog_elements',
        ];

        $response = $this->GETRequestApi("leads", $params);

        return $response['_embedded']['leads'];
    }

    public function addTasksAndNotes(array $leads): array
    {
        array_walk($leads, function (&$value) {
            $leadId = $value['id'];
            $value['tasks'] = $this->getTasks($leadId);
            $value['notes'] = $this->getNotes($leadId);
        });

        return $leads;
    }


    public function addTasks(array $leads): array
    {
        array_walk($leads, function (&$value) {
            $leadId = $value['id'];
            $value['tasks'] = $this->getTasks($leadId);
        });

        return $leads;
    }


    public function addNotes(array $leads): array
    {
        array_walk($leads, function (&$value) {
            $leadId = $value['id'];
            $value['notes'] = $this->getNotes($leadId);
        });

        return $leads;
    }


    private function getTasks(int $id): array
    {
        $service = 'tasks';
        $params = [
            'filter[entity_id]' => $id,
            'filter[entity_type]' => 'leads',
        ];
        return $this->GETRequestApi($service, $params);//['_embedded']['tasks'];
    }


    private function getNotes(int $id): array
    {
        $service = 'leads/notes';
        $params = [
            'filter[entity_id]' => $id,
        ];
        return $this->GETRequestApi($service, $params);//['_embedded']['notes'];
    }





}
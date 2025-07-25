<?php

require_once dirname(__DIR__) . '/AmoCrmDescriptor.php';

class LeadsPatcher extends AmoCrmDescriptor
{
    private const WAIT_STATUS = 78688250;

    public function moveLeadToWaiting(array $leads): void
    {
        $params = $this->getParamsForChangingStatus($leads, self::WAIT_STATUS);
        $this->POSTRequestApi('leads', $params, 'PATCH');
    }

    public function getParamsForChangingStatus(array $leads, int $statusId): array
    {
        return array_map(function ($lead) {
            return [
                'id' => $lead['id'],
                'price' => $lead['price'],
                'pipeline_id' => $lead['pipeline_id'],
                'status_id' => self::WAIT_STATUS,
            ];
        }, $leads);
    }

}
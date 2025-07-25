<?php


require_once dirname(__DIR__) . '/AmoCrmDescriptor.php';
require_once __DIR__ . '/LeadsFilters/LeadsSumFilter.php';


class LeadsGetter extends AmoCrmDescriptor
{
    private const VARONKA_ID = 9901142;
    private const Bid_STATUS = 78688246;
    private const CLIENT_APPROVE_STATUS = 78705022;

    public function getLeadsOnBidInVaronca(): array
    {
        $params = [
            'filter[statuses][0][pipeline_id]' => self::VARONKA_ID,
            'filter[statuses][0][status_id]' => self::Bid_STATUS,
        ];

        $response = $this->GETRequestApi("leads", $params);

        return $response['_embedded']['leads'];
    }

    public function get()
    {

    }





}
<?php

require_once dirname(__DIR__) . '/AmoCrmDescriptor.php';

/*
 * Класс для размещения методов POST запросов
 */
class LeadsPoster extends AmoCrmDescriptor
{

    private const CLIENT_WAITING_STATUS = 78705298;

    /*
     * Делает полную копию лидов и переносит ее на этап "Ожидание клиента"
     */
    public function createLeadsOnWaitingWithTasksAndNotes(array $leads): void
    {
        $service = 'leads/complex';
        $taskService = 'tasks';
        $noteService = 'leads/notes';

        foreach ($leads as $lead) {
            $params = $this->getParamsForCreatingLead($lead, self::CLIENT_WAITING_STATUS);

            $newLead = $this->POSTRequestApi($service, $params, 'POST');
            $newLeadId = $newLead[0]['id'];

            if (!empty($lead['tasks'])) {
                $taskParams = $this->getTasksParams($lead['tasks'], $newLeadId);
                $this->POSTRequestApi($taskService, $taskParams, 'POST');
            }

            if (!empty($lead['notes'])) {
                $noresParams = $this->getNotesParams($lead['notes'], $newLeadId);
                $this->POSTRequestApi($noteService, $noresParams, 'POST');
            }
        }
    }

    public function getParamsForCreatingLead(array $lead, int $statusId): array
    {
        return [
            [
                'name' => $lead['name'],
                'price' => $lead['price'],
                'responsible_user_id' => $lead['responsible_user_id'],
                'pipeline_id' => $lead['pipeline_id'],
                'status_id' => $statusId,
                'account_id' => $lead['account_id'],
                '_embedded' => $lead['_embedded'],
            ]
        ];
    }

    public function getTasksParams(array $leadTasks, int $leadId): array
    {
        return array_map(function ($leadTask) use ($leadId) {
            return [
                'task_type_id' => $leadTask["task_type_id"],
                'text' => $leadTask["text"],
                'complete_till' => $leadTask["complete_till"],
                'entity_id' =>$leadId,
                'entity_type' => $leadTask["entity_type"],
            ];
        }, $leadTasks);
    }

    public function getNotesParams(array $leadNotes, int $leadId): array
    {
        return array_map(function ($leadNote) use ($leadId) {
            return [
                'entity_id' => $leadId,
                'note_type' => $leadNote['note_type'],
                'responsible_user_id' => $leadNote['responsible_user_id'],
                'params' => $leadNote['params'],
            ];
        }, $leadNotes);
    }
}
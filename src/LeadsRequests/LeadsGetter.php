<?php


require_once dirname(__DIR__) . '/AmoCrmDescriptor.php';

/*
 * Класс для размещения методов GET запросов
 */
class LeadsGetter extends AmoCrmDescriptor
{
    private const VARONKA_ID = 9901142;
    private const Bid_STATUS = 78688246;
    private const CLIENT_APPROVE_STATUS = 78705022;

     /*
      * Получает выборку основных данных лидов из воронки "Воронка" (с индексом VARONKA_ID),
      * находящихся на этапе "Заявка" (с индексом Bid_STATUS)
      */
    public function getLeadsInVaroncaOnBid(): array
    {
        return $this->getLeadsInVaroncaOnStatus(self::Bid_STATUS);
    }

    /*
     * Получает выборку основных данных лидов из воронки "Воронка" (с индексом VARONKA_ID),
     * находящихся на этапе "Клиент подтвердил" (с индексом CLIENT_APPROVE_STATUS)
     */
    public function getLeadsInVaroncaOnClientApprove(): array
    {
        return $this->getLeadsInVaroncaOnStatus(self::CLIENT_APPROVE_STATUS);
    }

    /*
     * Получает расширенную выборку (с контактами и компаниями) данных лидов из воронки "Воронка" (с индексом VARONKA_ID),
     * находящихся на этапе "Клиент подтвердил" (с индексом CLIENT_APPROVE_STATUS)
     */
    public function getLeadsInVaroncaOnClientApproveFull(): array
    {
        return $this->getLeadsInVaroncaOnStatusFull(self::CLIENT_APPROVE_STATUS);
    }

    /*
     * Получает выборку основных данных лидов из воронки "Воронка" (с индексом VARONKA_ID),
     * находящихся на том этапе чей индекс передается аргументом
     *
     * @ integer $status - id статуса
     */
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


    /*
     * Получает расширенную выборку (с контактами и компаниями) данных лидов из воронки "Воронка" (с индексом VARONKA_ID),
     * находящихся на том этапе чей индекс передается аргументом
     *
     * @ integer $status - id статуса
     */
    private function getLeadsInVaroncaOnStatusFull(int $status): array
    {
        $params = [
            'filter[statuses][0][pipeline_id]' => self::VARONKA_ID,
            'filter[statuses][0][status_id]' => $status,
            'with' => 'catalog_elements,contacts',
        ];

        $response = $this->GETRequestApi("leads", $params);

        return $response['_embedded']['leads'];
    }

    /*
     * Добавляет к выборке данные о задачах и примечаниях
     *
     * @ array $leads - структура данных, получаемая при выполнении запросов, проходящих через интерфейсы
     *                  getLeadsInVaroncaOnStatus или getLeadsInVaroncaOnStatusFull
     */
    public function addTasksAndNotes(array $leads): array
    {
        array_walk($leads, function (&$value) {
            $leadId = $value['id'];
            $value['tasks'] = $this->getTasks($leadId);
            $value['notes'] = $this->getNotes($leadId);
        });

        return $leads;
    }

    /*
     * Добавляет к выборке данные о задачах
     *
     * @ array $leads - структура данных, получаемая при выполнении запросов, проходящих через интерфейсы
     *                  getLeadsInVaroncaOnStatus или getLeadsInVaroncaOnStatusFull
     */
    public function addTasks(array $leads): array
    {
        array_walk($leads, function (&$value) {
            $leadId = $value['id'];
            $value['tasks'] = $this->getTasks($leadId);
        });

        return $leads;
    }

    /*
     * Добавляет к выборке данные о примечаниях
     *
     * @ array $leads - структура данных, получаемая при выполнении запросов, проходящих через интерфейсы
     *                  getLeadsInVaroncaOnStatus или getLeadsInVaroncaOnStatusFull
     */
    public function addNotes(array $leads): array
    {
        array_walk($leads, function (&$value) {
            $leadId = $value['id'];
            $value['notes'] = $this->getNotes($leadId);
        });

        return $leads;
    }

    /*
     * Достает данные о задачах лидов по их id
     *
     * @ int $id
     */
    private function getTasks(int $id): array
    {
        $service = 'tasks';
        $params = [
            'filter[entity_id]' => $id,
            'filter[entity_type]' => 'leads',
        ];
        $response = $this->GETRequestApi($service, $params);
        return $response['_embedded']['tasks'] ?? [];
    }

    /*
     * Достает данные о примечания лидов по их id
     *
     * @ int $id
     */
    private function getNotes(int $id): array
    {
        $service = 'leads/notes';
        $params = [
            'filter[entity_id]' => $id,
        ];

        $response = $this->GETRequestApi($service, $params);
        return $response['_embedded']['notes'] ?? [];
    }
}
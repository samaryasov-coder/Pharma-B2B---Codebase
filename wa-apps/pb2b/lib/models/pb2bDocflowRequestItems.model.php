<?php
class pb2bDocflowRequestItemsModel extends pb2bWaproModel
{
    protected $table = 'pb2b_docflow_request_items';

    static public function getIdsByRequest(int $request_id): array
    {
        $self = new self();
        $self->setFetch('all');
        $self->setSelect(['id' => null]);
        $self->setWhere([
            'request_id' => ['simile' => '=', 'value' => $request_id],
        ]);
        $self->setOrderBy(['id' => 'ASC']);

        return $self->queryRun();
    }
}
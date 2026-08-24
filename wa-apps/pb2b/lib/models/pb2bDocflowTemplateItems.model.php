<?php
class pb2bDocflowTemplateItemsModel extends pb2bWaproModel
{
    protected $table = 'pb2b_docflow_template_items';

    static public function getIdsByTemplateAndCompanyType(int $template_id, string $company_type)
    {
        $self = new self();
        $self->setFetch('all');
        $self->setSelect(['id' => null]);
        $self->setWhere([
            'template_id' => ['simile' => '=', 'value' => $template_id],
            'company_type' => ['simile' => '=', 'value' => $company_type]
        ]);
        $self->setOrderBy(['id' => 'ASC']);

        return $self->queryRun();
    }
}
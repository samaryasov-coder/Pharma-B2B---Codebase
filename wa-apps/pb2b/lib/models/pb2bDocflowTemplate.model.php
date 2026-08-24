<?php
class pb2bDocflowTemplateModel extends pb2bWaproModel
{
    protected $table = 'pb2b_docflow_template';

    static public function getOrCreateTemplateByProcessType(int $process_type, int $company_id): ?int
    {
        $self = new self();
        $template_data = $self->getByField([
            'company_id' => $company_id,
            'process_type' => $process_type
        ]);

        if ($template_data)
            return (int) $template_data['id'];

        $template_id = (int) $self->insert([
            'company_id'  => $company_id,
            'process_type' => $process_type,
        ]);

        if (!$template_id)
            return null;

        return $template_id;
    }
}
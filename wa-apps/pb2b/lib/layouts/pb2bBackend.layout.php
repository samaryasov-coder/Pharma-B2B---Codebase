<?php
class pb2bBackendLayout extends waLayout
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        parent::execute();
        $sidebar_mode = pb2bWaproHelper::getSidebarMode();
        $filters = array();

        if($sidebar_mode === 'company') {
            $company_filter = waRequest::request('company', array(), waRequest::TYPE_ARRAY);
            $filters['company'] = (new pb2bCompanyCollection)->buildSidebarFilters( $company_filter);
        }

        if($sidebar_mode === 'tender') {
            $tender_filter = waRequest::request('tender', array(), waRequest::TYPE_ARRAY);
            $filters['tender'] = (new pb2bTenderCollection)->buildSidebarFilters($tender_filter);
        }

        $this->view->assign(array(
            'top_menu' => pb2bWaproHelper::getConfigOption('sidebar_top_menu'),
            'sidebar_mode' => $sidebar_mode,
            'sidebar_filters' => $filters ?? array(),
        ));
    }
}
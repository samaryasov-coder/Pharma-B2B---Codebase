<?php

class pb2bFrontendCabinetPrequalEditAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer(): void
    {
        $id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $prequal = (new pb2bPrequalService())->getFromBuyer($id, $this->context->company());
        if (!$prequal->canEdit()) {
            throw new waException('Изменять можно только черновик ПК', pb2bHttpStatus::CONFLICT);
        }
        $classifiers = $prequal->getClassifiers();
        $types = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'code');
        $category_ids = array();
        foreach ($classifiers as $row) {
            if ((int) $row['row_kind'] === pb2bPrequalClassifier::CLASSIFIER && (int) $row['classifier_type'] === (int) $types['category']['id']) $category_ids[(int) $row['classifier_id']] = true;
        }
        $this->view->assign(array(
            'prequal_steps' => pb2bPrequal::getCreateSteps(),
            'prequal_id' => (int) $prequal->id,
            'prequal_data' => $prequal->data,
            'prequal_invitations' => $prequal->getInvitations(),
            'prequal_category_ids' => $category_ids,
            'prequal_categories' => (new pb2bWaproTree((new pb2bCategoryCollection())->getCabinetTreeRows()))->getTree(),
            'prequal_documents' => $prequal->getDocuments(),
            'prequal_items' => $prequal->getItems(),
        ));
        $this->setCabinetThemeTemplate('prequal/prequal-form.html');
    }
}

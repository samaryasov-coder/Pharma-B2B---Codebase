<?php

$ROLE_BOTH = "<role:(buyer|supplier)>";
$ROLE_BUYER = "<role:buyer>";
$ROLE_SUPPLIER = "<role:supplier>";

return [
    '' => 'frontend/home',

    'cabinet/' => 'frontend/cabinetRedirect',
    'cabinet/switch-role/' => 'frontend/cabinetSwitchRole',

    "cabinet/$ROLE_BOTH/account/" => 'frontend/cabinetAccount',

    "cabinet/$ROLE_BUYER/prequal/create/" => 'frontend/cabinetPrequalCreate',
    "cabinet/$ROLE_BUYER/prequal/<id>/edit/" => 'frontend/cabinetPrequalEdit',

    "cabinet/$ROLE_BOTH/accreditation/"=> 'frontend/cabinetAccreditation',
    "cabinet/$ROLE_BOTH/accreditation/request/<id>/"=> 'frontend/cabinetRequest',



    // HTML-страница мастера: создание тендера
    "cabinet/$ROLE_BUYER/tenders/create/" => 'frontend/cabinetTenderBuyerCreate',
    // HTML-фрагмент модалки (form/method/ — выбор способа закупки)
    "cabinet/$ROLE_BUYER/tenders/form/<section>/" => 'frontend/cabinetTendersBuyerForm',
    // Список «Мои тендеры» (buyer/supplier — разные шаблоны)
    "cabinet/$ROLE_BOTH/tenders/" => 'frontend/cabinetTenders',
    // HTML-страница мастера: редактирование (тип из БД)
    "cabinet/$ROLE_BUYER/tender/<id>/edit/" => 'frontend/cabinetTenderBuyerEdit',
    // Карточка/просмотр тендера покупателем
    "cabinet/$ROLE_BUYER/tender/<id>/" => 'frontend/cabinetTender',
    // Участие поставщика (шаги заявки) — до generic tender/<id>/
    "cabinet/$ROLE_SUPPLIER/tender/<id>/application/" => 'frontend/cabinetTenderApplication',
    "cabinet/$ROLE_SUPPLIER/tender/<id>/participation/" => 'frontend/cabinetTenderParticipation',
    // Карточка/просмотр тендера поставщиком
    "cabinet/$ROLE_SUPPLIER/tender/<id>/" => 'frontend/cabinetTender',

    "cabinet/$ROLE_BOTH/company-data/" => 'frontend/cabinetData',
    'cabinet/company-data/save/' => 'frontend/cabinetDataSave',


    "cabinet/$ROLE_BOTH/" => 'frontend/cabinetAccount',

    'company-registration/' => 'frontend/companyRegistration',
    'company-registration/submit/' => 'frontend/companyRegistrationSubmit',



    /*========== FORMS ==========*/
    "cabinet/$ROLE_BUYER/request/form/<section>/"=> 'frontend/cabinetRequestBuyerForm',
    "cabinet/$ROLE_SUPPLIER/request/form/<section>/"=> 'frontend/cabinetRequestSupplierForm',

    "cabinet/$ROLE_BUYER/accreditation/form/<section>/" => 'frontend/cabinetAccreditationBuyerForm',
    'cabinet/company-data/form/<section>/' => 'frontend/cabinetDataForm',



    /*========== API ==========*/
    "api/$ROLE_BUYER/prequal/create/" => 'frontend/apiBuyerPrequalCreate',
    "api/$ROLE_BUYER/prequal/save/" => 'frontend/apiBuyerPrequalSave',
    "api/$ROLE_BUYER/prequal/get/" => 'frontend/apiBuyerPrequalGet',

    "api/$ROLE_BUYER/docflow/template/list/" => 'frontend/apiBuyerDocflowTemplateList',
    "api/$ROLE_BUYER/docflow/template/create/" => 'frontend/apiBuyerDocflowTemplateCreate',
    "api/$ROLE_BUYER/docflow/template/update/" => 'frontend/apiBuyerDocflowTemplateUpdate',
    "api/$ROLE_BUYER/docflow/template/delete/" => 'frontend/apiBuyerDocflowTemplateDelete',
    "api/$ROLE_BUYER/docflow/template/select/" => 'frontend/apiBuyerDocflowTemplateSelect',
    "api/$ROLE_BUYER/docflow/template/download/" => 'frontend/apiBuyerDocflowTemplateDownload',

    "api/$ROLE_BUYER/docflow/request/create/" => 'frontend/apiBuyerDocflowRequestCreate',
    "api/$ROLE_BUYER/docflow/request/list/" => 'frontend/apiBuyerDocflowRequestList',
    "api/$ROLE_BUYER/docflow/request/cancel/" => 'frontend/apiBuyerDocflowRequestCancel',
    "api/$ROLE_BUYER/docflow/request/reject/" => 'frontend/apiBuyerDocflowRequestReject',
    "api/$ROLE_BUYER/docflow/request/approve/" => 'frontend/apiBuyerDocflowRequestApprove',
    "api/$ROLE_BUYER/docflow/request/delete/" => 'frontend/apiBuyerDocflowRequestDelete',
    "api/$ROLE_BUYER/docflow/request/<id>/template/list/" => 'frontend/apiBuyerDocflowRequestTemplateList',
    "api/$ROLE_BUYER/docflow/request/<id>/document/list/" => 'frontend/apiBuyerDocflowRequestDocumentList',
    "api/$ROLE_BUYER/docflow/request/<id>/files/download/" => 'frontend/apiBuyerDocflowRequestFilesDownload',

    "api/$ROLE_BUYER/evaluation-template/list/" => 'frontend/apiBuyerEvaluationTemplateList',
    "api/$ROLE_BUYER/evaluation-template/save/" => 'frontend/apiBuyerEvaluationTemplateSave',
    "api/$ROLE_BUYER/evaluation-template/select/" => 'frontend/apiBuyerEvaluationTemplateSelect',
    "api/$ROLE_BUYER/evaluation-template/delete/" => 'frontend/apiBuyerEvaluationTemplateDelete',

    "api/$ROLE_BUYER/tender/list/" => 'frontend/apiBuyerTenderList',
    "api/$ROLE_BUYER/tender/create/" => 'frontend/apiBuyerTenderCreate',
    "api/$ROLE_BUYER/tender/save/" => 'frontend/apiBuyerTenderSave',
    "api/$ROLE_BUYER/tender/publish/" => 'frontend/apiBuyerTenderPublish',
    // Специфичные пути до generic tender/<id>/ — иначе upload/save ловятся Get.
    "api/$ROLE_BUYER/tender/<id>/start-review/" => 'frontend/apiBuyerTenderStartReview',
    "api/$ROLE_BUYER/tender/<id>/ranking/" => 'frontend/apiBuyerTenderRanking',
    // Winner: специфичные пути до winner/ и generic tender/<id>/.
    "api/$ROLE_BUYER/tender/<id>/winner/select/" => 'frontend/apiBuyerTenderWinnerSelect',
    "api/$ROLE_BUYER/tender/<id>/winner/clear/" => 'frontend/apiBuyerTenderWinnerClear',
    "api/$ROLE_BUYER/tender/<id>/winner/complete/" => 'frontend/apiBuyerTenderWinnerComplete',
    "api/$ROLE_BUYER/tender/<id>/winner/finish-without/" => 'frontend/apiBuyerTenderWinnerFinishWithout',
    "api/$ROLE_BUYER/tender/<id>/winner/" => 'frontend/apiBuyerTenderWinnerGet',
    "api/$ROLE_BUYER/tender/<id>/applications/<application_id>/decision/" => 'frontend/apiBuyerTenderApplicationDecision',
    "api/$ROLE_BUYER/tender/<id>/applications/<application_id>/documents/<document_id>/file/" => 'frontend/apiBuyerTenderApplicationDocumentFile',
    "api/$ROLE_BUYER/tender/<id>/applications/<application_id>/criteria/<criterion_id>/file/" => 'frontend/apiBuyerTenderCriterionFile',
    "api/$ROLE_BUYER/tender/<id>/applications/<application_id>/" => 'frontend/apiBuyerTenderApplicationGet',
    "api/$ROLE_BUYER/tender/<id>/applications/" => 'frontend/apiBuyerTenderApplicationList',
    "api/$ROLE_BUYER/tender/<id>/file/upload/" => 'frontend/apiBuyerTenderFileUpload',
    "api/$ROLE_BUYER/tender/<id>/" => 'frontend/apiBuyerTenderGet',
    "api/$ROLE_BUYER/category/tree/" => 'frontend/apiBuyerCategoryTree',


    "api/$ROLE_SUPPLIER/docflow/request/list/" => 'frontend/apiSupplierDocflowRequestList',
    "api/$ROLE_SUPPLIER/docflow/request/submit/" => 'frontend/apiSupplierDocflowRequestSubmit',
    "api/$ROLE_SUPPLIER/docflow/request/cancel/" => 'frontend/apiSupplierDocflowRequestCancel',
    "api/$ROLE_SUPPLIER/docflow/request/file/upload/" => 'frontend/apiSupplierDocflowRequestItemUpload',

    "api/$ROLE_SUPPLIER/tender/list/" => 'frontend/apiSupplierTenderList',
    "api/$ROLE_SUPPLIER/tender/<id>/documents/download/" => 'frontend/apiSupplierTenderDocumentsDownload',
    "api/$ROLE_SUPPLIER/tender/<id>/save/" => 'frontend/apiSupplierTenderSave',
    "api/$ROLE_SUPPLIER/tender/<id>/submit/" => 'frontend/apiSupplierTenderSubmit',
    "api/$ROLE_SUPPLIER/tender/<id>/withdraw/" => 'frontend/apiSupplierTenderWithdraw',
    "api/$ROLE_SUPPLIER/tender/<id>/file/upload/" => 'frontend/apiSupplierTenderFileUpload',
    "api/$ROLE_SUPPLIER/tender/<id>/applications/<application_id>/criteria/<criterion_id>/file/" => 'frontend/apiSupplierTenderCriterionFile',

    "api/$ROLE_BOTH/company/select/" => 'frontend/apiCompanySelect',
    "api/$ROLE_BOTH/docflow/request/template/download/" => 'frontend/apiCommonDocflowRequestTemplateDownload',

    /*========== END ==========*/




    /*========== AUTH ==========*/
    'auth/login/' => 'frontend/authLogin',
    'auth/registration/' => 'frontend/authRegistration',
    'auth/recovery/' => 'frontend/authRecovery',
    'auth/code/' => 'frontend/authCode',
    'auth/password/' => 'frontend/authPassword',

    'login/' => 'login',
    'logout/' => 'logout',
    'registration/' => 'registration',
    'recovery/' => 'recovery',
    'code/' => 'code',
    'resend/' => 'resend',
    'password/' => 'password',
    /*========== END ==========*/


    '<url>/' => 'frontend/error',
    '<url>' => 'frontend/noSlash',
];

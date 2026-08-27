<?php

$ROLE_BOTH = "<role:(buyer|supplier)>";
$ROLE_BUYER = "<role:buyer>";
$ROLE_SUPPLIER = "<role:supplier>";

return [
    '' => 'frontend/home',

    'cabinet/' => 'frontend/cabinetRedirect',
    'cabinet/switch-role/' => 'frontend/cabinetSwitchRole',

    "cabinet/$ROLE_BOTH/account/" => 'frontend/cabinetAccount',

    "cabinet/$ROLE_BOTH/accreditation/"=> 'frontend/cabinetAccreditation',
    "cabinet/$ROLE_BOTH/accreditation/request/<id>/"=> 'frontend/cabinetRequest',



    "cabinet/$ROLE_BOTH/tenders/" => 'frontend/cabinetTenders',
    "cabinet/$ROLE_BUYER/tenders/form/<section>/" => 'frontend/cabinetTendersBuyerForm',

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

    "api/$ROLE_BUYER/tender/list/" => 'frontend/apiBuyerTenderList',
    "api/$ROLE_BUYER/tender/create/" => 'frontend/apiBuyerTenderCreate',
    "api/$ROLE_BUYER/tender/save/" => 'frontend/apiBuyerTenderSave',
    "api/$ROLE_BUYER/tender/publish/" => 'frontend/apiBuyerTenderPublish',
    "api/$ROLE_BUYER/tender/<id>/" => 'frontend/apiBuyerTenderGet',
    "api/$ROLE_BUYER/tender/<id>/criterion/save/" => 'frontend/apiBuyerTenderCriterionSave',
    "api/$ROLE_BUYER/tender/<id>/invitation/save/" => 'frontend/apiBuyerTenderInvitationSave',
    "api/$ROLE_BUYER/tender/<id>/classifier/save/" => 'frontend/apiBuyerTenderClassifierSave',


    "api/$ROLE_SUPPLIER/docflow/request/list/" => 'frontend/apiSupplierDocflowRequestList',
    "api/$ROLE_SUPPLIER/docflow/request/submit/" => 'frontend/apiSupplierDocflowRequestSubmit',
    "api/$ROLE_SUPPLIER/docflow/request/cancel/" => 'frontend/apiSupplierDocflowRequestCancel',
    "api/$ROLE_SUPPLIER/docflow/request/file/upload/" => 'frontend/apiSupplierDocflowRequestItemUpload',

    'api/common/company/select/' => 'frontend/apiCompanySelect',
    "api/common/docflow/request/template/download/" => 'frontend/apiCommonDocflowRequestTemplateDownload',

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
<?php
return array (
  'plugins' => 
  array (
    'shop' => 
    array (
      'migrate' => 
      array (
        'name' => 'Переход на Shop-Script',
        'description' => 'Перенос данных в Shop-Script из других CMS для интернет-магазинов',
        'img' => 'wa-apps/shop/plugins/migrate/img/migrate.svg',
        'vendor' => 'webasyst',
        'version' => '2.4.7',
        'importexport' => true,
        'handlers' => 
        array (
          'backend_welcome' => 'backendWelcomeHandler',
        ),
        'id' => 'migrate',
        'app_id' => 'shop',
      ),
      'redirect' => 
      array (
        'name' => '301 Редирект',
        'description' => 'Помогает перейти на Shop-Script с других CMS и обновить адреса страниц интернет-магазина, сохранив их индексацию поисковыми системами.',
        'vendor' => 'webasyst',
        'version' => '1.2.0',
        'img' => 'wa-apps/shop/plugins/redirect/img/redirect.png',
        'icons' => 
        array (
          16 => 'img/redirect.png',
        ),
        'shop_settings' => true,
        'handlers' => 
        array (
          'frontend_error' => 'frontendError',
          'frontend_search' => 'frontendSearch',
        ),
        'id' => 'redirect',
        'app_id' => 'shop',
        'custom_settings' => true,
      ),
    ),
  ),
  'handlers' => 
  array (
    'contacts' => 
    array (
      'delete' => 
      array (
        0 => 
        array (
          'app_id' => 'blog',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'blogContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'crm',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'crmContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        2 => 
        array (
          'app_id' => 'files',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'filesContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        3 => 
        array (
          'app_id' => 'hub',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'hubContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        4 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'mailerContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        5 => 
        array (
          'app_id' => 'photos',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'photosContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        6 => 
        array (
          'app_id' => 'shop',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'shopContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        7 => 
        array (
          'app_id' => 'team',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'teamContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        8 => 
        array (
          'app_id' => 'webasyst',
          'regex' => '/delete/',
          'file' => 'contacts.delete.handler.php',
          'class' => 'webasystContactsDeleteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'merge' => 
      array (
        0 => 
        array (
          'app_id' => 'blog',
          'regex' => '/merge/',
          'file' => 'contacts.merge.handler.php',
          'class' => 'blogContactsMergeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'crm',
          'regex' => '/merge/',
          'file' => 'contacts.merge.handler.php',
          'class' => 'crmContactsMergeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        2 => 
        array (
          'app_id' => 'files',
          'regex' => '/merge/',
          'file' => 'contacts.merge.handler.php',
          'class' => 'filesContactsMergeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        3 => 
        array (
          'app_id' => 'hub',
          'regex' => '/merge/',
          'file' => 'contacts.merge.handler.php',
          'class' => 'hubContactsMergeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        4 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/merge/',
          'file' => 'contacts.merge.handler.php',
          'class' => 'mailerContactsMergeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        5 => 
        array (
          'app_id' => 'shop',
          'regex' => '/merge/',
          'file' => 'contacts.merge.handler.php',
          'class' => 'shopContactsMergeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'links' => 
      array (
        0 => 
        array (
          'app_id' => 'blog',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'blogContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'crm',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'crmContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        2 => 
        array (
          'app_id' => 'files',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'filesContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        3 => 
        array (
          'app_id' => 'hub',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'hubContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        4 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'mailerContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        5 => 
        array (
          'app_id' => 'photos',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'photosContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        6 => 
        array (
          'app_id' => 'shop',
          'regex' => '/links/',
          'file' => 'contacts.links.handler.php',
          'class' => 'shopContactsLinksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'profile.tab' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/profile\\.tab/',
          'file' => 'contacts.profile.tab.handler.php',
          'class' => 'crmContactsProfileTabHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'files',
          'regex' => '/profile\\.tab/',
          'file' => 'contacts.profile.tab.handler.php',
          'class' => 'filesContactsProfileTabHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        2 => 
        array (
          'app_id' => 'hub',
          'regex' => '/profile\\.tab/',
          'file' => 'contacts.profile.tab.handler.php',
          'class' => 'hubContactsProfileTabHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        3 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/profile\\.tab/',
          'file' => 'contacts.profile.tab.handler.php',
          'class' => 'mailerContactsProfileTabHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        4 => 
        array (
          'app_id' => 'shop',
          'regex' => '/profile\\.tab/',
          'file' => 'contacts.profile.tab.handler.php',
          'class' => 'shopContactsProfileTabHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        5 => 
        array (
          'app_id' => 'team',
          'regex' => '/profile\\.tab/',
          'file' => 'contacts.profile.tab.handler.php',
          'class' => 'teamContactsProfileTabHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'save' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/save/',
          'file' => 'contacts.save.handler.php',
          'class' => 'crmContactsSaveHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'explore' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/explore/',
          'file' => 'contacts.explore.handler.php',
          'class' => 'shopContactsExploreHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'contacts_collection' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/contacts_collection/',
          'file' => 'contacts.contacts_collection.handler.php',
          'class' => 'shopContactsContacts_collectionHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'team',
          'regex' => '/contacts_collection/',
          'file' => 'contacts.contacts_collection.handler.php',
          'class' => 'teamContactsContacts_collectionHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'shop' => 
    array (
      'reset' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/reset/',
          'file' => 'shop.reset.handler.php',
          'class' => 'crmShopResetHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_order' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/backend_order/',
          'file' => 'shop.backend_order.handler.php',
          'class' => 'crmShopBackend_orderHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_customers_list' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/backend_customers_list/',
          'file' => 'shop.backend_customers_list.handler.php',
          'class' => 'crmShopBackend_customers_listHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/backend_customers_list/',
          'file' => 'shop.backend_customers_list.handler.php',
          'class' => 'mailerShopBackend_customers_listHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'order_action' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/order_action/',
          'file' => 'shop.order_action.handler.php',
          'class' => 'crmShopOrder_actionHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_rights' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/backend_rights/',
          'file' => 'shop.backend_rights.handler.php',
          'class' => 'crmShopBackend_rightsHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'currency_primary' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/currency_primary/',
          'file' => 'shop.currency_primary.handler.php',
          'class' => 'crmShopCurrency_primaryHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'notifications_send.after' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/notifications_send\\.after/',
          'file' => 'shop.notifications_send.after.handler.php',
          'class' => 'crmShopNotifications_sendAfterHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      '*' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/order_action\\..*/',
          'file' => '',
          'class' => 'crmShopOrder_actionHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_menu' => 
      array (
        0 => 
        array (
          'app_id' => 'installer',
          'regex' => '/backend_menu/',
          'file' => 'shop.backend_menu.handler.php',
          'class' => 'installerShopBackend_menuHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_extended_menu' => 
      array (
        0 => 
        array (
          'app_id' => 'installer',
          'regex' => '/backend_extended_menu/',
          'file' => 'shop.backend_extended_menu.handler.php',
          'class' => 'installerShopBackend_extended_menuHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_products' => 
      array (
        0 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/backend_products/',
          'file' => 'shop.backend_products.handler.php',
          'class' => 'mailerShopBackend_productsHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_prod' => 
      array (
        0 => 
        array (
          'app_id' => 'site',
          'regex' => '/backend_prod/',
          'file' => 'shop.backend_prod.handler.php',
          'class' => 'siteShopBackend_prodHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_welcome' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'plugin_id' => 'migrate',
          'regex' => '/backend_welcome/',
          'class' => 'shopMigratePlugin',
          'method' => 
          array (
            0 => 'backendWelcomeHandler',
          ),
        ),
      ),
      'frontend_error' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'plugin_id' => 'redirect',
          'regex' => '/frontend_error/',
          'class' => 'shopRedirectPlugin',
          'method' => 
          array (
            0 => 'frontendError',
          ),
        ),
      ),
      'frontend_search' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'plugin_id' => 'redirect',
          'regex' => '/frontend_search/',
          'class' => 'shopRedirectPlugin',
          'method' => 
          array (
            0 => 'frontendSearch',
          ),
        ),
      ),
    ),
    'webasyst' => 
    array (
      'backend_header' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/backend_header/',
          'file' => 'webasyst.backend_header.handler.php',
          'class' => 'crmWebasystBackend_headerHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'installer',
          'regex' => '/backend_header/',
          'file' => 'webasyst.backend_header.handler.php',
          'class' => 'installerWebasystBackend_headerHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_dispatch_miss' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/backend_dispatch_miss/',
          'file' => 'webasyst.backend_dispatch_miss.handler.php',
          'class' => 'crmWebasystBackend_dispatch_missHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'team',
          'regex' => '/backend_dispatch_miss/',
          'file' => 'webasyst.backend_dispatch_miss.handler.php',
          'class' => 'teamWebasystBackend_dispatch_missHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'search_content' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/search_content/',
          'file' => 'webasyst.search_content.handler.php',
          'class' => 'crmWebasystSearch_contentHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_push' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/backend_push/',
          'file' => 'webasyst.backend_push.handler.php',
          'class' => 'crmWebasystBackend_pushHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        1 => 
        array (
          'app_id' => 'shop',
          'regex' => '/backend_push/',
          'file' => 'webasyst.backend_push.handler.php',
          'class' => 'shopWebasystBackend_pushHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
        2 => 
        array (
          'app_id' => 'webasyst',
          'regex' => '/backend_push/',
          'file' => 'webasyst.backend_push.handler.php',
          'class' => 'webasystWebasystBackend_pushHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'waid_auth' => 
      array (
        0 => 
        array (
          'app_id' => 'installer',
          'regex' => '/waid_auth/',
          'file' => 'webasyst.waid_auth.handler.php',
          'class' => 'installerWebasystWaid_authHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'backend_personal_profile' => 
      array (
        0 => 
        array (
          'app_id' => 'team',
          'regex' => '/backend_personal_profile/',
          'file' => 'webasyst.backend_personal_profile.handler.php',
          'class' => 'teamWebasystBackend_personal_profileHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'crm' => 
    array (
      'invoice_payment' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/invoice_payment/',
          'file' => 'crm.invoice_payment.handler.php',
          'class' => 'crmCrmInvoice_paymentHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'deal_stage_overdue' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/deal_stage_overdue/',
          'file' => 'crm.deal_stage_overdue.handler.php',
          'class' => 'crmCrmDeal_stage_overdueHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'deal_won' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/deal_won/',
          'file' => 'crm.deal_won.handler.php',
          'class' => 'crmCrmDeal_wonHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'deal_lost' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/deal_lost/',
          'file' => 'crm.deal_lost.handler.php',
          'class' => 'crmCrmDeal_lostHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'deal_create' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/deal_create/',
          'file' => 'crm.deal_create.handler.php',
          'class' => 'crmCrmDeal_createHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'invoice_cancel' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/invoice_cancel/',
          'file' => 'crm.invoice_cancel.handler.php',
          'class' => 'crmCrmInvoice_cancelHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'invoice_activate' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/invoice_activate/',
          'file' => 'crm.invoice_activate.handler.php',
          'class' => 'crmCrmInvoice_activateHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'invoice_expire' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/invoice_expire/',
          'file' => 'crm.invoice_expire.handler.php',
          'class' => 'crmCrmInvoice_expireHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'deal_move' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/deal_move/',
          'file' => 'crm.deal_move.handler.php',
          'class' => 'crmCrmDeal_moveHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'deal_restore' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/deal_restore/',
          'file' => 'crm.deal_restore.handler.php',
          'class' => 'crmCrmDeal_restoreHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'wa' => 
    array (
      'frontend.head' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/frontend\\.head/',
          'file' => 'wa.frontend.head.handler.php',
          'class' => 'crmWaFrontendHeadHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    '*' => 
    array (
      'wa.frontend_head' => 
      array (
        0 => 
        array (
          'app_id' => 'crm',
          'regex' => '/wa\\.frontend_head/',
          'file' => '',
          'class' => 'crmWaFrontendHeadHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'mailer' => 
    array (
      'recipients.prepare' => 
      array (
        0 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/recipients\\.prepare/',
          'file' => 'mailer.recipients.prepare.handler.php',
          'class' => 'mailerMailerRecipientsPrepareHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'recipients.form' => 
      array (
        0 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/recipients\\.form/',
          'file' => 'mailer.recipients.form.handler.php',
          'class' => 'mailerMailerRecipientsFormHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'personal.settings' => 
      array (
        0 => 
        array (
          'app_id' => 'mailer',
          'regex' => '/personal\\.settings/',
          'file' => 'mailer.personal.settings.handler.php',
          'class' => 'mailerMailerPersonalSettingsHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'site' => 
    array (
      'domain_duplicate' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/domain_duplicate/',
          'file' => 'site.domain_duplicate.handler.php',
          'class' => 'shopSiteDomain_duplicateHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'update.route' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/update\\.route/',
          'file' => 'site.update.route.handler.php',
          'class' => 'shopSiteUpdateRouteHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'route_save.before' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/route_save\\.before/',
          'file' => 'site.route_save.before.handler.php',
          'class' => 'shopSiteRoute_saveBeforeHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'route_save.after' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/route_save\\.after/',
          'file' => 'site.route_save.after.handler.php',
          'class' => 'shopSiteRoute_saveAfterHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'route_delete.after' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/route_delete\\.after/',
          'file' => 'site.route_delete.after.handler.php',
          'class' => 'shopSiteRoute_deleteAfterHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'blockpage_blocks' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/blockpage_blocks/',
          'file' => 'site.blockpage_blocks.handler.php',
          'class' => 'shopSiteBlockpage_blocksHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
      'blockpage_elements_list' => 
      array (
        0 => 
        array (
          'app_id' => 'shop',
          'regex' => '/blockpage_elements_list/',
          'file' => 'site.blockpage_elements_list.handler.php',
          'class' => 'shopSiteBlockpage_elements_listHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'hub' => 
    array (
      'backend' => 
      array (
        0 => 
        array (
          'app_id' => 'tasks',
          'regex' => '/backend/',
          'file' => 'hub.backend.handler.php',
          'class' => 'tasksHubBackendHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
    'helpdesk' => 
    array (
      'view_action' => 
      array (
        0 => 
        array (
          'app_id' => 'tasks',
          'regex' => '/view_action/',
          'file' => 'helpdesk.view_action.handler.php',
          'class' => 'tasksHelpdeskView_actionHandler',
          'method' => 
          array (
            0 => 'execute',
          ),
        ),
      ),
    ),
  ),
);

<?php

enum pb2bCompanyRole: string
{
    case BUYER = 'buyer';
    case SUPPLIER = 'supplier';
}

return [
    'app_key' => 'f1b9c4e2d7a8f3b5c0d6e1a9b7c2f4d8e3a0f5c1b9d6e2a7f0c8b3d1e4a5f9c6',
    'cabinet_menu' => [
        'sidebar' => [
            'buyer' => [
                [
                    [
                        'name' => 'Личный кабинет',
                        'icon' => 'icon-account',
                        'link' => '/cabinet/buyer/account/',
                    ],
                    [
                        'name' => 'Мои тендеры',
                        'icon' => 'icon-tender',
                        'link' => '/cabinet/buyer/tenders/',
                    ],
                    [
                        'name' => 'Задачи',
                        'icon' => 'icon-task',
                        'link' => '/cabinet/buyer/tasks/',
                    ],
                    [
                        'name' => 'Договоры',
                        'icon' => 'icon-contract',
                        'link' => '/cabinet/buyer/contracts/',
                    ],
                    [
                        'name' => 'Шаблоны',
                        'icon' => 'icon-template',
                        'link' => '/cabinet/buyer/templates/',
                    ],
                    [
                        'name' => 'Аналитика',
                        'icon' => 'icon-analytics',
                        'link' => '/cabinet/buyer/analytics/',
                    ],
                    [
                        'name' => 'Критерии',
                        'icon' => 'icon-criteria',
                        'link' => '/cabinet/buyer/criteria/',
                    ],
                    [
                        'name' => 'Контрагенты',
                        'icon' => 'icon-contractor',
                        'link' => '/cabinet/buyer/contractors/',
                    ],
                    [
                        'name' => 'Одобрение поставщиков',
                        'icon' => 'icon-approval',
                        'link' => '/cabinet/buyer/accreditation/',
                    ],
                ],
                [
                    [
                        'name' => 'Баланс',
                        'icon' => 'icon-cash',
                        'link' => '/cabinet/buyer/balance/',
                    ],
                ]
            ],
            'supplier' => [
                [
                    [
                        'name' => 'Личный кабинет',
                        'icon' => 'icon-account',
                        'link' => '/cabinet/supplier/account/',
                    ],
                    [
                        'name' => 'Тендеры',
                        'icon' => 'icon-tender',
                        'link' => '/cabinet/supplier/tenders/',
                    ],
                    [
                        'name' => 'Избранные тендеры',
                        'icon' => 'icon-favorite',
                        'link' => '/cabinet/supplier/tenders/favorite/',
                    ],
                    [
                        'name' => 'Задачи',
                        'icon' => 'icon-task',
                        'link' => '/cabinet/supplier/tasks/',
                    ],
                    [
                        'name' => 'Договоры',
                        'icon' => 'icon-contract',
                        'link' => '/cabinet/supplier/contracts/',
                    ],
                    [
                        'name' => 'Шаблоны',
                        'icon' => 'icon-template',
                        'link' => '/cabinet/supplier/templates/',
                    ],
                    [
                        'name' => 'Аналитика',
                        'icon' => 'icon-analytics',
                        'link' => '/cabinet/supplier/analytics/',
                    ],
                    [
                        'name' => 'Контрагенты',
                        'icon' => 'icon-contractor',
                        'link' => '/cabinet/supplier/contractors/',
                    ],
                    [
                        'name' => 'Запросы на одобрение',
                        'icon' => 'icon-approval',
                        'link' => '/cabinet/supplier/accreditation/',
                    ],
                ],
                [
                    [
                        'name' => 'Права',
                        'icon' => 'icon-rights',
                        'link' => '/cabinet/supplier/rights/',
                    ],
                    [
                        'name' => 'Баланс',
                        'icon' => 'icon-cash',
                        'link' => '/cabinet/supplier/balance/',
                    ],
                ]
            ]
        ],
        'header' => [
            [
                'name' => 'Настройки',
                'icon' => 'icon-settings',
                'link' => '/settings/',
            ],
            [
                'name' => 'Данные компании',
                'icon' => 'icon-office',
                'link' => '/company-data/',
            ],
        ],
    ],


    'objects' => [
        'contact' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 1000, 'min_length' => 1, 'name' => 'имя', 'viewed' => true],
                'company' => ['type' => 'string', 'name' => 'компания', 'viewed' => true],
            ],
            'cases' => ['контакт', 'контакта', 'контакту', 'контакт', 'контактом', 'контакте', 'контакты', 'контактов', 'контактам', 'контактов', 'контактами', 'контактах'],
        ],
        'tag' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 1000, 'min_length' => 1, 'name' => 'название', 'viewed' => true],
            ],
            'cases' => ['тег', 'тега', 'тегу', 'тега', 'тегом', 'теге', 'теги', 'тегов', 'тегам', 'тегов', 'тегами', 'тегах'],
        ],
        'esklp' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 1000, 'min_length' => 1, 'name' => 'название', 'viewed' => true],
            ],
        ],
        'esklpGroup' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 1000, 'min_length' => 1, 'name' => 'название', 'viewed' => true],
            ],
        ],
        'category' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 1000, 'min_length' => 1, 'name' => 'название', 'viewed' => true],
            ],
            'cases' => ['классификатор', 'классификатора', 'классификатору', 'классификатора', 'классификаторм', 'классификаторе', 'классификаторы', 'классификаторов', 'классификаторам', 'классификаторов', 'классификаторами', 'классификаторах'],
        ],
        'company' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 255, 'min_length' => 1, 'name' => 'название', 'viewed' => true, 'required' => false],
                'company_type' => array('type' => 'config', 'code' => 'company_type', 'name' => 'Тип компании', 'viewed' => true, 'nullable' => false, 'required' => true),
                'type_organization' => array('type' => 'config', 'code' => 'type_organization', 'name' => 'Тип организации', 'viewed' => true, 'nullable' => true, 'required' => false),

                'buyer' => ['type' => 'checkbox', 'name' => 'Покупатель', 'viewed' => true, 'required' => false],
                'supplier' => ['type' => 'checkbox', 'name' => 'Поставщик', 'viewed' => true, 'required' => false],

                'contact_id' => ['type' => 'int', 'name' => 'ID контакта', 'viewed' => false, 'nullable' => false, 'required' => true],
                'status' => ['type' => 'checkbox', 'name' => 'Подтвержденна ли компания', 'nullable' => true, 'required' => false],

                'inn' => ['type' => 'string', 'max_length' => 12, 'min_length' => 10, 'name' => 'ИНН', 'viewed' => true, 'nullable' => true, 'required' => true],
                'kpp' => ['type' => 'string', 'max_length' => 9, 'min_length' => 9,'name' => 'КПП', 'viewed' => true, 'nullable' => true, 'required' => false],
                'ogrn' => ['type' => 'string', 'max_length' => 15, 'min_length' => 13, 'name' => 'ОГРН', 'viewed' => true, 'nullable' => false, 'required' => true],
                'jur_address' => ['type' => 'text', 'name' => 'Юр. адрес', 'viewed' => true, 'nullable' => true, 'required' => true],

                'bank' => ['type' => 'string', 'max_length' => 255, 'name' => 'Банк', 'viewed' => true, 'nullable' => true],
                'bik' => ['type' => 'string', 'max_length' => 45, 'name' => 'БИК', 'viewed' => true, 'nullable' => true],
                'rs' => ['type' => 'string', 'max_length' => 45, 'name' => 'р/с', 'viewed' => true, 'nullable' => true],
                'ks' => ['type' => 'string', 'max_length' => 45, 'name' => 'к/с', 'viewed' => true, 'nullable' => true],

                'registry_email' => ['type' => 'email', 'max_length' => 255, 'name' => 'E-mail', 'viewed' => true, 'nullable' => true],
                'reserve_emails' => ['type' => 'text', 'name' => 'Резервные E-mail', 'viewed' => false, 'nullable' => true],
                'phone' => ['type' => 'phone', 'max_length' => 255, 'name' => 'Телефон', 'viewed' => true, 'nullable' => true, 'required' => false],
                'reserve_phones' => ['type' => 'text', 'name' => 'Резервные телефоны', 'viewed' => false, 'nullable' => true],
                'contact_person' => ['type' => 'string', 'max_length' => 255, 'name' => 'Контактное лицо', 'viewed' => true, 'nullable' => true],
                'site' => ['type' => 'string', 'max_length' => 255, 'name' => 'Веб-сайт', 'viewed' => true, 'nullable' => true],

                'blocked' => ['type' => 'checkbox', 'name' => 'заблокирована', 'viewed' => true],
                'deleted' => ['type' => 'checkbox', 'name' => 'удалена', 'viewed' => true],
            ],
            'cases' => ['компания','компании','компании','компанию','компанией','компании','компании','компаний','компаниям','компании','компаниями','компаниях'],
        ],
        'client' => [
            'fields' => [
                'name' => ['type' => 'string', 'max_length' => 255, 'min_length' => 1, 'name' => 'название', 'viewed' => true, 'required' => true],
                'buyer' => ['type' => 'checkbox', 'name' => 'Покупатель', 'viewed' => true, 'required' => false],
                'supplier' => ['type' => 'checkbox', 'name' => 'Поставщик', 'viewed' => true, 'required' => false],
                'company_id' => ['type' => 'int', 'name' => 'ID компании', 'viewed' => false, 'nullable' => true, 'required' => false],
                'archive' => ['type' => 'checkbox',  'name' => 'Архив',  'viewed' => true],
                'inn' => ['type' => 'string', 'max_length' => 10, 'min_length' => 10, 'name' => 'ИНН', 'viewed' => true, 'nullable' => false, 'required' => true],
                'ogrn' => ['type' => 'string', 'max_length' => 15, 'min_length' => 13, 'name' => 'ОГРН', 'viewed' => true, 'nullable' => false, 'required' => true],
                'jur_address' => ['type' => 'text', 'name' => 'Юр. адрес', 'viewed' => true, 'nullable' => true, 'required' => false],

                'registry_email' => ['type' => 'email', 'max_length' => 255, 'name' => 'E-mail', 'viewed' => true, 'nullable' => true],
                'phone' => ['type' => 'phone', 'max_length' => 255, 'name' => 'Телефон', 'viewed' => true, 'nullable' => true, 'required' => false],
                'site' => ['type' => 'string', 'max_length' => 255, 'name' => 'Веб-сайт', 'viewed' => true, 'nullable' => true],

                'comment ' => ['type' => 'text', 'name' => 'Коментарий', 'viewed' => true, 'nullable' => true],
                'last_contact_datetime ' => ['type' => 'date', 'name' => 'Дата последнего контакта', 'viewed' => true, 'nullable' => true],

                'bank' => ['type' => 'string', 'max_length' => 255, 'name' => 'Банк', 'viewed' => true, 'nullable' => true],
                'kpp' => ['type' => 'string', 'max_length' => 31, 'name' => 'КПП', 'viewed' => true, 'nullable' => false, 'required' => true],
                'bik' => ['type' => 'string', 'max_length' => 45, 'name' => 'БИК', 'viewed' => true, 'nullable' => true],
                'rs' => ['type' => 'string', 'max_length' => 45, 'name' => 'р/с', 'viewed' => true, 'nullable' => true],
                'ks' => ['type' => 'string', 'max_length' => 45, 'name' => 'к/с', 'viewed' => true, 'nullable' => true],

            ],
            'cases' => ['клиент', 'клиента', 'клиенту', 'клиента', 'клиентом', 'клиенте', 'клиенты', 'клиентов', 'клиентам', 'клиентов', 'клиентами', 'клиентах'],
        ],

        'client_contact' => [
            'fields' => [
                'name' => ['name' => 'ФИО', 'type' => 'string', 'nullable' => true, 'max_length' => 255],
                'post' => ['name' => 'Должность', 'type' => 'string', 'nullable' => true, 'max_length' => 255],
                'email' => ['name' => 'E-mail', 'type' => 'email', 'nullable' => true, 'max_length' => 255],
                'phone' => ['name' => 'Телефон', 'type' => 'phone', 'nullable' => 1, 'max_length' => 255],
                'sort' => ['name' => 'Сортировка', 'type' => 'int', 'nullable' => 1, 'min' => 0],
            ]
        ],

        'docflow_template' => [
            'fields' => [
                'process_type' => ['type' => 'config', 'code' => 'docflow_process_types','name' => 'Тип процесса', 'nullable' => false, 'required' => true],
                'company_id' => ['type' => 'int', 'name' => 'ID компании', 'nullable' => false, 'required' => true],
                'auto_request' => array('type'=> 'int', 'name'=> 'Автоматический запрос документов', 'nullable' => false, 'required' => false, 'min' => 0, 'max' => 1),
                'refresh_period_days' => ['type'=> 'int', 'name'=> 'Период обновления процесса', 'nullable' => true, 'required' => false],
                
            ],
            'cases' => ['шаблон', 'шаблона', 'шаблону', 'шаблон', 'шаблоном', 'шаблоне', 'шаблоны', 'шаблонов', 'шаблонам', 'шаблоны', 'шаблонами', 'шаблонах'],
        ],

        'docflow_template_item' => [
            'fields' => [
                'template_id' => ['type' => 'int', 'name' => 'ID шаблона', 'nullable' => false, 'required' => true,],
                'name' => ['name' => 'Название документа', 'type' => 'string', 'nullable' => false, 'required' => true, 'max_length' => 255],
                'comment' => ['name' => 'Комментарий', 'type' => 'string', 'nullable' => true, 'required' => false, 'max_length' => 255],
                'company_type_id' => ['type' => 'config', 'code' => 'company_type', 'name' => 'Тип компании поставщика', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['документ', 'документа', 'документу', 'документ', 'документом', 'документе', 'документы', 'документов', 'документам', 'документы', 'документами', 'документах']
        ],

        'docflow_request' => [
            'fields' => [
                'process_type' => ['type' => 'config', 'code' => 'docflow_process_types', 'name' => 'Тип процесса', 'nullable' => false, 'required' => true],
                'reviewer_company_id' => ['type' => 'int', 'name' => 'ID компании-проверяющего', 'nullable' => false, 'required' => true],
                'provider_company_id' => ['type' => 'int', 'name' => 'ID компании-поставщика', 'nullable' => false, 'required' => true],
                'file_set_id' => ['type' => 'int', 'name' => 'ID набора файлов', 'nullable' => true, 'required' => false],
                'template_id' => ['type' => 'int', 'name' => 'ID шаблона', 'nullable' => false, 'required' => true],
                'procedure_code' => ['type' => 'string', 'name' => 'Код процедуры', 'nullable' => true, 'required' => false, 'max_length' => 32],
                'status' => ['type' => 'config', 'code' => 'docflow_request_statuses', 'name' => 'Статус процесса', 'nullable' => false, 'required' => true],
                'approved_datetime' => ['type' => 'string', 'name' => 'Дата подтверждения', 'nullable' => true, 'required' => false, 'max_length' => 255],
                'expires_datetime' => array('type' => 'string', 'name' => 'Дата истечения аккредитации', 'nullable' => true, 'required' => false, 'max_length' => 255),
                'expired_datetime' => array('type' => 'string', 'name' => 'Дата перевода в истёк срок', 'nullable' => true, 'required' => false, 'max_length' => 255),
                'source_request_id' => array('type' => 'int', 'name' => 'ID исходного процесса', 'nullable' => true, 'required' => false),
                'comment' => ['type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false],
                'contact_id' => ['type' => 'int', 'name' => 'ID контактного лица', 'nullable' => true, 'required' => false],
            ],
        ],
        
        'docflow_request_item' => [
            'fields' => [
                'request_id' => ['type' => 'int', 'name' => 'ID процесса', 'nullable' => false, 'required' => true],
                'reviewer_name' => ['name' => 'Название документа от проверяющего', 'type' => 'string', 'nullable' => false, 'required' => true, 'max_length' => 255],
                'reviewer_comment' => ['name' => 'Комментарий проверяющего', 'type' => 'string', 'nullable' => true, 'required' => false],
                'sample_file_id' => ['type' => 'int', 'name' => 'ID файла шаблона', 'nullable' => true, 'required' => false],
                'reviewer_file_link_id' => ['type' => 'int', 'name' => 'ID ссылки на файл шаблона', 'nullable' => true, 'required' => false],
                'provider_comment' => ['name' => 'Комментарий поставщика', 'type' => 'string', 'nullable' => true, 'required' => false],
                'provider_file_id' => ['type' => 'int', 'name' => 'ID файла поставщика', 'nullable' => true, 'required' => false],
                'provider_file_link_id' => ['type' => 'int', 'name' => 'ID ссылки на файл шаблона', 'nullable' => true, 'required' => false],
                'status' => ['type' => 'config', 'code' => 'docflow_request_item_statuses', 'name' => 'Статус элемента процесса', 'nullable' => false, 'required' => true],
                'provider_uploaded_datetime' => ['type' => 'string', 'name' => 'Дата загрузки от поставщика', 'nullable' => true, 'required' => false, 'max_length' => 255],
                'accepted_datetime' => ['type' => 'string', 'name' => 'Дата подтверждения элемента', 'nullable' => true, 'required' => false, 'max_length' => 255],
                'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
            ],
        ],

        'tender' => [
            'fields' => [
                'type' => ['type' => 'config', 'code' => 'tender_types', 'name' => 'Тип тендера', 'nullable' => false, 'required' => true],
                'status' => ['type' => 'config', 'code' => 'tender_statuses', 'name' => 'Статус', 'nullable' => false, 'required' => false],
                'number' => ['type' => 'string', 'name' => 'Реестровый номер', 'nullable' => false, 'required' => true, 'max_length' => 64, 'min_length' => 1],
                'title' => ['type' => 'string', 'name' => 'Наименование', 'nullable' => false, 'required' => true, 'max_length' => 255, 'min_length' => 1],
                'organizer_company_id' => ['type' => 'int', 'name' => 'ID компании-организатора', 'nullable' => false, 'required' => true],
                'responsible_contact_id' => ['type' => 'int', 'name' => 'ID ответственного контакта', 'nullable' => false, 'required' => true],
                'is_private' => ['type' => 'checkbox', 'name' => 'Закрытая', 'nullable' => true, 'required' => false],
                'submission_form' => ['type' => 'config', 'code' => 'tender_submission_forms', 'name' => 'Форма участия', 'nullable' => true, 'required' => false],
                'retendering_enabled' => ['type' => 'checkbox', 'name' => 'Переторжка', 'nullable' => true, 'required' => false],
                'itemized_enabled' => ['type' => 'checkbox', 'name' => 'Попозиционная', 'nullable' => true, 'required' => false],
                'approval_required' => ['type' => 'checkbox', 'name' => 'Согласование', 'nullable' => true, 'required' => false],
                'start_at' => ['type' => 'datetime', 'name' => 'Начало', 'nullable' => true, 'required' => false],
                'end_at' => ['type' => 'datetime', 'name' => 'Окончание', 'nullable' => true, 'required' => false],
                'opening_at' => ['type' => 'datetime', 'name' => 'Вскрытие', 'nullable' => true, 'required' => false],
                'budget' => ['type' => 'decimal', 'name' => 'Бюджет', 'nullable' => true, 'required' => false],
                'currency' => ['type' => 'string', 'name' => 'Валюта', 'nullable' => true, 'required' => false, 'max_length' => 3],
                'payment_terms' => ['type' => 'text', 'name' => 'Условия оплаты', 'nullable' => true, 'required' => false],
                'delivery_terms' => ['type' => 'text', 'name' => 'Условия поставки', 'nullable' => true, 'required' => false],
                'single_supplier_company_id' => ['type' => 'int', 'name' => 'ID поставщика (ЕП)', 'nullable' => true, 'required' => false],
                'single_supplier_reason' => ['type' => 'text', 'name' => 'Обоснование ЕП', 'nullable' => true, 'required' => false],
                'prequal_validity_months' => ['type' => 'int', 'name' => 'Срок ПК, мес.', 'nullable' => true, 'required' => false],
                'okpd2_id' => ['type' => 'int', 'name' => 'OKPD2', 'nullable' => true, 'required' => false],
                'esklp_id' => ['type' => 'int', 'name' => 'ЕСКЛП', 'nullable' => true, 'required' => false],
                'atc_id' => ['type' => 'int', 'name' => 'АТХ', 'nullable' => true, 'required' => false],
                'cancellation_reason' => ['type' => 'text', 'name' => 'Причина отмены', 'nullable' => true, 'required' => false],
                'pause_reason' => ['type' => 'text', 'name' => 'Причина приостановки', 'nullable' => true, 'required' => false],
                'failed_reason' => ['type' => 'text', 'name' => 'Причина несостоявшейся', 'nullable' => true, 'required' => false],
                'create_datetime' => ['type' => 'date', 'name' => 'Дата создания', 'nullable' => true, 'required' => false],
                'update_datetime' => ['type' => 'date', 'name' => 'Дата изменения', 'nullable' => true, 'required' => false],
                'is_deleted' => ['type' => 'checkbox', 'name' => 'Удалена', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['тендер', 'тендера', 'тендеру', 'тендер', 'тендером', 'тендере', 'тендеры', 'тендеров', 'тендерам', 'тендеры', 'тендерами', 'тендерах'],
        ],

        'tender_classifier' => [
            'fields' => [
                'tender_id' => ['type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true],
                'classifier_type' => ['type' => 'config', 'code' => 'tender_classifier_types', 'name' => 'Тип классификатора', 'nullable' => false, 'required' => true],
                'classifier_id' => ['type' => 'int', 'name' => 'ID узла классификатора', 'nullable' => false, 'required' => true],
            ],
            'cases' => ['привязка классификатора', 'привязки классификатора', 'привязке классификатора', 'привязку классификатора', 'привязкой классификатора', 'привязке классификатора', 'привязки классификаторов', 'привязок классификаторов', 'привязкам классификаторов', 'привязки классификаторов', 'привязками классификаторов', 'привязках классификаторов'],
        ],

        'prequal' => [
            'fields' => [
                'procedure_code' => ['type' => 'string', 'name' => 'Код процедуры', 'nullable' => true, 'required' => false, 'max_length' => 32],
                'title' => ['type' => 'string', 'name' => 'Наименование ПК', 'nullable' => false, 'required' => true, 'max_length' => 255],
                'reviewer_company_id' => ['type' => 'int', 'name' => 'ID компании-организатора', 'nullable' => false, 'required' => true],
                'provider_company_id' => ['type' => 'int', 'name' => 'ID ответственного контакта', 'nullable' => false, 'required' => true],
                'status' => ['type' => 'config', 'code' => 'prequal_statuses', 'name' => 'Статус процесса', 'nullable' => false, 'required' => true],
                'is_private' => ['type' => 'checkbox', 'name' => 'Закрытая', 'nullable' => true, 'required' => false],
                'validity_months' => ['type' => 'int', 'name' => 'Срок действия квалификации, мес.', 'nullable' => true, 'required' => false],
                'accept_start_at' => ['type' => 'datetime', 'name' => 'Начало приёма заявок', 'nullable' => true, 'required' => false],
                'accept_end_at' => ['type' => 'datetime', 'name' => 'Окончание приёма заявок', 'nullable' => true, 'required' => false],
                'file_set_id' => ['type' => 'int', 'name' => 'ID набора файлов', 'nullable' => true, 'required' => false],
                'description' => ['type' => 'text', 'name' => 'Описание', 'nullable' => true, 'required' => false],
                'wizard_extra_json' => ['type' => 'text', 'name' => 'Доп. параметры мастера (JSON)', 'nullable' => true, 'required' => false],
                'cancellation_reason' => ['type' => 'text', 'name' => 'Причина отмены', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['процедура ПК', 'процедуры ПК', 'процедуре ПК', 'процедуру ПК', 'процедурой ПК', 'процедуре ПК', 'процедуры ПК', 'процедур ПК', 'процедурам ПК', 'процедуры ПК', 'процедурами ПК', 'процедурах ПК'],
        ],

        // 'prequalSubject' => [
        //     'fields' => [
        //         'prequal_procedure_id' => ['type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true],
        //         'name' => ['type' => 'string', 'name' => 'Название предмета', 'nullable' => false, 'required' => true, 'max_length' => 255],
        //         'description' => ['type' => 'text', 'name' => 'Описание', 'nullable' => true, 'required' => false],
        //         'okpd2_id' => ['type' => 'int', 'name' => 'OKPD2', 'nullable' => true, 'required' => false],
        //         'esklp_filter_json' => ['type' => 'text', 'name' => 'Фильтр ЕСКЛП (JSON)', 'nullable' => true, 'required' => false],
        //         'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['предмет квалификации', 'предмета квалификации', 'предмету квалификации', 'предмет квалификации', 'предметом квалификации', 'предмете квалификации', 'предметы квалификации', 'предметов квалификации', 'предметам квалификации', 'предметы квалификации', 'предметами квалификации', 'предметах квалификации'],
        // ],

        // 'prequalRequirement' => [
        //     'fields' => [
        //         'prequal_procedure_id' => ['type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true],
        //         'name' => ['type' => 'string', 'name' => 'Название документа', 'nullable' => false, 'required' => true, 'max_length' => 255],
        //         'comment' => ['type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false],
        //         'sample_file_id' => ['type' => 'int', 'name' => 'ID образца', 'nullable' => true, 'required' => false],
        //         'is_required' => ['type' => 'checkbox', 'name' => 'Обязательный', 'nullable' => true, 'required' => false],
        //         'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['требование ПК', 'требования ПК', 'требованию ПК', 'требование ПК', 'требованием ПК', 'требовании ПК', 'требования ПК', 'требований ПК', 'требованиям ПК', 'требования ПК', 'требованиями ПК', 'требованиях ПК'],
        // ],

        // 'prequalApplication' => [
        //     'fields' => [
        //         'prequal_procedure_id' => ['type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true],
        //         'supplier_company_id' => ['type' => 'int', 'name' => 'ID компании-поставщика', 'nullable' => false, 'required' => true],
        //         'submitted_by_contact_id' => ['type' => 'int', 'name' => 'ID контакта', 'nullable' => true, 'required' => false],
        //         'status' => ['type' => 'string', 'name' => 'Статус заявки', 'nullable' => false, 'required' => false, 'max_length' => 32],
        //         'file_set_id' => ['type' => 'int', 'name' => 'ID набора файлов', 'nullable' => true, 'required' => false],
        //         'supplier_comment' => ['type' => 'text', 'name' => 'Комментарий поставщика', 'nullable' => true, 'required' => false],
        //         'submitted_at' => ['type' => 'datetime', 'name' => 'Дата подачи', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['заявка на ПК', 'заявки на ПК', 'заявке на ПК', 'заявку на ПК', 'заявкой на ПК', 'заявке на ПК', 'заявки на ПК', 'заявок на ПК', 'заявкам на ПК', 'заявки на ПК', 'заявками на ПК', 'заявках на ПК'],
        // ],

        // 'prequalApplicationItem' => [
        //     'fields' => [
        //         'application_id' => ['type' => 'int', 'name' => 'ID заявки', 'nullable' => false, 'required' => true],
        //         'requirement_id' => ['type' => 'int', 'name' => 'ID требования', 'nullable' => true, 'required' => false],
        //         'name' => ['type' => 'string', 'name' => 'Название', 'nullable' => false, 'required' => true, 'max_length' => 255],
        //         'reviewer_comment' => ['type' => 'text', 'name' => 'Комментарий покупателя', 'nullable' => true, 'required' => false],
        //         'provider_comment' => ['type' => 'text', 'name' => 'Комментарий поставщика', 'nullable' => true, 'required' => false],
        //         'sample_file_id' => ['type' => 'int', 'name' => 'ID образца', 'nullable' => true, 'required' => false],
        //         'provider_file_id' => ['type' => 'int', 'name' => 'ID файла поставщика', 'nullable' => true, 'required' => false],
        //         'is_required' => ['type' => 'checkbox', 'name' => 'Обязательный', 'nullable' => true, 'required' => false],
        //         'status' => ['type' => 'string', 'name' => 'Статус', 'nullable' => false, 'required' => false, 'max_length' => 32],
        //         'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['элемент заявки ПК', 'элемента заявки ПК', 'элементу заявки ПК', 'элемент заявки ПК', 'элементом заявки ПК', 'элементе заявки ПК', 'элементы заявки ПК', 'элементов заявки ПК', 'элементам заявки ПК', 'элементы заявки ПК', 'элементами заявки ПК', 'элементах заявки ПК'],
        // ],

        // 'prequalDecision' => [
        //     'fields' => [
        //         'application_id' => ['type' => 'int', 'name' => 'ID заявки', 'nullable' => false, 'required' => true],
        //         'prequal_subject_id' => ['type' => 'int', 'name' => 'ID предмета', 'nullable' => true, 'required' => false],
        //         'decision' => ['type' => 'string', 'name' => 'Решение', 'nullable' => false, 'required' => true, 'max_length' => 32],
        //         'reason' => ['type' => 'text', 'name' => 'Причина', 'nullable' => true, 'required' => false],
        //         'decided_by_contact_id' => ['type' => 'int', 'name' => 'ID контакта', 'nullable' => false, 'required' => true],
        //         'decided_at' => ['type' => 'datetime', 'name' => 'Дата решения', 'nullable' => false, 'required' => true],
        //         'valid_until' => ['type' => 'datetime', 'name' => 'Действует до', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['решение по ПК', 'решения по ПК', 'решению по ПК', 'решение по ПК', 'решением по ПК', 'решении по ПК', 'решения по ПК', 'решений по ПК', 'решениям по ПК', 'решения по ПК', 'решениями по ПК', 'решениях по ПК'],
        // ],

        // 'prequalApplicationLog' => [
        //     'fields' => [
        //         'application_id' => ['type' => 'int', 'name' => 'ID заявки', 'nullable' => false, 'required' => true],
        //         'log_status' => ['type' => 'string', 'name' => 'Событие', 'nullable' => false, 'required' => true, 'max_length' => 64],
        //         'status_from' => ['type' => 'string', 'name' => 'Статус от', 'nullable' => true, 'required' => false, 'max_length' => 32],
        //         'status_to' => ['type' => 'string', 'name' => 'Статус до', 'nullable' => true, 'required' => false, 'max_length' => 32],
        //         'author_contact_id' => ['type' => 'int', 'name' => 'ID контакта', 'nullable' => true, 'required' => false],
        //         'author_company_id' => ['type' => 'int', 'name' => 'ID компании', 'nullable' => true, 'required' => false],
        //         'comment' => ['type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false],
        //         'file_set_id' => ['type' => 'int', 'name' => 'ID набора файлов', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['запись журнала ПК', 'записи журнала ПК', 'записи журнала ПК', 'запись журнала ПК', 'записью журнала ПК', 'записи журнала ПК', 'записи журнала ПК', 'записей журнала ПК', 'записям журнала ПК', 'записи журнала ПК', 'записями журнала ПК', 'записях журнала ПК'],
        // ],

        // 'qualifiedPool' => [
        //     'fields' => [
        //         'buyer_company_id' => ['type' => 'int', 'name' => 'ID покупателя', 'nullable' => true, 'required' => false],
        //         'name' => ['type' => 'string', 'name' => 'Название', 'nullable' => false, 'required' => true, 'max_length' => 255],
        //         'description' => ['type' => 'text', 'name' => 'Описание', 'nullable' => true, 'required' => false],
        //     ],
        //     'cases' => ['пул квалифицированных', 'пула квалифицированных', 'пулу квалифицированных', 'пул квалифицированных', 'пулом квалифицированных', 'пуле квалифицированных', 'пулы квалифицированных', 'пулов квалифицированных', 'пулам квалифицированных', 'пулы квалифицированных', 'пулами квалифицированных', 'пулах квалифицированных'],
        // ],

        // 'qualifiedPoolEntry' => [
        //     'fields' => [
        //         'qualified_pool_id' => ['type' => 'int', 'name' => 'ID пула', 'nullable' => false, 'required' => true],
        //         'supplier_company_id' => ['type' => 'int', 'name' => 'ID поставщика', 'nullable' => false, 'required' => true],
        //         'prequal_subject_id' => ['type' => 'int', 'name' => 'ID предмета', 'nullable' => true, 'required' => false],
        //         'prequal_procedure_id' => ['type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true],
        //         'decision_id' => ['type' => 'int', 'name' => 'ID решения', 'nullable' => false, 'required' => true],
        //         'valid_until' => ['type' => 'datetime', 'name' => 'Действует до', 'nullable' => false, 'required' => true],
        //         'status' => ['type' => 'string', 'name' => 'Статус', 'nullable' => false, 'required' => false, 'max_length' => 32],
        //     ],
        //     'cases' => ['запись пула', 'записи пула', 'записи пула', 'запись пула', 'записью пула', 'записи пула', 'записи пула', 'записей пула', 'записям пула', 'записи пула', 'записями пула', 'записях пула'],
        // ],
    ],
    'docflow_process_types' => [
        1 => [
            'id' => 1, 
            'name' => 'Одобрение поставщиков', 
            'reviewer_company_type' => 'buyer',
            'tender_type_id' => 4
        ],
    ],
    'tender_codes' => [
        4 => ['id' => 4, 'code' => '04'],
    ],
    'tender_types' => [
        1 => ['id' => 1, 'code' => 'prequalification', 'name' => 'Предквалификация'],
        2 => ['id' => 2, 'code' => 'quick_purchase', 'name' => 'Запрос цен (быстрая закупка)'],
        3 => ['id' => 3, 'code' => 'price_request', 'name' => 'Запрос цен'],
        4 => ['id' => 4, 'code' => 'single_supplier', 'name' => 'Закупка у единственного поставщика'],
        5 => ['id' => 5, 'code' => 'proposal_request', 'name' => 'Запрос предложений'],
        6 => ['id' => 6, 'code' => 'auction', 'name' => 'Аукцион'],
    ],
    'tender_statuses' => [
        1 => ['id' => 1, 'code' => 'draft', 'name' => 'Черновик'],
        2 => ['id' => 2, 'code' => 'na_soglasovanii', 'name' => 'На согласовании'],
        3 => ['id' => 3, 'code' => 'opublikovan', 'name' => 'Опубликована'],
        4 => ['id' => 4, 'code' => 'priem_zayavok', 'name' => 'Приём заявок'],
        5 => ['id' => 5, 'code' => 'vskrytie_zayavok', 'name' => 'Вскрытие заявок'],
        6 => ['id' => 6, 'code' => 'rassmotrenie_i_dopusk', 'name' => 'Рассмотрение и допуск'],
        7 => ['id' => 7, 'code' => 'peretorzhka_aktivna', 'name' => 'Переторжка'],
        8 => ['id' => 8, 'code' => 'auktsionnye_torgi', 'name' => 'Аукционные торги'],
        9 => ['id' => 9, 'code' => 'otsenka', 'name' => 'Оценка'],
        10 => ['id' => 10, 'code' => 'podvedenie_itogov', 'name' => 'Подведение итогов'],
        11 => ['id' => 11, 'code' => 'zaklyuchenie_dogovora', 'name' => 'Заключение договора'],
        12 => ['id' => 12, 'code' => 'arkhiv', 'name' => 'Архив'],
        13 => ['id' => 13, 'code' => 'otmenen', 'name' => 'Отменена'],
        14 => ['id' => 14, 'code' => 'nesostoyalsya', 'name' => 'Несостоялась'],
        15 => ['id' => 15, 'code' => 'priostanovlen', 'name' => 'Приостановлена'],
    ],
    'tender_submission_forms' => [
        1 => ['id' => 1, 'code' => 'open', 'name' => 'Открытая'],
        2 => ['id' => 2, 'code' => 'closed', 'name' => 'Закрытая'],
    ],
    'tender_classifier_types' => [
        1 => ['id' => 1, 'code' => 'esklp', 'name' => 'ЕСКЛП', 'target_table' => 'pb2b_esklp'],
        2 => ['id' => 2, 'code' => 'okpd2', 'name' => 'ОКПД2', 'target_table' => null],
        3 => ['id' => 3, 'code' => 'atc', 'name' => 'АТХ', 'target_table' => null],
        4 => ['id' => 4, 'code' => 'category', 'name' => 'Категория (внутренний классификатор)', 'target_table' => 'pb2b_category'],
    ],
    'tender_status_transitions' => [
        'draft' => ['na_soglasovanii', 'opublikovan', 'otmenen'],
        'na_soglasovanii' => ['draft', 'opublikovan', 'otmenen'],
        'opublikovan' => ['priem_zayavok', 'otmenen'],
        'priem_zayavok' => ['vskrytie_zayavok', 'otmenen', 'priostanovlen'],
        'vskrytie_zayavok' => ['rassmotrenie_i_dopusk', 'otmenen'],
        'rassmotrenie_i_dopusk' => ['peretorzhka_aktivna', 'auktsionnye_torgi', 'otsenka', 'otmenen', 'nesostoyalsya', 'priostanovlen'],
        'peretorzhka_aktivna' => ['rassmotrenie_i_dopusk'],
        'auktsionnye_torgi' => ['otsenka', 'nesostoyalsya'],
        'otsenka' => ['podvedenie_itogov'],
        'podvedenie_itogov' => ['zaklyuchenie_dogovora', 'arkhiv'],
        'zaklyuchenie_dogovora' => ['arkhiv'],
        'priostanovlen' => ['priem_zayavok', 'rassmotrenie_i_dopusk'],
        'otmenen' => ['arkhiv'],
        'nesostoyalsya' => ['arkhiv'],
    ],
    'docflow_request_statuses' => [
        1 => ['id' => 1, 'code' => 'waiting_provider', 'name' => 'Ожидает документы'],
        2 => ['id' => 2, 'code' => 'waiting_review', 'name' => 'Ожидает проверки'],
        3 => ['id' => 3, 'code' => 'approved', 'name' => 'Одобрено'],
        4 => ['id' => 4, 'code' => 'rejected', 'name' => 'Отклонено'],
        5 => ['id' => 5, 'code' => 'cancelled', 'name' => 'Отменено'],
        6 => array('id' => 6, 'code' => 'expired', 'name' => 'Истёк срок'),
    ],
    'docflow_request_item_statuses' => [
        1 => ['id' => 1, 'code' => 'waiting_provider', 'name' => 'Ожидает документ'],
        2 => ['id' => 2, 'code' => 'uploaded', 'name' => 'Документ загружен'],
        3 => ['id' => 3, 'code' => 'accepted', 'name' => 'Документ принят'],
        4 => ['id' => 4, 'code' => 'rejected', 'name' => 'Документ отклонён'],
        5 => ['id' => 5, 'code' => 'cancelled', 'name' => 'Отменено'],
    ],
    'docflow_request_log_statuses' => [
        1 => [
            'id' => 1,
            'code' => 'request_created',
            'title' => 'Заявка получена',
            'description' => 'Заявка на одобрение поставщика создана и направлена поставщику.',
        ],
        2 => [
            'id' => 2,
            'code' => 'request_submitted',
            'title' => 'Документы отправлены на одобрение',
            'description' => 'Все требуемые документы загружены и направлены на проверку покупателю.',
        ],
        3 => [
            'id' => 3,
            'code' => 'request_resubmitted',
            'title' => 'Документы отправлены на одобрение повторно',
            'description' => 'Все требуемые документы загружены и направлены на проверку покупателю повторно.',
        ],
        4 => [
            'id' => 4,
            'code' => 'request_approved',
            'title' => 'Документы одобрены',
            'description' => 'Покупатель подтвердил документы поставщика.',
        ],
        5 => [
            'id' => 5,
            'code' => 'request_rejected',
            'title' => 'Отказано в одобрении',
            'description' => 'Покупатель отклонил заявку на одобрение.',
        ],
        6 => [
            'id' => 6,
            'code' => 'request_cancelled',
            'title' => 'Заявка отменена',
            'description' => 'Процесс отменён.',
        ],
        7 => array(
            'id' => 7,
            'code' => 'request_expired',
            'title' => 'Срок аккредитации истёк',
            'description' => 'Срок действия аккредитации истёк. Требуется повторный запрос документов.',
        ),
    ],
    'docflow_refresh_decrease_modes' => array(
        1 => array('id' => 1, 'code' => 'expire_outdated', 'name' => 'Снять аккредитацию у просроченных'),
        2 => array('id' => 2, 'code' => 'keep_existing', 'name' => 'Не трогать старые, применить только к новым'),
    ),
    'prequal_statuses' => [
        1 => ['id' => 1, 'code' => 'draft', 'name' => 'Черновик'],
        2 => ['id' => 2, 'code' => 'published', 'name' => 'Опубликована'],
        3 => ['id' => 3, 'code' => 'accepting_applications', 'name' => 'Приём заявок'],
        4 => ['id' => 4, 'code' => 'review', 'name' => 'Проверка заявок'],
        5 => ['id' => 5, 'code' => 'completed', 'name' => 'Завершена'],
        6 => ['id' => 6, 'code' => 'archived', 'name' => 'Архив'],
        7 => ['id' => 7, 'code' => 'cancelled', 'name' => 'Отменена'],
        8 => ['id' => 8, 'code' => 'suspended', 'name' => 'Приостановлена'],
    ],
    'prequal_application_statuses' => [
        1 => ['id' => 1, 'code' => 'draft', 'name' => 'Черновик'],
        2 => ['id' => 2, 'code' => 'submitted', 'name' => 'Подана'],
        3 => ['id' => 3, 'code' => 'in_review', 'name' => 'На проверке'],
        4 => ['id' => 4, 'code' => 'decided', 'name' => 'Решение принято'],
        5 => ['id' => 5, 'code' => 'expired', 'name' => 'Истёк срок'],
        6 => ['id' => 6, 'code' => 'withdrawn', 'name' => 'Отозвана'],
    ],
    'prequal_application_item_statuses' => [
        1 => ['id' => 1, 'code' => 'waiting_provider', 'name' => 'Ожидает документ'],
        2 => ['id' => 2, 'code' => 'uploaded', 'name' => 'Документ загружен'],
        3 => ['id' => 3, 'code' => 'accepted', 'name' => 'Документ принят'],
        4 => ['id' => 4, 'code' => 'rejected', 'name' => 'Документ отклонён'],
    ],
    'prequal_decision_types' => [
        1 => ['id' => 1, 'code' => 'passed', 'name' => 'Пройдена'],
        2 => ['id' => 2, 'code' => 'failed', 'name' => 'Не пройдена'],
        3 => ['id' => 3, 'code' => 'needs_update', 'name' => 'Требует обновления'],
    ],
    'qualified_pool_entry_statuses' => [
        1 => ['id' => 1, 'code' => 'active', 'name' => 'Активна'],
        2 => ['id' => 2, 'code' => 'expired', 'name' => 'Истекла'],
        3 => ['id' => 3, 'code' => 'needs_update', 'name' => 'Требует обновления'],
    ],
    'company_roles' => [
        1 => ['id' => 1, 'code' => 'buyer','name' => 'Покупатель'],
        2 => ['id' => 2, 'code' => 'supplier','name' => 'Продавец'],
    ],
    'company_type' => [
        1 => ['id' => 1, 'code' => 'organization', 'name' => 'Организация'],
        2 => ['id' => 2, 'code' => 'entrepreneur', 'name' => 'Предприниматель'],
    ],
    'type_organization' => [
        1 => ['id' => 1, 'code' => 'ooo', 'name' => 'ООО', 'title' => 'Общество с ограниченной ответственностью'],
        2 => ['id' => 2, 'code' => 'odo', 'name' => 'ОДО', 'title' => 'Общество с дополнительной ответственностью'],
        3 => ['id' => 3, 'code' => 'ao', 'name' => 'АО', 'title' => 'Акционерное общество'],
        4 => ['id' => 4, 'code' => 'oao', 'name' => 'ОАО', 'title' => 'Открытое акционерное общество'],
        5 => ['id' => 5, 'code' => 'zao', 'name' => 'ЗАО', 'title' => 'Закрытое акционерное общество'],
        6 => ['id' => 6, 'code' => 'pao', 'name' => 'ПАО', 'title' => 'Публичное акционерное общество'],
    ],
    'sidebar_top_menu' => [
        'tag' => ['name' => 'Теги', 'icon' => 'fas fa-image', 'sidebar' => true],
        'classification' => ['name' => 'Фарм класс', 'icon' => 'fas fa-calendar-alt', 'sidebar' => true],
        'tender' => ['name' => 'Тендеры', 'icon' => 'fas fa-gavel', 'sidebar' => true],
        'company' => ['name' => 'Компании', 'icon' => 'fas fa-handshake', 'sidebar' => true],
        'client' => ['name' => 'Клиенты', 'icon' => 'fas fa-user-plus', 'sidebar' => true],
    ],
    'settings' => [
        'general' => [
            'name' => 'Общие настройки',
            'type' => 'general',
            'form_vertical' => false,
            'icon' => 'fas fa-home',
            'fields' => [
                'test' => [
                    'name' => 'Тест',
                    'hint' => 'Тест ',
                    'field' => ['tag' => 'input', 'type' => 'text'],
                    'default' => '',
                    'required' => true,
                ],
            ]
        ],
        'api' => [
            'name' => 'Сторонние API',
            'type' => 'api',
            'form_vertical' => false,
            'icon' => 'fas fa-plug',
            'fields' => [
                'dadata_token' => [
                    'name' => 'DaData API-ключ',
                    'hint' => 'Токен из личного кабинета DaData',
                    'field' => ['tag' => 'input', 'type' => 'password'],
                    'default' => '',
                    'required' => true,
                ]
            ],
        ],
        'docflow_defaults' => [
            'name' => 'Аккредитация поставщиков',
            'type' => 'docflow_defaults',
            'form_vertical' => true,
            'icon' => 'fas fa-file-signature',
            'fields' => array(
                'defaults_add_button' => array(
                    'field' => array(
                        'tag' => 'button',
                        'type' => 'button',
                        'text' => 'Добавить документ',
                        'class' => 'green js-popup',
                        'action' => '?module=docflow&action=templateDefaultAdd',
                    ),
                ),
                'defaults_files_table' => array(
                    'name' => 'Текущий перечень',
                    'field' => array(
                        'tag' => 'fileset_files',
                        'get_action' => '?module=docflow&action=templateDefaultGet',
                        'delete_action' => '?module=docflow&action=templateDefaultDelete',
                        'delete_id_param' => 'file_id',
                        'empty_text' => 'Позиции ещё не добавлены.',
                    ),
                ),
            ),
        ],
    ],
];

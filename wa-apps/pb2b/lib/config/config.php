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
                'company_type' => ['type' => 'string', 'max_length' => 32, 'name' => 'Тип компании', 'viewed' => true, 'nullable' => false, 'required' => true],
                'type_organization' => ['type' => 'string', 'max_length' => 32, 'name' => 'Тип организации', 'viewed' => true, 'nullable' => true, 'required' => false],

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
                'title' => array('type' => 'string', 'name' => 'Наименование', 'nullable' => false, 'required' => false, 'max_length' => 255),
                'organizer_company_id' => ['type' => 'int', 'name' => 'ID компании-организатора', 'nullable' => false, 'required' => true],
                'responsible_contact_id' => ['type' => 'int', 'name' => 'ID ответственного контакта', 'nullable' => false, 'required' => true],
                'is_private' => ['type' => 'checkbox', 'name' => 'Закрытая', 'nullable' => true, 'required' => false],
                'submission_form' => ['type' => 'config', 'code' => 'tender_submission_forms', 'name' => 'Форма участия', 'nullable' => true, 'required' => false],
                'retendering_enabled' => ['type' => 'checkbox', 'name' => 'Переторжка', 'nullable' => true, 'required' => false],
                'itemized_enabled' => ['type' => 'checkbox', 'name' => 'Попозиционная', 'nullable' => true, 'required' => false],
                /** Wave 3: как tender.status — в БД id словаря tender_winner_selection_modes. */
                'winner_selection_mode' => ['type' => 'config', 'code' => 'tender_winner_selection_modes', 'name' => 'Режим выбора победителя', 'nullable' => false, 'required' => false],
                'winner_selection_completed' => ['type' => 'checkbox', 'name' => 'Выбор победителя завершён', 'nullable' => true, 'required' => false],
                'finished_without_winner' => ['type' => 'checkbox', 'name' => 'Завершено без победителя', 'nullable' => true, 'required' => false],
                'finished_without_winner_reason' => ['type' => 'text', 'name' => 'Причина завершения без победителя', 'nullable' => true, 'required' => false],
                'approval_required' => ['type' => 'checkbox', 'name' => 'Согласование', 'nullable' => true, 'required' => false],
                'hide_initial_price' => ['type' => 'checkbox', 'name' => 'Скрывать начальную цену', 'nullable' => true, 'required' => false],
                'hide_participants_count' => ['type' => 'checkbox', 'name' => 'Скрывать число участников', 'nullable' => true, 'required' => false],
                'hide_participant_prices' => ['type' => 'checkbox', 'name' => 'Скрывать цены участников', 'nullable' => true, 'required' => false],
                'rank_prices_mode' => ['type' => 'string', 'name' => 'Режим рангов по ценам', 'nullable' => true, 'required' => false, 'max_length' => 16],
                'organizer_sees_names' => ['type' => 'checkbox', 'name' => 'Имена участников видны организатору', 'nullable' => true, 'required' => false],
                'docs_end_at' => ['type' => 'datetime', 'name' => 'Окончание загрузки документов', 'nullable' => true, 'required' => false],
                'allow_analogues' => ['type' => 'checkbox', 'name' => 'Разрешены аналоги', 'nullable' => true, 'required' => false],
                'vat_mode' => ['type' => 'string', 'name' => 'Учёт НДС', 'nullable' => true, 'required' => false, 'max_length' => 16],
                'auto_extend_no_offers' => ['type' => 'checkbox', 'name' => 'Автопродление без предложений', 'nullable' => true, 'required' => false],
                'auto_extend_on_change' => ['type' => 'checkbox', 'name' => 'Автопродление при изменении цены', 'nullable' => true, 'required' => false],
                'auto_extend_period_min' => ['type' => 'int', 'name' => 'Период автопродления, мин.', 'nullable' => true, 'required' => false],
                'min_step_enabled' => ['type' => 'checkbox', 'name' => 'Минимальный шаг изменения', 'nullable' => true, 'required' => false],
                'min_step_base' => ['type' => 'string', 'name' => 'База мин. шага', 'nullable' => true, 'required' => false, 'max_length' => 8],
                'min_step_type' => ['type' => 'string', 'name' => 'Тип мин. шага', 'nullable' => true, 'required' => false, 'max_length' => 8],
                'min_step_value' => ['type' => 'decimal', 'name' => 'Значение мин. шага', 'nullable' => true, 'required' => false],
                'only_price_reduction' => ['type' => 'checkbox', 'name' => 'Только уменьшение цены', 'nullable' => true, 'required' => false],
                'require_additional_docs' => ['type' => 'checkbox', 'name' => 'Требовать доп. документы', 'nullable' => true, 'required' => false],
                'additional_info' => ['type' => 'text', 'name' => 'Доп. информация для поставщиков', 'nullable' => true, 'required' => false],
                'additional_delivery_info' => ['type' => 'text', 'name' => 'Доп. условия доставки', 'nullable' => true, 'required' => false],
                'past_prequal_tender_id' => ['type' => 'int', 'name' => 'ID связанной ПК', 'nullable' => true, 'required' => false],
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
                'published_at' => ['type' => 'datetime', 'name' => 'Дата публикации', 'nullable' => true, 'required' => false],
                'create_datetime' => ['type' => 'date', 'name' => 'Дата создания', 'nullable' => true, 'required' => false],
                'update_datetime' => ['type' => 'date', 'name' => 'Дата изменения', 'nullable' => true, 'required' => false],
                'is_deleted' => ['type' => 'checkbox', 'name' => 'Удалена', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['тендер', 'тендера', 'тендеру', 'тендер', 'тендером', 'тендере', 'тендеры', 'тендеров', 'тендерам', 'тендеры', 'тендерами', 'тендерах'],
        ],

        'tenderInvitation' => array(
            'fields' => array(
                'tender_id' => array('type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true),
                'supplier_company_id' => array('type' => 'int', 'name' => 'ID поставщика', 'nullable' => false, 'required' => true),
                'invited_by_contact_id' => array('type' => 'int', 'name' => 'ID автора приглашения', 'nullable' => true, 'required' => false),
                'status' => array('type' => 'string', 'name' => 'Статус', 'nullable' => false, 'required' => true, 'max_length' => 32),
                'sent_at' => array('type' => 'datetime', 'name' => 'Дата отправки', 'nullable' => true, 'required' => false),
                'responded_at' => array('type' => 'datetime', 'name' => 'Дата ответа', 'nullable' => true, 'required' => false),
            ),
            'cases' => array('приглашение', 'приглашения', 'приглашению', 'приглашение', 'приглашением', 'приглашении', 'приглашения', 'приглашений', 'приглашениям', 'приглашения', 'приглашениями', 'приглашениях'),
        ),

        'tenderCriterion' => array(
            'fields' => array(
                'tender_id' => array('type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true),
                'type' => array('type' => 'string', 'name' => 'Тип ответа', 'nullable' => false, 'required' => true, 'max_length' => 32),
                'name' => array('type' => 'string', 'name' => 'Название критерия', 'nullable' => false, 'required' => true, 'max_length' => 255),
                'description' => array('type' => 'text', 'name' => 'Описание', 'nullable' => true, 'required' => false),
                'confirmation_text' => array('type' => 'string', 'name' => 'Текст подтверждения', 'nullable' => true, 'required' => false, 'max_length' => 255),
                'weight' => array('type' => 'decimal', 'name' => 'Вес', 'nullable' => true, 'required' => false),
                'is_mandatory' => array('type' => 'checkbox', 'name' => 'Обязательный', 'nullable' => false, 'required' => false),
                'sort' => array('type' => 'int', 'name' => 'Сортировка', 'nullable' => false, 'required' => false),
            ),
            'cases' => array('критерий', 'критерия', 'критерию', 'критерий', 'критерием', 'критерии', 'критерии', 'критериев', 'критериям', 'критерии', 'критериями', 'критериях'),
        ),

        'tender_classifier' => [
            'fields' => [
                'tender_id' => ['type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true],
                'classifier_type' => ['type' => 'config', 'code' => 'tender_classifier_types', 'name' => 'Тип классификатора', 'nullable' => false, 'required' => true],
                'classifier_id' => ['type' => 'int', 'name' => 'ID узла классификатора', 'nullable' => false, 'required' => true],
            ],
            'cases' => ['привязка классификатора', 'привязки классификатора', 'привязке классификатора', 'привязку классификатора', 'привязкой классификатора', 'привязке классификатора', 'привязки классификаторов', 'привязок классификаторов', 'привязкам классификаторов', 'привязки классификаторов', 'привязками классификаторов', 'привязках классификаторов'],
        ],

        'tender_item' => [
            'fields' => [
                'tender_id' => ['type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true],
                'name' => ['type' => 'string', 'name' => 'Наименование позиции', 'nullable' => false, 'required' => true, 'max_length' => 255],
                'qty' => ['type' => 'decimal', 'name' => 'Количество', 'nullable' => false, 'required' => true],
                'unit' => ['type' => 'string', 'name' => 'Единица измерения', 'nullable' => true, 'required' => false, 'max_length' => 32],
                'max_price_no_vat' => ['type' => 'decimal', 'name' => 'Макс. цена без НДС', 'nullable' => true, 'required' => false],
                'vat_rate' => ['type' => 'string', 'name' => 'Ставка НДС', 'nullable' => true, 'required' => false, 'max_length' => 16],
                'delivery_place' => ['type' => 'string', 'name' => 'Место поставки', 'nullable' => true, 'required' => false, 'max_length' => 255],
                'comment' => ['type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false],
                'file_link_id' => ['type' => 'int', 'name' => 'ID ссылки на файл', 'nullable' => true, 'required' => false],
                'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['позиция тендера', 'позиции тендера', 'позиции тендера', 'позицию тендера', 'позицией тендера', 'позиции тендера', 'позиции тендера', 'позиций тендера', 'позициям тендера', 'позиции тендера', 'позициями тендера', 'позициях тендера'],
        ],

        'tender_document' => [
            'fields' => [
                'tender_id' => ['type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true],
                'kind' => ['type' => 'string', 'name' => 'Тип документа', 'nullable' => false, 'required' => true, 'max_length' => 32],
                'name' => ['type' => 'string', 'name' => 'Название', 'nullable' => false, 'required' => true, 'max_length' => 255],
                'description' => ['type' => 'text', 'name' => 'Описание', 'nullable' => true, 'required' => false],
                'is_required' => ['type' => 'checkbox', 'name' => 'Обязательный', 'nullable' => true, 'required' => false],
                'file_link_id' => ['type' => 'int', 'name' => 'ID ссылки на файл', 'nullable' => true, 'required' => false],
                'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['документ тендера', 'документа тендера', 'документу тендера', 'документ тендера', 'документом тендера', 'документе тендера', 'документы тендера', 'документов тендера', 'документам тендера', 'документы тендера', 'документами тендера', 'документах тендера'],
        ],

        /** КП / заявка участия. Таблица pb2b_tender_application (2.1). type=string: в БД code, не id. */
        'tender_application' => [
            'fields' => [
                'tender_id' => ['type' => 'int', 'name' => 'ID тендера', 'nullable' => false, 'required' => true],
                'supplier_company_id' => ['type' => 'int', 'name' => 'ID компании-поставщика', 'nullable' => false, 'required' => true],
                'status' => ['type' => 'string', 'name' => 'Статус заявки', 'nullable' => false, 'required' => true, 'max_length' => 32],
                'nonprice_done' => ['type' => 'checkbox', 'name' => 'Неценовые заполнены', 'nullable' => true, 'required' => false],
                'approval_status' => ['type' => 'string', 'name' => 'Одобрение', 'nullable' => false, 'required' => true, 'max_length' => 32],
                'qualification_status' => ['type' => 'string', 'name' => 'Квалификация', 'nullable' => false, 'required' => true, 'max_length' => 32],
                'admission_status' => ['type' => 'string', 'name' => 'Допуск', 'nullable' => false, 'required' => true, 'max_length' => 32],
                'approval_comment' => ['type' => 'text', 'name' => 'Комментарий одобрения', 'nullable' => true, 'required' => false],
                'qualification_comment' => ['type' => 'text', 'name' => 'Комментарий квалификации', 'nullable' => true, 'required' => false],
                'admission_comment' => ['type' => 'text', 'name' => 'Комментарий допуска', 'nullable' => true, 'required' => false],
                'is_winner' => ['type' => 'checkbox', 'name' => 'Победитель процедуры', 'nullable' => true, 'required' => false],
                'submitted_at' => ['type' => 'datetime', 'name' => 'Дата подачи', 'nullable' => true, 'required' => false],
                'withdrawn_at' => ['type' => 'datetime', 'name' => 'Дата отзыва', 'nullable' => true, 'required' => false],
                'create_datetime' => ['type' => 'datetime', 'name' => 'Дата создания', 'nullable' => true, 'required' => false],
                'update_datetime' => ['type' => 'datetime', 'name' => 'Дата изменения', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['заявка на участие', 'заявки на участие', 'заявке на участие', 'заявку на участие', 'заявкой на участие', 'заявке на участие', 'заявки на участие', 'заявок на участие', 'заявкам на участие', 'заявки на участие', 'заявками на участие', 'заявках на участие'],
        ],

        'tender_application_item' => [
            'fields' => [
                'application_id' => ['type' => 'int', 'name' => 'ID заявки', 'nullable' => false, 'required' => true],
                'tender_item_id' => ['type' => 'int', 'name' => 'ID позиции извещения', 'nullable' => false, 'required' => true],
                'qty' => ['type' => 'decimal', 'name' => 'Количество (снимок)', 'nullable' => true, 'required' => false],
                'unit' => ['type' => 'string', 'name' => 'Единица (снимок)', 'nullable' => true, 'required' => false, 'max_length' => 32],
                'price_per_unit' => ['type' => 'decimal', 'name' => 'Цена за единицу', 'nullable' => true, 'required' => false],
                'vat_rate' => ['type' => 'string', 'name' => 'Ставка НДС', 'nullable' => true, 'required' => false, 'max_length' => 16],
                'is_winner' => ['type' => 'checkbox', 'name' => 'Победитель позиции', 'nullable' => true, 'required' => false],
                'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['строка цены', 'строки цены', 'строке цены', 'строку цены', 'строкой цены', 'строке цены', 'строки цен', 'строк цен', 'строкам цен', 'строки цен', 'строками цен', 'строках цен'],
        ],

        'tender_application_document' => [
            'fields' => [
                'application_id' => ['type' => 'int', 'name' => 'ID заявки', 'nullable' => false, 'required' => true],
                'tender_document_id' => ['type' => 'int', 'name' => 'ID требования извещения', 'nullable' => true, 'required' => false],
                'name' => ['type' => 'string', 'name' => 'Название', 'nullable' => false, 'required' => true, 'max_length' => 255],
                'comment' => ['type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false],
                'file_link_id' => ['type' => 'int', 'name' => 'ID ссылки на файл', 'nullable' => true, 'required' => false],
                'sort' => ['type' => 'int', 'name' => 'Сортировка', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['документ заявки', 'документа заявки', 'документу заявки', 'документ заявки', 'документом заявки', 'документе заявки', 'документы заявки', 'документов заявки', 'документам заявки', 'документы заявки', 'документами заявки', 'документах заявки'],
        ],

        'tender_application_criterion' => [
            'fields' => [
                'application_id' => ['type' => 'int', 'name' => 'ID заявки', 'nullable' => false, 'required' => true],
                'criterion_id' => ['type' => 'int', 'name' => 'ID критерия', 'nullable' => false, 'required' => true],
                'value' => ['type' => 'text', 'name' => 'Ответ', 'nullable' => true, 'required' => false],
                'file_link_id' => ['type' => 'int', 'name' => 'ID ссылки на файл', 'nullable' => true, 'required' => false],
                'confirmed' => ['type' => 'checkbox', 'name' => 'Подтверждено', 'nullable' => true, 'required' => false],
            ],
            'cases' => ['ответ на критерий', 'ответа на критерий', 'ответу на критерий', 'ответ на критерий', 'ответом на критерий', 'ответе на критерий', 'ответы на критерии', 'ответов на критерии', 'ответам на критерии', 'ответы на критерии', 'ответами на критерии', 'ответах на критерии'],
        ],

        'prequal' => [
            'fields' => array(
                'procedure_code' => array('type' => 'string', 'name' => 'Код процедуры', 'nullable' => false, 'required' => true, 'max_length' => 32),
                'title' => array('type' => 'string', 'name' => 'Наименование ПК', 'nullable' => false, 'required' => false, 'max_length' => 255),
                'reviewer_id' => array('type' => 'int', 'name' => 'Компания-организатор', 'nullable' => false, 'required' => true),
                'contact_id' => array('type' => 'int', 'name' => 'Ответственный контакт', 'nullable' => false, 'required' => true),
                'provider_id' => array('type' => 'int', 'name' => 'Прежний получатель', 'nullable' => true, 'required' => false),
                'status' => array('type' => 'config', 'code' => 'prequal_statuses', 'name' => 'Статус процесса', 'nullable' => false, 'required' => true),
                'is_private' => array('type' => 'checkbox', 'name' => 'Закрытая', 'nullable' => false, 'required' => false),
                'validity_months' => array('type' => 'int', 'name' => 'Срок квалификации, мес.', 'nullable' => true, 'required' => false),
                'accept_start_datetime' => array('type' => 'datetime', 'name' => 'Начало приёма', 'nullable' => true, 'required' => false),
                'accept_end_datetime' => array('type' => 'datetime', 'name' => 'Окончание приёма', 'nullable' => true, 'required' => false),
                'results_planned_datatime' => array('type' => 'datetime', 'name' => 'Плановая дата итогов', 'nullable' => true, 'required' => false),
                'requires_audit' => array('type' => 'checkbox', 'name' => 'Требуется аудит', 'nullable' => false, 'required' => false),
                'audit_info' => array('type' => 'text', 'name' => 'Информация об аудите', 'nullable' => true, 'required' => false),
                'requires_samples' => array('type' => 'checkbox', 'name' => 'Требуются образцы', 'nullable' => false, 'required' => false),
                'samples_info' => array('type' => 'text', 'name' => 'Требования к образцам', 'nullable' => true, 'required' => false),
                'auto_extend' => array('type' => 'checkbox', 'name' => 'Автопродление', 'nullable' => false, 'required' => false),
                'additional_info' => array('type' => 'text', 'name' => 'Дополнительная информация', 'nullable' => true, 'required' => false),
                'delivery_terms' => array('type' => 'string', 'name' => 'Условия доставки', 'nullable' => true, 'required' => false, 'max_length' => 500),
                'file_set_id' => array('type' => 'int', 'name' => 'Прежний набор файлов', 'nullable' => true, 'required' => false),
                'description' => array('type' => 'text', 'name' => 'Описание', 'nullable' => true, 'required' => false),
                'cancellation_reason' => array('type' => 'text', 'name' => 'Причина отмены', 'nullable' => true, 'required' => false),
            ),
            'cases' => ['процедура ПК', 'процедуры ПК', 'процедуре ПК', 'процедуру ПК', 'процедурой ПК', 'процедуре ПК', 'процедуры ПК', 'процедур ПК', 'процедурам ПК', 'процедуры ПК', 'процедурами ПК', 'процедурах ПК'],
        ],

        'prequalItem' => array(
            'fields' => array(
                'prequal_id' => array('type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true),
                'name' => array('type' => 'string', 'name' => 'Предмет квалификации', 'nullable' => false, 'required' => true, 'max_length' => 255),
                'qty' => array('type' => 'decimal', 'name' => 'Количество', 'nullable' => true, 'required' => false),
                'unit_id' => array('type' => 'int', 'name' => 'Единица измерения', 'nullable' => true, 'required' => false),
                'file_set_id' => array('type' => 'int', 'name' => 'Прежний набор файлов', 'nullable' => true, 'required' => false),
                'comment' => array('type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false),
                'sort' => array('type' => 'int', 'name' => 'Порядок', 'nullable' => false, 'required' => false),
            ),
            'cases' => array('предмет квалификации', 'предмета квалификации', 'предмету квалификации', 'предмет квалификации', 'предметом квалификации', 'предмете квалификации', 'предметы квалификации', 'предметов квалификации', 'предметам квалификации', 'предметы квалификации', 'предметами квалификации', 'предметах квалификации'),
        ),
        'prequalDocument' => array(
            'fields' => array(
                'prequal_id' => array('type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true),
                'name' => array('type' => 'string', 'name' => 'Название документа', 'nullable' => false, 'required' => true, 'max_length' => 255),
                'description' => array('type' => 'string', 'name' => 'Описание', 'nullable' => false, 'required' => false, 'max_length' => 500),
                'file_set_id' => array('type' => 'int', 'name' => 'Прежний набор файлов', 'nullable' => true, 'required' => false),
                'sort' => array('type' => 'int', 'name' => 'Порядок', 'nullable' => false, 'required' => false),
            ),
            'cases' => array('документ ПК', 'документа ПК', 'документу ПК', 'документ ПК', 'документом ПК', 'документе ПК', 'документы ПК', 'документов ПК', 'документам ПК', 'документы ПК', 'документами ПК', 'документах ПК'),
        ),
        'prequalClassifier' => array(
            'fields' => array(
                'prequal_id' => array('type' => 'int', 'name' => 'ID процедуры ПК', 'nullable' => false, 'required' => true),
                'row_kind' => array('type' => 'int', 'name' => 'Вид связи', 'nullable' => false, 'required' => true),
                'classifier_type' => array('type' => 'int', 'name' => 'Тип классификатора', 'nullable' => true, 'required' => false),
                'classifier_id' => array('type' => 'int', 'name' => 'Узел классификатора', 'nullable' => true, 'required' => false),
                'tag_id' => array('type' => 'int', 'name' => 'Тег', 'nullable' => true, 'required' => false),
                'sort' => array('type' => 'int', 'name' => 'Порядок', 'nullable' => false, 'required' => false),
            ),
            'cases' => array('классификация ПК', 'классификации ПК', 'классификации ПК', 'классификацию ПК', 'классификацией ПК', 'классификации ПК', 'классификации ПК', 'классификаций ПК', 'классификациям ПК', 'классификации ПК', 'классификациями ПК', 'классификациях ПК'),
        ),

        'prequalInvitation' => array(
            'fields' => array(
                'prequal_id' => array('type' => 'int', 'name' => 'Процедура ПК', 'nullable' => false, 'required' => true, 'min' => 1),
                'supplier_company_id' => array('type' => 'int', 'name' => 'Компания поставщика', 'nullable' => false, 'required' => true, 'min' => 1),
                'invited_by_contact_id' => array('type' => 'int', 'name' => 'Автор приглашения', 'nullable' => false, 'required' => true, 'min' => 1),
                'status' => array('type' => 'config', 'name' => 'Статус приглашения', 'nullable' => false, 'required' => true, 'code' => 'prequal_invitation_statuses'),
                'sent_at' => array('type' => 'datetime', 'name' => 'Отправлено', 'nullable' => true, 'required' => false),
                'create_datetime' => array('type' => 'datetime', 'name' => 'Создано', 'nullable' => true, 'required' => false),
                'update_datetime' => array('type' => 'datetime', 'name' => 'Изменено', 'nullable' => true, 'required' => false),
            ),
            'cases' => array('приглашение ПК', 'приглашения ПК', 'приглашению ПК', 'приглашение ПК', 'приглашением ПК', 'приглашении ПК', 'приглашения ПК', 'приглашений ПК', 'приглашениям ПК', 'приглашения ПК', 'приглашениями ПК', 'приглашениях ПК'),
        ),

        'prequalApplication' => array(
            'fields' => array(
                'prequal_id' => array('type' => 'int', 'name' => 'Процедура ПК', 'nullable' => false, 'required' => true, 'min' => 1),
                'supplier_company_id' => array('type' => 'int', 'name' => 'Компания поставщика', 'nullable' => false, 'required' => true, 'min' => 1),
                'contact_id' => array('type' => 'int', 'name' => 'Создатель пакета', 'nullable' => false, 'required' => true, 'min' => 1),
                'status' => array('type' => 'config', 'name' => 'Статус пакета', 'nullable' => false, 'required' => true, 'code' => 'prequal_application_statuses', 'min' => 1),
                'submitted_at' => array('type' => 'datetime', 'name' => 'Отправлено', 'nullable' => true, 'required' => false),
                'withdrawn_at' => array('type' => 'datetime', 'name' => 'Отозвано', 'nullable' => true, 'required' => false),
                'create_datetime' => array('type' => 'datetime', 'name' => 'Создано', 'nullable' => true, 'required' => false),
                'update_datetime' => array('type' => 'datetime', 'name' => 'Изменено', 'nullable' => true, 'required' => false),
            ),
            'cases' => array('пакет ПК', 'пакета ПК', 'пакету ПК', 'пакет ПК', 'пакетом ПК', 'пакете ПК', 'пакеты ПК', 'пакетов ПК', 'пакетам ПК', 'пакеты ПК', 'пакетами ПК', 'пакетах ПК'),
        ),

        'prequalApplicationItem' => array(
            'fields' => array(
                'application_id' => array('type' => 'int', 'name' => 'Пакет ПК', 'nullable' => false, 'required' => true, 'min' => 1),
                'prequal_item_id' => array('type' => 'int', 'name' => 'Предмет квалификации', 'nullable' => false, 'required' => true, 'min' => 1),
                'decision' => array('type' => 'config', 'name' => 'Результат квалификации', 'nullable' => true, 'required' => false, 'code' => 'prequal_decision_types', 'min' => 1),
                'decision_comment' => array('type' => 'text', 'name' => 'Комментарий решения', 'nullable' => true, 'required' => false),
                'decided_by_contact_id' => array('type' => 'int', 'name' => 'Автор решения', 'nullable' => true, 'required' => false, 'min' => 1),
                'decided_at' => array('type' => 'datetime', 'name' => 'Дата решения', 'nullable' => true, 'required' => false),
                'valid_until' => array('type' => 'datetime', 'name' => 'Квалификация действует до', 'nullable' => true, 'required' => false),
                'create_datetime' => array('type' => 'datetime', 'name' => 'Создано', 'nullable' => true, 'required' => false),
                'update_datetime' => array('type' => 'datetime', 'name' => 'Изменено', 'nullable' => true, 'required' => false),
            ),
            'cases' => array('предмет пакета ПК', 'предмета пакета ПК', 'предмету пакета ПК', 'предмет пакета ПК', 'предметом пакета ПК', 'предмете пакета ПК', 'предметы пакета ПК', 'предметов пакета ПК', 'предметам пакета ПК', 'предметы пакета ПК', 'предметами пакета ПК', 'предметах пакета ПК'),
        ),

        'prequalApplicationDocument' => array(
            'fields' => array(
                'application_id' => array('type' => 'int', 'name' => 'Пакет ПК', 'nullable' => false, 'required' => true, 'min' => 1),
                'prequal_document_id' => array('type' => 'int', 'name' => 'Запрашиваемый документ', 'nullable' => false, 'required' => true, 'min' => 1),
                'file_link_id' => array('type' => 'int', 'name' => 'Ссылка на файл', 'nullable' => true, 'required' => false, 'min' => 1),
                'provider_comment' => array('type' => 'text', 'name' => 'Комментарий поставщика', 'nullable' => true, 'required' => false),
                'status' => array('type' => 'config', 'name' => 'Статус проверки документа', 'nullable' => false, 'required' => true, 'code' => 'prequal_application_document_statuses', 'min' => 1),
                'uploaded_at' => array('type' => 'datetime', 'name' => 'Загружено', 'nullable' => true, 'required' => false),
                'valid_until' => array('type' => 'datetime', 'name' => 'Документ действует до', 'nullable' => true, 'required' => false),
                'review_comment' => array('type' => 'text', 'name' => 'Комментарий проверки', 'nullable' => true, 'required' => false),
                'reviewed_by_contact_id' => array('type' => 'int', 'name' => 'Проверяющий', 'nullable' => true, 'required' => false, 'min' => 1),
                'reviewed_at' => array('type' => 'datetime', 'name' => 'Дата проверки', 'nullable' => true, 'required' => false),
                'create_datetime' => array('type' => 'datetime', 'name' => 'Создано', 'nullable' => true, 'required' => false),
                'update_datetime' => array('type' => 'datetime', 'name' => 'Изменено', 'nullable' => true, 'required' => false),
            ),
            'cases' => array('документ пакета ПК', 'документа пакета ПК', 'документу пакета ПК', 'документ пакета ПК', 'документом пакета ПК', 'документе пакета ПК', 'документы пакета ПК', 'документов пакета ПК', 'документам пакета ПК', 'документы пакета ПК', 'документами пакета ПК', 'документах пакета ПК'),
        ),

        'prequalApplicationHistory' => array(
            'fields' => array(
                'application_id' => array('type' => 'int', 'name' => 'Пакет ПК', 'nullable' => false, 'required' => true, 'min' => 1),
                'application_item_id' => array('type' => 'int', 'name' => 'Предмет пакета', 'nullable' => true, 'required' => false, 'min' => 1),
                'application_document_id' => array('type' => 'int', 'name' => 'Документ пакета', 'nullable' => true, 'required' => false, 'min' => 1),
                'action' => array('type' => 'string', 'name' => 'Действие', 'nullable' => false, 'required' => true, 'max_length' => 64, 'min_length' => 1),
                'status_from' => array('type' => 'config', 'name' => 'Предыдущее состояние пакета', 'nullable' => true, 'required' => false, 'code' => 'prequal_application_statuses', 'min' => 1),
                'status_to' => array('type' => 'config', 'name' => 'Новое состояние пакета', 'nullable' => true, 'required' => false, 'code' => 'prequal_application_statuses', 'min' => 1),
                'actor_contact_id' => array('type' => 'int', 'name' => 'Автор', 'nullable' => true, 'required' => false, 'min' => 1),
                'actor_company_id' => array('type' => 'int', 'name' => 'Компания автора', 'nullable' => true, 'required' => false, 'min' => 1),
                'comment' => array('type' => 'text', 'name' => 'Комментарий', 'nullable' => true, 'required' => false),
                'data_json' => array('type' => 'text', 'name' => 'Снимок изменения', 'nullable' => true, 'required' => false),
                'create_datetime' => array('type' => 'datetime', 'name' => 'Создано', 'nullable' => true, 'required' => false),
            ),
            'cases' => array('событие пакета ПК', 'события пакета ПК', 'событию пакета ПК', 'событие пакета ПК', 'событием пакета ПК', 'событии пакета ПК', 'события пакета ПК', 'событий пакета ПК', 'событиям пакета ПК', 'события пакета ПК', 'событиями пакета ПК', 'событиях пакета ПК'),
        ),

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
        //         'prequal_item_id' => ['type' => 'int', 'name' => 'ID предмета', 'nullable' => true, 'required' => false],
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
            'tender_type_id' => 4,
            'procedure_code_type' => 'supplier_approval',
        ],
    ],
    'procedure_codes' => [
        'price_request' => ['code' => '01', 'name' => 'Запрос цен'],
        'proposal_request' => ['code' => '02', 'name' => 'Запрос предложений'],
        'prequalification' => ['code' => '03', 'name' => 'Предварительная квалификация'],
        'supplier_approval' => ['code' => '04', 'name' => 'Одобрение поставщиков'],
    ],
    'tender_types' => [
        1 => [
            'id' => 1,
            'code' => 'prequalification',
            'name' => 'Предквалификация',
            'modal_name' => 'Предварительная квалификация',
            'description' => 'Отбор поставщиков для допуска к закупкам',
            'create_title' => 'Создание процедуры предварительной квалификации',
            'show_in_create_modal' => 1,
            'create_modal_sort' => 50,
            'create_steps' => [
                ['key' => 'privacy', 'label' => 'Доступ и видимость'],
                ['key' => 'basic', 'label' => 'Основные данные'],
                ['key' => 'purchase_params', 'label' => 'Параметры закупки'],
                ['key' => 'payment_delivery', 'label' => 'Условия оплаты и доставки'],
                ['key' => 'lots', 'label' => 'Перечень предметов квалификации'],
                ['key' => 'invitation', 'label' => 'Рассылка'],
            ],
        ],
        2 => [
            'id' => 2,
            'code' => 'quick_purchase',
            'name' => 'Запрос цен (быстрая закупка)',
            'modal_name' => 'Быстрая закупка',
            'description' => 'Ускоренная процедура для типовой номенклатуры',
            'create_title' => 'Создание процедуры быстрой закупки',
            'show_in_create_modal' => 1,
            'create_modal_sort' => 10,
            'create_steps' => [
                ['key' => 'privacy', 'label' => 'Доступ и видимость'],
                ['key' => 'basic', 'label' => 'Основные данные'],
                ['key' => 'purchase_params', 'label' => 'Параметры закупки'],
                ['key' => 'lots', 'label' => 'Заполнение лотов'],
                ['key' => 'invitation', 'label' => 'Рассылка'],
            ],
        ],
        3 => [
            'id' => 3,
            'code' => 'price_request',
            'name' => 'Запрос цен',
            'modal_name' => 'Запрос цен',
            'description' => 'Побеждает участник с минимальной ценой',
            'create_title' => 'Создание процедуры запроса цен',
            'show_in_create_modal' => 1,
            'create_modal_sort' => 20,
            'create_steps' => [
                ['key' => 'privacy', 'label' => 'Доступ и видимость'],
                ['key' => 'basic', 'label' => 'Основные данные'],
                ['key' => 'purchase_params', 'label' => 'Параметры закупки'],
                ['key' => 'payment_delivery', 'label' => 'Условия оплаты и доставки'],
                ['key' => 'lots', 'label' => 'Заполнение лотов'],
                ['key' => 'invitation', 'label' => 'Рассылка'],
            ],
        ],
        4 => [
            'id' => 4,
            'code' => 'single_supplier',
            'name' => 'Закупка у единственного поставщика',
            'modal_name' => 'Закупка у единственного поставщика',
            'description' => 'Неконкурентная закупка у заранее выбранного поставщика',
            'create_title' => 'Создание процедуры закупки у единственного поставщика',
            'show_in_create_modal' => 1,
            'create_modal_sort' => 30,
            'create_steps' => [
                ['key' => 'basic', 'label' => 'Основные данные'],
                ['key' => 'purchase_params', 'label' => 'Параметры закупки'],
                ['key' => 'payment_delivery', 'label' => 'Условия оплаты и доставки'],
                ['key' => 'lots', 'label' => 'Позиции и обоснование'],
            ],
        ],
        5 => [
            'id' => 5,
            'code' => 'proposal_request',
            'name' => 'Запрос предложений',
            'modal_name' => 'Запрос предложений',
            'description' => 'Оценка по цене и условиям поставки',
            'create_title' => 'Создание процедуры запроса предложений',
            'show_in_create_modal' => 1,
            'create_modal_sort' => 40,
            'create_steps' => [
                ['key' => 'privacy', 'label' => 'Доступ и видимость'],
                ['key' => 'basic', 'label' => 'Основные данные'],
                ['key' => 'purchase_params', 'label' => 'Параметры закупки'],
                ['key' => 'payment_delivery', 'label' => 'Условия оплаты и доставки'],
                ['key' => 'lots', 'label' => 'Заполнение лотов'],
                ['key' => 'invitation', 'label' => 'Рассылка'],
            ],
        ],
        6 => [
            'id' => 6,
            'code' => 'auction',
            'name' => 'Аукцион',
            'modal_name' => 'Аукцион',
            'description' => '',
            'create_title' => 'Создание процедуры аукциона',
            'show_in_create_modal' => 0,
            'create_modal_sort' => 60,
            'create_steps' => [
                ['key' => 'privacy', 'label' => 'Доступ и видимость'],
                ['key' => 'basic', 'label' => 'Основные данные'],
                ['key' => 'purchase_params', 'label' => 'Параметры закупки'],
                ['key' => 'payment_delivery', 'label' => 'Условия оплаты и доставки'],
                ['key' => 'lots', 'label' => 'Заполнение лотов'],
                ['key' => 'invitation', 'label' => 'Рассылка'],
            ],
        ],
    ],
    'tender_statuses' => [
        1 => ['id' => 1, 'code' => 'draft', 'name' => 'Черновик', 'tone' => 'warning'],
        2 => ['id' => 2, 'code' => 'pending_approval', 'name' => 'На согласовании', 'tone' => 'info'],
        3 => ['id' => 3, 'code' => 'published', 'name' => 'Опубликована', 'tone' => 'info'],
        4 => ['id' => 4, 'code' => 'accepting_applications', 'name' => 'Приём заявок', 'tone' => 'success'],
        5 => ['id' => 5, 'code' => 'opening_applications', 'name' => 'Вскрытие заявок', 'tone' => 'info'],
        6 => ['id' => 6, 'code' => 'review_and_admission', 'name' => 'Рассмотрение и допуск', 'tone' => 'info'],
        7 => ['id' => 7, 'code' => 'rebidding', 'name' => 'Переторжка', 'tone' => 'info'],
        8 => ['id' => 8, 'code' => 'auction', 'name' => 'Аукционные торги', 'tone' => 'info'],
        9 => ['id' => 9, 'code' => 'evaluation', 'name' => 'Оценка', 'tone' => 'info'],
        10 => ['id' => 10, 'code' => 'summarizing_results', 'name' => 'Подведение итогов', 'tone' => 'info'],
        11 => ['id' => 11, 'code' => 'contract_signing', 'name' => 'Заключение договора', 'tone' => 'info'],
        12 => ['id' => 12, 'code' => 'archived', 'name' => 'Архив', 'tone' => 'info'],
        13 => ['id' => 13, 'code' => 'cancelled', 'name' => 'Отменена', 'tone' => 'error'],
        14 => ['id' => 14, 'code' => 'unsuccessful', 'name' => 'Несостоялась', 'tone' => 'info'],
        15 => ['id' => 15, 'code' => 'suspended', 'name' => 'Приостановлена', 'tone' => 'info'],
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
    /** kind в pb2b_tender_document — строковый code (не id). */
    'tender_document_kinds' => [
        1 => ['id' => 1, 'code' => 'tech_spec', 'name' => 'Техническое задание'],
        2 => ['id' => 2, 'code' => 'requirement', 'name' => 'Запрашиваемый документ'],
    ],
    /** status в pb2b_tender_application — строковый code, не id tender_statuses. */
    'tender_application_statuses' => [
        1 => ['id' => 1, 'code' => 'draft', 'name' => 'Черновик', 'tone' => 'info'],
        2 => ['id' => 2, 'code' => 'submitted', 'name' => 'Подана', 'tone' => 'success'],
        3 => ['id' => 3, 'code' => 'withdrawn', 'name' => 'Отозвана', 'tone' => 'error'],
    ],
    'tender_application_status_transitions' => [
        'draft' => ['submitted', 'withdrawn'],
        'submitted' => ['draft', 'withdrawn'],
        'withdrawn' => [],
    ],
    'tender_application_approval_statuses' => [
        1 => ['id' => 1, 'code' => 'pending', 'name' => 'Ожидает'],
        2 => ['id' => 2, 'code' => 'approved', 'name' => 'Одобрено'],
        3 => ['id' => 3, 'code' => 'rejected', 'name' => 'Отклонено'],
        4 => ['id' => 4, 'code' => 'not_required', 'name' => 'Не требуется'],
    ],
    'tender_application_qualification_statuses' => [
        1 => ['id' => 1, 'code' => 'pending', 'name' => 'Ожидает'],
        2 => ['id' => 2, 'code' => 'passed', 'name' => 'Пройдена'],
        3 => ['id' => 3, 'code' => 'failed', 'name' => 'Не пройдена'],
        4 => ['id' => 4, 'code' => 'not_required', 'name' => 'Не требуется'],
    ],
    'tender_application_admission_statuses' => [
        1 => ['id' => 1, 'code' => 'pending', 'name' => 'На рассмотрении', 'tone' => 'warning'],
        2 => ['id' => 2, 'code' => 'admitted', 'name' => 'Допущен', 'tone' => 'success'],
        3 => ['id' => 3, 'code' => 'rejected', 'name' => 'Отклонён', 'tone' => 'error'],
    ],

    'tender_winner_selection_modes' => [
        1 => ['id' => 1, 'code' => 'none', 'name' => 'Не выбран'],
        2 => ['id' => 2, 'code' => 'all', 'name' => 'Один победитель'],
        3 => ['id' => 3, 'code' => 'positional', 'name' => 'Попозиционный'],
    ],
    'tender_status_transitions' => [
        'draft' => ['pending_approval', 'published', 'cancelled'],
        'pending_approval' => ['draft', 'published', 'cancelled'],
        'published' => ['accepting_applications', 'cancelled'],
        'accepting_applications' => ['opening_applications', 'cancelled', 'suspended'],
        'opening_applications' => ['review_and_admission', 'cancelled'],
        'review_and_admission' => ['rebidding', 'auction', 'evaluation', 'cancelled', 'unsuccessful', 'suspended'],
        'rebidding' => ['review_and_admission'],
        'auction' => ['evaluation', 'unsuccessful'],
        'evaluation' => ['summarizing_results'],
        'summarizing_results' => ['contract_signing', 'archived'],
        'contract_signing' => ['archived'],
        'suspended' => ['accepting_applications', 'review_and_admission'],
        'cancelled' => ['archived'],
        'unsuccessful' => ['archived'],
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
    'prequal_invitation_statuses' => array(
        'invited' => array('code' => 'invited', 'name' => 'Приглашён'),
    ),
    'prequal_application_statuses' => [
        1 => ['id' => 1, 'code' => 'draft', 'name' => 'Черновик'],
        2 => ['id' => 2, 'code' => 'submitted', 'name' => 'Подана'],
        3 => ['id' => 3, 'code' => 'in_review', 'name' => 'На проверке'],
        4 => ['id' => 4, 'code' => 'decided', 'name' => 'Решение принято'],
        5 => ['id' => 5, 'code' => 'expired', 'name' => 'Истёк срок'],
        6 => ['id' => 6, 'code' => 'withdrawn', 'name' => 'Отозвана'],
    ],
    'prequal_application_document_statuses' => [
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

<?php

class crmContactOperationDelete
{
    /**
     * @var array
     */
    protected $contacts;

    /**
     * @var array
     */
    protected $linked_contacts;

    /**
     * @var array
     */
    protected $free_contacts;

    /**
     * @var array
     */
    protected $links;

    /**
     * @var array
     */
    protected $crm_links;

    /**
     * @var waContact
     */
    protected $contact;

    /**
     * @var int
     */
    protected $contact_id;

    /**
     * @var bool
     */
    protected $is_super_admin;

    /**
     * @var crmRights
     */
    protected $crm_rights;

    /**
     * @var array
     */
    protected static $delete_contact_ids = [];

    public function __construct($options = array())
    {
        $this->contact = wa()->getUser();
        $this->contact_id = $this->contact['id'];
        $this->is_super_admin = (bool) $this->contact->getRights('webasyst', 'backend');

        $contacts = (array)ifset($options['contacts']);
        if (empty($contacts)) {
            $this->forceEmptyCase();
            return;
        }

        $type = 'item';
        $contact = reset($contacts);
        if (wa_is_int($contact)) {
            $type = 'int';

            if (ifset($options['event']) === 'contacts.delete' && empty(array_diff($contacts, self::$delete_contact_ids))) {
                // Self fired event (deletion is alredy in process)
                $this->forceEmptyCase();
                return;
            }
        } else if (is_array($contact)) {
            if (!array_key_exists('crm_vault_id', $contact) || 
                !array_key_exists('create_contact_id', $contact) || 
                !array_key_exists('crm_user_id', $contact) ||
                !array_key_exists('is_user', $contact)
            ) {
                $contacts = array_column($contacts, 'id');
                $type = 'int';
            }
        }

        if ($type === 'int') {
            $contact_ids = $contacts;
            $contacts = (new waContactModel)->getByField(['id' => $contact_ids], 'id');
        } else {
            // force contact ids as contacts array keys
            $contacts = array_reduce($contacts, function ($result, $contact) {
                $result[$contact['id']] = $contact;
                return $result;
            }, []);
        }

        $this->crm_rights = new crmRights(array('contact' => $this->contact));
        $contacts = $this->crm_rights->dropUnallowedContacts($contacts, 'edit');

        // exclude current contact - do not allow self delete
        unset($contacts[$this->contact_id]);

        // format names
        $this->contacts = array_map(function ($contact) {
            $contact['name'] = waContactNameField::formatName($contact);
            return $contact;
        }, $contacts);

        self::$delete_contact_ids = array_column($this->contacts, 'id');
    }

    /**
     * @return array|null
     */
    public function execute()
    {
        $contacts = $this->getContacts();
        if (!$contacts) {
            return null;
        }

        // Revoke user access before deletion
        $users = $this->getUsers($contacts);
        foreach($users as $user_id => $user) {
            waUser::revokeUser($user_id);
        }

        // prepare log-params
        $count = count($contacts);
        if ($count > 30) {
            $log_params = $count;
        } else {
            $log_params = $this->getNames($contacts);
        }

        $contact_ids = array_keys($contacts);

        $this->deleteContactsAppHistory($contact_ids);

        $contact_model = new waContactModel();
        // When delete contacts also throws a contacts.delete event
        $contact_model->delete($contact_ids);

        return array(
            'count' => $count,
            'log_params' => $log_params
        );
    }

    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }

    public function getLinkedContacts()
    {
        if ($this->linked_contacts !== null) {
            return $this->linked_contacts;
        }
        $this->splitContactsByLinks();
        return $this->linked_contacts;
    }

    public function getFreeContacts()
    {
        if ($this->free_contacts !== null) {
            return $this->free_contacts;
        }
        $this->splitContactsByLinks();
        return $this->free_contacts;
    }

    public function getContacts()
    {
        return $this->is_super_admin ? $this->contacts : $this->getFreeContacts();
    }

    public function getLinks()
    {
        if ($this->links !== null) {
            return $this->links;
        }

        if (!$this->contacts) {
            return $this->links = array();
        }

        /**
         * Check contacts link in other apps
         * @event links
         * @param int[] $contact_ids
         * @return array $links
         *    Format of returned array
         *    array(
         *        <app_id> => array(
         *            <contact_id> => array(
         *                array(
         *                    'role' => string - Some string named meaning of current links
         *                    'links_number' => int - Number of links
         *                )
         *                ...
         *            )
         *            ...
         *        )
         *        ...
         *    )
         */
        $contact_ids = array_keys($this->contacts);
        $result = wa()->event(array('contacts', 'links'), $contact_ids);

        // Only super admin can delete contacts with links
        // So form links map contact_id => app_id => link-items
        $links = array();
        foreach ($result as $app_id => $app_links) {
            foreach ($app_links as $contact_id => $contact_links) {
                if ($contact_links) {
                    $links[$contact_id][$app_id] = $contact_links;
                }
            }
        }

        // Do not allow non-superadmin to remove users
        if (!$this->is_super_admin) {
            $users = $this->getUsers($this->contacts);
            foreach($users as $user_id) {
                $links[$user_id]['contacts'] = (array) ifset($links[$user_id]['contacts']);
                // User isn't removable by non-superadmin, so mark as there is link for this contact
                $links[$user_id]['contacts'][] = array('user', 1);
            }
        }

        return $this->links = $links;
    }

    /**
     * Calculate links array for CRM-app
     * @return array
     * @throws waException
     * @see getLinks for array format
     */
    public function getCrmLinks()
    {
        if ($this->crm_links !== null) {
            return $this->crm_links;
        }

        waLocale::loadByDomain('crm');

        $links = array();

        /**
         * Calculate for each model links count
         * Each model must be implement method getContactLinksCount
         *
         * @var array $role_models
         */
        $role_models = $this->getRoleModels(true);

        foreach ($this->contacts as $contact_id => $contact) {

            $links[$contact_id] = array();

            foreach ($role_models as $role_model) {
                /**
                 * @var crmModel $model
                 */
                $model = $role_model['model'];
                $count = $model->getContactLinksCount($contact_id);
                if ($count > 0) {
                    $links[$contact_id][] = array(
                        'role' => $role_model['role'],
                        'links_number' => $count,
                    );
                }
            }

        }

        return $this->crm_links = $links;
    }

    public function deleteCrmLinks()
    {
        /**
         * Calculate for each model links count
         * Each model must be implement method getContactLinksCount
         *
         * @var array $role_models
         */
        $role_models = $this->getRoleModels();

        foreach ($role_models as $role_model) {
            /**
             * @var crmModel $model
             */
            $model = $role_model['model'];
            $model->unsetContactLinks(array_keys($this->contacts));
        }
    }

    protected function getRoleModels($only_for_show = false)
    {
        $models = array(
            'deals' =>
                array(
                    'role' => _wd('crm', 'Deals'),
                    'model' => new crmDealModel()
                ),
            'segments' =>
                array(
                    'role' => _wd('crm', 'Segments'),
                    'model' => new crmSegmentModel()
                ),
            'notes' =>
                array(
                    'role' => _wd('crm', 'Notes'),
                    'model' => new crmNoteModel()
                ),
            'reminders' =>
                array(
                    'role' => _wd('crm', 'Reminders'),
                    'model' => new crmReminderModel()
                ),
            'files' =>
                array(
                    'role' => _wd('crm', 'Files'),
                    'model' => new crmFileModel()
                ),
            'recent' =>
                array(
                    'role' => _wd('crm', 'Recent views'),
                    'model' => new crmRecentModel()
                ),
            'invoices' =>
                array(
                    'role' => _wd('crm', 'Invoices'),
                    'model' => new crmInvoiceModel()
                ),
            'tags' =>
                array(
                    'role' => _wd('crm', 'Tags'),
                    'model' => new crmContactTagsModel()
                ),
            'messages' =>
                array(
                    'role' => _wd('crm', 'Messages'),
                    'model' => new crmMessageModel()
                ),
            'conversation' =>
                array(
                    'role' => 'Conversations',
                    'model' => new crmConversationModel()
                ),
            'call' =>
                array(
                    'role' => _wd('crm', 'Calls'),
                    'model' => new crmCallModel()
                ),
            'source' =>
                array(
                    'role' => 'Sources',
                    'model' => new crmSourceModel(),
                ),
        );

        if ($only_for_show) {
            unset($models['conversation'], $models['source']);
        }

        return array_values($models);
    }

    /**
     * Split contacts into 2 arrays, first contacts with links, second - without
     */
    protected function splitContactsByLinks()
    {
        if ($this->linked_contacts !== null) {
            return;
        }
        $links = $this->getLinks();
        $contacts = $this->contacts;
        $linked_contacts = array();
        $free_contacts = array();
        foreach ($contacts as $contact_id => $contact) {
            if (!empty($links[$contact_id])) {
                $contact['links'] = $links[$contact_id];
                $linked_contacts[$contact_id] = $contact;
            } else {
                $free_contacts[$contact_id] = $contact;
            }
        }
        $this->linked_contacts = $linked_contacts;
        $this->free_contacts = $free_contacts;
    }

    protected function getUsers(array $contacts)
    {
        return array_reduce($contacts, function($res, $contact) {
            if ($contact['is_user'] > 0) {
                $res[$contact['id']] = $contact;
            }
            return $res;
        }, []);
    }

    protected function deleteContactsAppHistory(array $contact_ids)
    {
        if (!wa()->appExists('contacts')) {
            return;
        }
        wa('contacts');
        $hashes = array_map(wa_lambda('$contact_id', 'return "/contact/" . $contact_id;'), $contact_ids);
        $history_model = new contactsHistoryModel();
        $history_model->deleteByField(array(
            'type' => 'add',
            'hash' => $hashes
        ));
    }

    protected function getNames(array $contacts)
    {
        return array_column($contacts, 'name');
    }

    protected function forceEmptyCase()
    {
        $this->contacts = [];
        $this->linked_contacts = [];
        $this->free_contacts = [];
        $this->links = [];
        $this->crm_links = [];
    }
}

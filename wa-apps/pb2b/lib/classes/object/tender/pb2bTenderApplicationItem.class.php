<?php

class pb2bTenderApplicationItem extends pb2bWaproObject
{
    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_application_item';
        $this->model = new pb2bTenderApplicationItemModel();
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $data['application_id'] = (int) ($data['application_id'] ?? 0);
        $data['tender_item_id'] = (int) ($data['tender_item_id'] ?? 0);
        $data['sort'] = (int) ($data['sort'] ?? 0);

        if ($data['application_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указана заявка');
        }
        if ($data['tender_item_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указана позиция извещения');
        }

        if (array_key_exists('qty', $data) && $data['qty'] !== '' && $data['qty'] !== null) {
            $qty = (float) $data['qty'];
            if (!is_numeric($data['qty']) || !is_finite($qty) || $qty <= 0) {
                return array('error' => true, 'message' => 'Количество должно быть больше нуля');
            }
            $data['qty'] = $qty;
        } else {
            $data['qty'] = null;
        }

        if (array_key_exists('price_per_unit', $data) && $data['price_per_unit'] !== '' && $data['price_per_unit'] !== null) {
            $price = (float) $data['price_per_unit'];
            if (!is_numeric($data['price_per_unit']) || !is_finite($price) || $price <= 0) {
                return array('error' => true, 'message' => 'Цена должна быть больше нуля');
            }
            $data['price_per_unit'] = $price;
        } else {
            $data['price_per_unit'] = null;
        }

        $unit = trim((string) ($data['unit'] ?? ''));
        $data['unit'] = $unit !== '' ? $unit : null;
        $vat = trim((string) ($data['vat_rate'] ?? ''));
        $data['vat_rate'] = $vat !== '' ? $vat : null;

        $winner_via_service = !empty($data['_winner_via_service']);
        unset($data['_winner_via_service']);
        if (empty($this->id)) {
            $data['is_winner'] = !empty($data['is_winner']) ? 1 : 0;
        } elseif (!$winner_via_service) {
            $data['is_winner'] = !empty($this->data['is_winner']) ? 1 : 0;
        } else {
            $new_winner = !empty($data['is_winner']) ? 1 : 0;
            $old_winner = !empty($this->data['is_winner']) ? 1 : 0;
            if ($new_winner !== $old_winner) {
                $app = new pb2bTenderApplication((int) $data['application_id']);
                $tender_id = (int) ($app->data['tender_id'] ?? 0);
                if ($tender_id > 0) {
                    $tender = new pb2bTender($tender_id);
                    if ((int) $tender->id && !empty($tender->data['winner_selection_completed'])) {
                        return array('error' => true, 'message' => 'Выбор победителя уже завершён');
                    }
                }
            }
            $data['is_winner'] = $new_winner;
        }

        $existing = $this->model->getByField(array(
            'application_id' => $data['application_id'],
            'tender_item_id' => $data['tender_item_id'],
        ));
        $existing_id = is_array($existing) ? (int) ($existing['id'] ?? 0) : 0;
        if ($existing_id > 0 && $existing_id !== (int) ($this->id ?? 0)) {
            return array('error' => true, 'message' => 'Цена по этой позиции уже указана');
        }

        return parent::preSave($data);
    }
}

<?php

class pb2bPrequalModel extends pb2bWaproModel
{
    protected $table = 'pb2b_prequal';

    public function lockForUpdate(int $id): void
    {
        $this->query('SELECT id FROM ' . $this->table . ' WHERE id = i:id FOR UPDATE', array('id' => $id));
    }
}

<?php
class pb2bAuthCodesModel extends pb2bWaproModel
{
    protected $table = 'pb2b_auth_codes';

    public function revokeActiveCodes(string $userIdentifier, string $purpose, string $channel)
    {
        return $this->updateByField(
            ['user_identifier' => $userIdentifier, 'purpose' => $purpose, 'channel' => $channel, 'status' => 'active'],
            ['status' => 'revoked']
        );
    }

    public function incrementAttempts($id)
    {
        $this->query("UPDATE {$this->table} SET attempts = attempts + 1 WHERE id = i:id",
            ['id' => $id]
        );
    }

    public function ipRateLimited(string $ip, string $purpose, int $seconds, int $limit)
    {
        $count = $this->query(
            "SELECT COUNT(*)
         FROM {$this->getTableName()}
         WHERE ip_address = s:ip
         AND purpose = s:purpose
         AND create_datetime > NOW() - INTERVAL i:sec SECOND",
            ['ip'=>$ip,'purpose'=>$purpose,'sec'=>$seconds]
        )->fetchField();

        return $count >= $limit;
    }

    public function identifierRateLimited(string $identifier, string $purpose, int $seconds)
    {
        $count = $this->query(
            "SELECT COUNT(*)
         FROM {$this->getTableName()}
         WHERE user_identifier = s:identifier
         AND purpose = s:purpose
         AND create_datetime > NOW() - INTERVAL i:sec SECOND",
            ['identifier'=>$identifier,'purpose'=>$purpose,'sec'=>$seconds]
        )->fetchField();

        return $count > 0;
    }

    public function comboRateLimited(string $ip, string $identifier, string $purpose, int $seconds, int $limit)
    {
        $count = $this->query(
            "SELECT COUNT(*)
         FROM {$this->getTableName()}
         WHERE ip_address = s:ip
         AND user_identifier = s:identifier
         AND purpose = s:purpose
         AND create_datetime > NOW() - INTERVAL i:sec SECOND",
            ['ip'=>$ip,'identifier'=>$identifier,'purpose'=>$purpose,'sec'=>$seconds]
        )->fetchField();

        return $count >= $limit;
    }
}
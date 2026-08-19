<?php

enum pb2bChannel: string {
    case SMS = 'sms';
    case EMAIL = 'email';
    case APP = 'app';
}

enum pb2bPurpose: string {
    case REGISTER = 'register';
    case LOGIN = 'login';
    case RESET_PASSWORD = 'reset_password';
}

enum pb2bStatus: string {
    case ACTIVE = 'active';
    case USED = 'used';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';
    case BLOCKED = 'blocked';
}



class pb2bAuthCodeService
{
    protected static int $maxAttempts = 5;
    protected static int $maxIpAttempts = 20;
    protected static int $ttlSeconds = 180;
    protected static int $rateSeconds = 60;
    protected $model;
    protected string $app_key;
    protected string $last_error = '';

    private function generateCode(): int
    {
        return 111111;
        return random_int(1000, 9999);
    }

    protected function sendMessage(int $code, string $channel): bool
    {
        switch ($channel) {
            case pb2bChannel::EMAIL: break;
            case pb2bChannel::SMS: break;
            case pb2bChannel::APP: break;
        }
        return true;
    }

    protected function getActiveCode(string $userIdentifier, pb2bPurpose $purpose, pb2bChannel $channel): ?array
    {
        $status = pb2bStatus::ACTIVE->value;
        $current_date = date('Y-m-d H:i:s');
        $record = $this->model->where('user_identifier', $userIdentifier)
            ->where('purpose', $purpose->value)
            ->where('channel', $channel->value)
            ->where('status', $status)
            ->where('expires_datetime', '>', $current_date)
            ->order('create_datetime DESC')
            ->limit(1)
            ->fetch();

        return $record;
    }

    protected function verify(array $data, string $code)
    {
        if (empty($data)) {
            $this->last_error = 'Неверный код';
            return false;
        }

        $id = $data['id'];
        $status = $data['status'];

        if ($status === pb2bStatus::BLOCKED->value) {
            $this->last_error = 'Попытки исчерпаны, получите новый код';
            return false;
        }

        if ($status === pb2bStatus::USED->value) {
            $this->last_error = 'Код недействителен, запросите новый';
            return false;
        }

        if (new DateTime($data['expires_datetime']) < new DateTime()) {
            $this->model->updateById($id, ['status' => pb2bStatus::EXPIRED->value]);
            $this->last_error = 'Время кода истекло, запросите новый';
            return false;
        }

        if (password_verify($code, $data['code_hash'])) {
            $this->model->updateById($id, ['status' => pb2bStatus::USED->value]);
            return true;
        }

        $this->model->incrementAttempts($id);
        $attempts = $data['attempts'] + 1;
        $attempts_left = self::$maxAttempts - $attempts;

        if ($attempts_left <= 0) {
            $this->model->updateById($id, ['status' => pb2bStatus::BLOCKED->value]);
            $this->last_error = 'Попытки исчерпаны, получите новый код';
            return false;
        }

        $this->last_error = "Неверный код, осталось попыток $attempts_left";
        return false;
    }




    public function __construct()
    {
        $this->model = new pb2bAuthCodesModel();
        $this->app_key = (string) pb2bWaproHelper::getConfigOption('app_key');
    }

    public function getLastError(): string
    {
        return $this->last_error;
    }

    public function send(string $userIdentifier, pb2bChannel $channel, pb2bPurpose $purpose): ?int
    {
        $user_ip = waRequest::getIp();
        $user_agent = waRequest::getUserAgent();

        if ($this->model->ipRateLimited($user_ip, $purpose->value, self::$rateSeconds, self::$maxIpAttempts) ||
            $this->model->identifierRateLimited($userIdentifier, $purpose->value, self::$rateSeconds) ||
            $this->model->comboRateLimited($user_ip, $userIdentifier, $purpose->value, self::$rateSeconds, self::$maxAttempts))
        {
            $rateSeconds = self::$rateSeconds;
            $this->last_error = "Лимит исчерпан, попробуйте через $rateSeconds секунд";
            return null;
        }

        $this->model->revokeActiveCodes($userIdentifier, $purpose->value, $channel->value);

        $code = $this->generateCode();
        $codeHash = password_hash((string)$code, PASSWORD_DEFAULT);
        $ttlSeconds = self::$ttlSeconds;
        $expiresAt = (new DateTime("+$ttlSeconds seconds"))->format('Y-m-d H:i:s');

        $insertedId = $this->model->insert([
            'user_identifier' => $userIdentifier,
            'code_hash' => $codeHash,
            'purpose' => $purpose->value,
            'channel' => $channel->value,
            'status' => pb2bStatus::ACTIVE->value,
            'attempts' => 0,
            'max_attempts' => self::$maxAttempts,
            'expires_datetime' => $expiresAt,
            'ip_address' => $user_ip,
            'user_agent' => $user_agent,
        ]);

        if (!$insertedId){
            $this->last_error = 'Ошибка отправки кода';
            return null;
        }

        $sent = $this->sendMessage($code, $channel->value);
        if (!$sent) {
            $this->last_error = 'Ошибка отправки кода';
            $this->model->updateById($insertedId, ['status' => pb2bStatus::REVOKED->value]);
            return null;
        }

        return $insertedId;
    }

    public function resend(string $token): ?int
    {
        $record = $this->getByToken($token);
        if (empty($record)){
            $this->last_error = 'Неверифицированный код';
            return null;
        }

        return $this->send($record['user_identifier'], pb2bChannel::from($record['channel']), pb2bPurpose::from($record['purpose']));
    }

    public function getByToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$payload, $signature] = $parts;
        if (!hash_equals(hash_hmac('sha256', $payload, $this->app_key), $signature)){
            return null;
        }

        $data = json_decode(base64_decode($payload), true);
        if (!is_array($data) || !isset($data['id'])){
            return null;
        }

        return $this->model->getById($data['id']) ?? null;
    }

    public function verifyByToken(string $token, string $code): bool
    {
        $record = $this->getByToken($token);
        if (empty($record)){
            $this->last_error = 'Неверифицированный код';
            return false;
        }

        return $this->verify($record, $code);
    }

    public function generateToken(int $id): string
    {
        $payload = base64_encode(json_encode(['id' => $id]));
        $signature = hash_hmac('sha256', $payload, $this->app_key);
        return $payload . '.' . $signature;
    }

    public static function getMaxAttempts(): int
    {
        return self::$maxAttempts;
    }

    public static function getRateSeconds(): int
    {
        return self::$rateSeconds;
    }

    public static function getTtlSeconds(): int
    {
        return self::$ttlSeconds;
    }
}
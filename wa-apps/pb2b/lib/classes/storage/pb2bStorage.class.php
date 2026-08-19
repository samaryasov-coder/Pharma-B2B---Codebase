<?php
class pb2bStorage
{
    protected static array $disks = [];
    protected static bool $initialized = false;

    private static function init(): void
    {
        if (self::$initialized)
            return;

        $config = require wa()->getAppPath('lib/config/filesystems.php');

        foreach ($config as $name => $diskConfig) {
            $driver = $diskConfig['driver'] ?? null;
            switch ($driver) {
                case 'local':
                    self::$disks[$name] = new pb2bFileDriver($diskConfig['root'], $diskConfig['url'] ?? null);
                    break;

                default:
                    throw new Exception("Неизвестный файловый драйвер {$driver}");
            }
        }

        self::$initialized = true;
    }


    public static function disk(string $storage_disk): pb2bFileSystemInterface
    {
        self::init();

        if (!isset(self::$disks[$storage_disk]))
            throw new Exception("Storage disk {$storage_disk} не найден");

        return self::$disks[$storage_disk];
    }
}

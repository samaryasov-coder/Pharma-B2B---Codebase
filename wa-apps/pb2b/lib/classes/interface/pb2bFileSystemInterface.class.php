<?php
interface pb2bFileSystemInterface
{

    /**
     * Проверяет, существует ли файл.
     *
     * @param string $path
     * @return bool
     */
    public function exists($path);

    /**
     * Получает содержимое файла.
     *
     * @param string $path
     * @return string|null
     */
    public function get($path);

    /**
     * Получает ресурс для чтения файла.
     *
     * @param string $path
     * @return resource|null Ресурс файла или null при ошибке.
     */
    public function readStream($path);

    /**
     * Записывает содержимое в файл.
     *
     * @param string $path
     * @param ploadedFile|string|resource $contents
     * @param mixed $options
     * @return bool
     */
    public function put($path, $contents, $options = []);

    /**
     * Записывает новый файл с использованием потока.
     *
     * @param string $path
     * @param resource $resource
     * @param array $options
     * @return bool
     */
    public function writeStream($path, $resource, array $options = []);


    /**
     * Добавляет данные в начало файла.
     *
     * @param string $path
     * @param string $data
     * @return bool
     */
    public function prepend($path, $data);

    /**
     * Добавляет данные в конец файла.
     *
     * @param string $path
     * @param string $data
     * @return bool
     */
    public function append($path, $data);

    /**
     * Удаляет файл(ы) по указанному пути.
     *
     * @param string|array $paths
     * @return bool
     */
    public function delete($paths);

    /**
     * Копирует файл в новое место.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function copy($from, $to);

    /**
     * Перемещает файл в новое место.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move($from, $to);

    /**
     * Получает размер файла.
     *
     * @param string $path
     * @return int
     */
    public function size($path);

    /**
     * Получает время последней модификации файла.
     *
     * @param string $path
     * @return int
     */
    public function lastModified($path);

    /**
     * Получает массив всех файлов в директории.
     *
     * @param null $directory
     * @param bool $recursive
     * @param callable|null $filter
     * @return array
     */
    public function files($directory = null, $recursive = false, callable $filter = null);


    /**
     * Получает все директории внутри указанной директории.
     *
     * @param string|null $directory
     * @param bool $recursive
     * @return array
     */
    public function directories($directory = null, $recursive = false);


    /**
     * Создаёт директорию.
     *
     * @param string $path
     * @return bool
     */
    public function makeDirectory($path);

    /**
     * Рекурсивно удаляет директорию.
     *
     * @param string $directory
     * @return bool
     */
    public function deleteDirectory($directory);
}


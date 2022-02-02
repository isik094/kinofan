<?php
namespace common\helpers;


use yii\base\Exception;

class FileHelper
{
    /**
     * @brief Выгружает/открывает документ пользователю
     * @param string $path
     * @param string $filename
     * @param string $contentDisposition
     * @throws \Exception
     */
    public static function downloadFile($path, $filename, $contentDisposition = 'inline')
    {
        if (!file_exists($path)) {
            throw new \Exception('File ' . $path . ' not found');
        }
        $FileMime = mime_content_type($path);

        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        if (ob_get_level()) {
            ob_end_clean();
        }
        header("Content-Description: File Transfer");
        header("Content-Type: $FileMime");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($path));
        header("Content-Disposition: $contentDisposition; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        readfile($path);
        exit();
    }
}
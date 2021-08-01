<?php
namespace api\components;


class ApiResponse
{
    public $error;
    public $data;
    public $status;

    /**
     * @inheritdoc
     * @param bool $error
     * @param array|object|string|int|bool $data
     * @param int|null $status
     * @throws \Exception
     */
    public function __construct($error, $data, $status = null)
    {
        if (!is_bool($error)) {
            throw new \Exception('error must been boolean');
        }

        if ($status && !is_int($status)) {
            throw new \Exception('status must been integer');
        }

        $this->error = $error;
        $this->data = $data;
        $this->status = $status;

        if (!$this->status) {
            $this->status = $error ? 406 : \Yii::$app->response->statusCode;
        }
    }
}
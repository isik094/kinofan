<?php
namespace api\components;


use common\models\ErrorLog;

class ApiResponseException
{
    public $error;
    public $data;
    public $status;

    /**
     * @inheritdoc
     * @param \Exception $e
     */
    public function __construct(\Exception $e)
    {
        ErrorLog::createLog($e);
        if (property_exists($e, 'statusCode')) {
            $status = $e->statusCode;
        } else {
            $status = 500;
        }

        $this->error = true;
        $this->data = $e->getMessage();
        $this->status = $status;
    }
}
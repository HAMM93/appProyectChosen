<?php

namespace App\Services\Logging;

use App\Models\Logging;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class Log
{
    private $uuid;
    private $data;
    private $level;

    public function __construct()
    {
        $this->getGenerateErrorCode();
    }

    /**
     * INFO should be used to log any information
     * @param $error - (Throwable || Array['message' => string, 'error_code' => int])
     * @return string
     */
    public function info($error): string
    {
        $this->store($error, __FUNCTION__);

        return $this->uuid;
    }

    /**
     * WARNING should be used to log problems with rules of product
     * @param $error - (Throwable || Array['message' => string, 'error_code' => int])
     * @return string
     */
    public function warning($error): string
    {
        $this->store($error, __FUNCTION__);

        return $this->uuid;
    }

    /**
     * SECURITY should be used to log any behavior threats security
     * @param $error - (Throwable || Array['message' => string, 'error_code' => int])
     * @return mixed
     */
    public function security($error)
    {
        $this->store($error, __FUNCTION__);
        $this->slack(__FUNCTION__);

        return $this->uuid;
    }

    /**
     * CRITICAL should be used to log any error of PHP
     * @param $error - (Throwable || Array['message' => string, 'error_code' => int])
     * @return mixed
     */
    public function critical($error)
    {
        $this->store($error, __FUNCTION__);
        $this->slack(__FUNCTION__);

        return $this->uuid;
    }

    /**
     * @param string $level
     */
    private function slack(string $level): void
    {
        if (config('app.env') !== 'production') return;

        $text = sprintf(
            'Level: %s | UUID: %s',
            strtoupper($level),
            $this->uuid
        );

        Http::withHeaders(['Content-Type' => 'application/json'])
            ->post(config('mdb-logging.channels.slack.url'), [
                'text' => $text
            ]);
    }

    /**
     *
     */
    private function getGenerateErrorCode(): void
    {
        $this->uuid = Str::uuid();
    }

    /**
     * @param $error
     * @param string $level
     */
    private function store($error, string $level)
    {
        $this->level = $level;

        if ($error instanceof \Exception)
            $this->getDataFromException($error);
        else
            $this->getDataFromAdditionalArguments($error);

        $model = new Logging();
        $model->level = $this->level;
        $model->uuid = $this->data['uuid'];
        $model->message = substr($this->data['message'], 0, 254);
        $model->error_code = $this->data['error_code'];
        $model->trace = json_encode($this->data['trace']);
        $model->additional = $this->data['additional'];
        $model->save();
    }

    /**
     * @param Throwable $e
     */
    private function getDataFromException(Throwable $e)
    {
        $this->data = [
            'level' => $this->level,
            'uuid' => "$this->uuid",
            'message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'trace' => $e->getTrace(),
            'additional' => '...'
        ];
    }

    /**
     * @param array $arguments
     */
    private function getDataFromAdditionalArguments(array $arguments)
    {
        $this->data = [
            'level' => $this->level,
            'uuid' => "$this->uuid",
            'message' => $arguments['message'] ?? '...',
            'error_code' => $arguments['error_code'] ?? 0,
            'trace' => [],
            'additional' => '...'
        ];
    }
}

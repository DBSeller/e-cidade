<?php

namespace ECidade\V3\Error\Handler;

use Whoops\Handler\Handler;
use Whoops\Handler\PlainTextHandler;

/**
 * Class LogFileHandler
 * @package ECidade\V3\Error\Handler
 */
class LogFileHandler extends PlainTextHandler
{
    /**
     * @return int
     */
    public function handle()
    {
        $response = $this->generateResponse();

        if ($this->getLogger()) {
            $this->getLogger()->error($response);
        }

        file_put_contents('extension/log/error.log', "$response\n", FILE_APPEND);

        return Handler::DONE;
    }
}

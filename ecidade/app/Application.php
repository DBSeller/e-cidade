<?php

namespace App;

/**
 * Class Application
 * @package App
 */
class Application extends \Illuminate\Foundation\Application
{
    /**
     * @param string $path
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'configuration' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

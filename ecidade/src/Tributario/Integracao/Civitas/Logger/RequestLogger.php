<?php

namespace ECidade\Tributario\Integracao\Civitas\Logger;

/**
 * Class RequestLogger Responsavel por gerar logs da reuisicão, esse log e gerado em arquivo
 *
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 * @package ECidade\Tributario\Integracao\Civitas\Logger
 */
class RequestLogger
{

    /** @var EMERGENCY */
    const EMERGENCY = 'emergency';

    /** @var ALERT */
    const ALERT = 'alert';

    /** @var CRITICAL */
    const CRITICAL = 'critical';

    /** @var ERROR */
    const ERROR = 'error';

    /** @var WARNING */
    const WARNING = 'warning';

    /** @var NOTICE */
    const NOTICE = 'notice';

    /** @var INFO */
    const INFO = 'info';

    /** @var DEBUG */
    const DEBUG = 'debug';

    /** @var PATH_LOG string */
    const  PATH_LOG = 'tmp';

    /** @var $identifier string */
    private static $identifier;

    /** @var $messages mixed */
    private static $messages;

    /** @var $level string */
    private static $level;

    /** @var $logName string */
    private static $logName;

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function emergency($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function alert($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function critical($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function error($message, array $context = array())
    {

        self::log($message, $context);

    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function warning($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function info($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function debug($message, array $context = array())
    {
        self::log($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function log($level, $logName, $identifier = '', $messages, array $context = array())
    {

        if (!empty($context) && !empty($messages)) {
            $context = self::multidimensionalArrayMap(function ($value) {
                return addslashes($value);
            }, $context);

            $messages = self::interpolate($messages, $context);
        }

        if (empty($messages) && !empty($context)) {
            $messages = $context;
        }

        self::$level = $level;
        self::$logName = $logName;
        self::$messages = $messages;
        self::$identifier = (!empty($identifier) ? $identifier : 'empty');

        $container = self::createPackage(true);

        self::persist($container);
    }

    /**
     * Interpolate
     *
     * @param $message
     * @param array $context
     * @return string
     */
    public static function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }


    /**
     * Cria pacote com todas informa??es referente ao log
     * @param bool $to_json true caso deseje retorno em formato json ou false caso deseje retorno em array
     * @return array|string retorna array ou json
     */
    private static function createPackage($json = false)
    {
        $package = array(
            'level' => self::$level,
            'host' => self::getUrl(),
            'logName' => self::$logName,
            'identifier' => self::$identifier,
            'messages' => (is_string(self::$messages) ? htmlentities(self::$messages) : self::$messages)
        );

        if ($json) {
            return self::encodePackage($package);
        }

        return $package;
    }

    /**
     * multidimensionalArrayMap
     *
     * @param $func
     * @param $arr
     * @return array
     */
    public static function multidimensionalArrayMap($func, $arr)
    {
        $newArr = array();
        foreach ($arr as $key => $value) {
            $newArr[$key] = (is_array($value) ? multidimensionalArrayMap($func, $value) : $func($value));
        }

        return $newArr;
    }

    public static function getTypeEncode()
    {
        return 'default';
    }

    /**
     * Encoda um array para json
     * @param array $package
     * @return string json do $package enviado
     */
    public static function encodePackage($package)
    {
        switch (self::getTypeEncode()) {
            case 'json':

                $encoded_package = json_encode($package);

                if ($encoded_package == false) {
                    $package['messages'] = addslashes($package['messages']);
                    $encoded_package = json_encode($package);
                }

                break;
            default:
                $encoded_package = is_array($package) ? http_build_query($package) : $package;
                break;
        }

        return $encoded_package;
    }


    /**
     *  envia
     * @param string $container json com logs a serem enviados
     */
    private static function persist($container)
    {
        $fulltoday = date("Y-m-d H:i:s");
        $today = date("Y-m-d");

        $strfile = "$fulltoday |IP: " . self::getIp() . "|" . self::$level . "|$container \n";
        $file = self::PATH_LOG . DIRECTORY_SEPARATOR . self::$logName . '_' . $today . '.log';
        file_put_contents($file, $strfile, FILE_APPEND);

    }

    /**
     * Retorna a URL do servidor
     *
     * @return string
     */
    public static function getUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $port = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] ? ":" . $_SERVER['SERVER_PORT'] : "";
        return sprintf("%s://%s%s%s", $protocol, $_SERVER['SERVER_NAME'], $port, ECIDADE_REQUEST_ROOT);
    }

    /**
     * Retorna o IP da Solicitação
     *
     * @return mixed
     */
    public static function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}
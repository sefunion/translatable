<?php

declare(strict_types=1);

namespace Easy\Translatable\Exception;

use Throwable;

class LocalesNotDefinedException extends \Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $message = empty($message)
            ? 'Please make sure you have run `php bin/hyperf.php vendor:publish sef/translatable` and that the locales configuration is defined.'
            : $message;
        parent::__construct($message, $code, $previous);
    }
}

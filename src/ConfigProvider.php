<?php

declare(strict_types=1);

namespace Easy\Translatable;

use Easy\Translatable\Contracts\LocalesInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                LocalesInterface::class => Locales::class,
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for sef/translatable.',
                    'source' => __DIR__ . '/../publish/translatable.php',
                    'destination' => BASE_PATH . '/config/autoload/translatable.php',
                ],
            ],
        ];
    }
}

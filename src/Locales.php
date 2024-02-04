<?php

declare(strict_types=1);

namespace Easy\Translatable;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\TranslatorInterface;
use Easy\Translatable\Contracts\LocalesInterface;
use Easy\Translatable\Exception\LocalesNotDefinedException;

class Locales implements LocalesInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var \Hyperf\Contract\TranslatorInterface
     */
    protected $translator;

    public function __construct(ConfigInterface $config, TranslatorInterface $translator)
    {
        $this->config = $config->get('translatable');
        $this->translator = $translator;

        $this->load();
    }

    public function add(string $locale): void
    {
        $this->locales[$locale] = $locale;
    }

    public function all(): array
    {
        return array_values($this->locales);
    }

    public function current(): string
    {
        return $this->config['locale'] ?? $this->translator->getLocale();
    }

    public function forget(string $locale): void
    {
        unset($this->locales[$locale]);
    }

    public function get(string $locale): ?string
    {
        return $this->locales[$locale] ?? null;
    }

    public function getCountryLocale(string $locale, string $country): string
    {
        return $locale . $this->getLocaleSeparator() . $country;
    }

    public function getLanguageFromCountryBasedLocale(string $locale): string
    {
        return explode($this->getLocaleSeparator(), $locale)[0];
    }

    public function getLocaleSeparator(): string
    {
        return $this->config['locale_separator'] ?? '-';
    }

    public function has(string $locale): bool
    {
        return isset($this->locales[$locale]);
    }

    public function isLocaleCountryBased(string $locale): bool
    {
        return strpos($locale, $this->getLocaleSeparator()) !== false;
    }

    public function offsetExists($key): bool
    {
        return $this->has($key);
    }

    public function offsetGet($key): ?string
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value): void
    {
        if (is_string($key) && is_string($value)) {
            $this->add($this->getCountryLocale($key, $value));
        } elseif (is_string($value)) {
            $this->add($value);
        }
    }

    public function offsetUnset($key): void
    {
        $this->forget($key);
    }

    public function toArray(): array
    {
        return $this->all();
    }

    protected function load(): void
    {
        $localesConfig = (array) ($this->config['locales'] ?? []);

        if (empty($localesConfig)) {
            throw new LocalesNotDefinedException();
        }

        $this->locales = [];
        foreach ($localesConfig as $key => $locale) {
            if (is_string($key) && is_array($locale)) {
                $this->locales[$key] = $key;

                foreach ($locale as $country) {
                    $countryLocale = $this->getCountryLocale($key, $country);
                    $this->locales[$countryLocale] = $countryLocale;
                }
            } elseif (is_string($locale)) {
                $this->locales[$locale] = $locale;
            }
        }
    }
}

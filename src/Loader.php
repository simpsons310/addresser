<?php

namespace Addresser;

use Addresser\AddresserException;

class Loader
{
    public const DEFAULT_LOCALE = 'vi_VN';

    protected $data;

    public function __construct($locale = null)
    {
        $this->loadData($locale);
    }

    public function loadData($locale = null)
    {
        $locale = $locale ?? static::DEFAULT_LOCALE;
        $path = __DIR__ . '/../data/' . $locale . '.json';

        if (!file_exists($path)) {
            throw new AddresserException('Address file ' . $path . ' not found.');
        }

        $this->data = json_decode(file_get_contents($path), true);

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}

<?php

namespace Cmixin\BusinessDay\Util;

class FileConfig
{
    /** @var string */
    protected $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function useOn($mixin, string $region): void
    {
        $mixin->holidays[$region] = include $this->file;
        $mixin->workdays[$region] = $mixin->holidays[$region]['__without'] ?? [];
        unset($mixin->holidays[$region]['__without']);
    }

    public static function use(string $file, $mixin, string $region): void
    {
        (new static($file))->useOn($mixin, $region);
    }
}

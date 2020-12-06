<?php

namespace Cmixin\BusinessDay\Util;

use Cmixin\BusinessDay\MixinBase;

final class Context
{
    public static function isNotMixin($context, $mixin): bool
    {
        return $context !== $mixin && !$context instanceof MixinBase;
    }
}

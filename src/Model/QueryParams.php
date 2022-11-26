<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

abstract class QueryParams
{
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), static function ($v) {
            if (null === $v) {
                return false;
            }

            if (\is_array($v) && empty($v)) {
                return false;
            }

            return true;
        });
    }
}

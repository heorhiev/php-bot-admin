<?php

namespace app\traits;

trait EnumDisplayNameTrait
{
    abstract public function displayName(): string;

    public static function getOptions(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->displayName();
        }

        return $options;
    }
}
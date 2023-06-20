<?php
declare(strict_types=1);

namespace App\Utils;


use Illuminate\Support\Collection;
use IntBackedEnum;

class EnumBitmapEncoder
{
    /**
     * @param IntBackedEnum[]|null $items
     * @return int|null
     */
    public static function encode(?array $items): ?int
    {
        if ($items === null || count($items) === 0) {
            return null;
        }

        $result = 0;

        foreach ($items as $item) {
            $result |= $item->value;
        }

        return $result === 0 ? null : $result;
    }


    /**
     * @param IntBackedEnum[] $enumValues
     * @param int|null $value
     * @return Collection<int, int>
     */
    public static function decode(array $enumValues, ?int $value): Collection
    {
        if ($value === null) {
            return new Collection();
        }

        $result = new Collection();

        foreach ($enumValues as $enumValue) {
            if ($value & $enumValue->value) {
                $result->add($enumValue->value);
            }
        }

        return $result;

    }

}

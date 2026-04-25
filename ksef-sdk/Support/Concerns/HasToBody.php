<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Support\Concerns;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Support\Str;

trait HasToBody
{
    use HasToArray;

    /**
     * @return array<string, mixed>
     */
    public function toBody(): array
    {
        $toArray = $this->toArray();

        $newArray = [];

        foreach (get_object_vars($this) as $key => $value) {
            $name = Str::camel($key);

            if ($value instanceof BodyInterface) {
                $newArray[$name] = $value->toBody();
            }
        }

        /** @var array<string, mixed> */
        return array_merge($toArray, $newArray);
    }
}

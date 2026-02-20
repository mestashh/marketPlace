<?php

namespace App\Http\Requests\User;

trait MergeFullName
{
    protected function mergeFullNameFromParts(
        string $last = 'last_name',
        string $first = 'first_name',
        string $middle = 'middle_name',
        string $target = 'name'
    ): void {
        $parts = [
            $this->input($last),
            $this->input($first),
            $this->input($middle),
        ];

        $parts = array_map(fn ($v) => is_string($v) ? trim($v) : null, $parts);
        $parts = array_values(array_filter($parts, fn ($v) => $v !== null && $v !== ''));
        $fullName = trim(preg_replace('/\s+/u', ' ', implode(' ', $parts)));

        if ($fullName !== '') {
            $this->merge([$target => $fullName]);
        }
    }
}

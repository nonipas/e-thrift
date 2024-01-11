<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class dataExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }
}

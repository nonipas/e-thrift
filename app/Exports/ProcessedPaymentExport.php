<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ProcessedPaymentExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    protected $batch_id;

    public function __construct(int $batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function query()
    {
        return Payment::where('batch_id', $this->batch_id)->where('is_processed', 1);
    }
}

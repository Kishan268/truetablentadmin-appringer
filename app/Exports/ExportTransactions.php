<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Companies;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTransactions implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($data) {
        $this->data = $data;
 	}

    public function collection()
    {
        
        return collect($this->data);

    }

    public function headings(): array
    {
       return [
         'Candidate ID',
         'Detail',
         'Date',
         'Transaction By',
         'Amount',
         'Balance'
       ];
    }
}

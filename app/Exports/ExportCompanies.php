<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Auth\User;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportCompanies implements FromCollection, WithHeadings
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
            'Name',
            'Member Count',
            'Job Count',
            'Gig Count',
            'Size',
            'Locations',
            'Domains',
            'TT Cash',
            'Created On'
       ];
    }
}

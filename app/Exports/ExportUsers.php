<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Auth\User;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUsers implements FromCollection, WithHeadings
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
            'Email',
            'Phone',
            'Overall Experience',
            'Date of Registration',
            'Location',
            'TT Profile Completion(%)',
            'Added From',
            'Last Updated'
       ];
    }
}

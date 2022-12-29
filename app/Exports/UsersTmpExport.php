<?php

namespace App\Exports;

use App\Models\UserTmp;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersTmpExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;
    protected $batch_id;

    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function headings(): array
    {
        return [
            'Username',
            'Name',
            'Password',
            'Batch Id'
        ];
    }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function view(): View
    // {
    //     return view('cetak.usersTable', [
    //         'users' => UserTmp::where('batch_id', $this->batch_id)->get(['username', 'name', 'password', 'batch_id']),
    //         'batch_id' => $this->batch_id
    //     ]);      
    // }

    public function collection()
    {
        return UserTmp::where('batch_id', $this->batch_id)->get(['username', 'name', 'password', 'batch_id']);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

}

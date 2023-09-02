<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $search;
    protected $selected_rows;
    protected $office_id;
    private $index;

    function __construct($search,$selectedRows,$office_id) {
        $this->search = $search;
        $this->selected_rows = $selectedRows;
        $this->office_id = $office_id;
    }

    public function collection()
    {
        if ($this->selected_rows) {
            return User::whereIn('id', $this->selected_rows)->orderBy('name', 'asc')
            ->get();
        } else {
            return User::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->where('office_id', $this->office_id)
            ->orderBy('name', 'asc')
            ->get();
        }
    }

    public function map($user) : array {

        $this->index = $this->index + 1;

        return [
            $user->id ? $this->index : '',
            $user->name,
            $user->email,
            $user->mobile,
            $user->specialization->name,
            $user->job_type->name,
            $user->section_type->name,
            $user->office->name,
            $user->email_verified_at ? 'موثق' : 'غير موثق',
            $user->status ? 'مفعل' : 'غير مفعل',
        ] ;
    }

    public function headings(): array
    {
        return [
            'م',
            'الاسم',
            'البريد الإلكتروني',
            'الجوال',
            'التخصص',
            'العمل الحالي',
            'المرجع الإداري',
            'الإدارة / مكتب التعليم',
            'توثيق البريد الإلكتروني',
            'الحالة',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
            ],

            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:J1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray(
                    array(
                       'font'  => array(
                           'bold'  =>  true,
                           'color' => ['rgb' => '219b00'],
                       )
                    )
                  );
            },
        ];
    }
}

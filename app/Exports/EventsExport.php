<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Event;
use Alkoumi\LaravelHijriDate\Hijri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $search;
    protected $selected_rows;
    protected $byWeek;
    protected $bySectionType;
    protected $byOffice;
    private $index;

    function __construct($search,$selectedRows,$byWeek,$byEduType,$byOffice) {
        $this->search = $search;
        $this->selected_rows = $selectedRows;
        $this->byWeek = $byWeek;
        $this->bySectionType = $byEduType;
        $this->byOffice = $byOffice;
    }

    public function collection()
    {
        $week =$this->byWeek;
        $bySectionType =$this->bySectionType;
        $office =$this->byOffice ? $this->byOffice : auth()->user()->office_id;

        if ($this->selected_rows) {
            return Event::whereIn('id', $this->selected_rows)
                ->where('office_id', $office)->when($week, function($query) use ($week){
                    $query->where('week_id', $week);
                })->when($bySectionType, function ($query) use($bySectionType) {
                    $query->whereHas('user', function ($q) use($bySectionType) {
                        $q->where('section_type_id', $bySectionType);
                    });
                })
                ->search(trim(($this->search)))
                //->latest('created_at')
                ->orderBy('start', 'asc')->get();
        } else {
            return Event::query()
            ->where('office_id', $office)->when($this->byWeek, function($query) use ($week){
                $query->where('week_id', $week);
            })->when($bySectionType, function ($query) use($bySectionType) {
                $query->whereHas('user', function ($q) use($bySectionType) {
                    $q->where('section_type_id', $bySectionType);
                });
            })
            ->search(trim(($this->search)))
            //->latest('created_at')
            ->orderBy('start', 'asc')->get();
        }
    }

    public function map($event) : array {

        $this->index = $this->index + 1;

        return [
            $event->id ? $this->index : '',
            $event->user->name,
            $event->user->specialization->name,
            $event->user->section_type->name,
            $event->task->name,
            Hijri::Date('l', $event->start),
            Hijri::Date('Y-m-d', $event->start),
            $event->start,
            $event->semester->name,
            $event->week->name,
            $event->week->semester->school_year,
            $event->office->name,
            $event->status ? 'معتمدة' : 'غير معتمدة',
            $event->task_done ? 'منفدة' : 'غير منفذة',
        ] ;
    }

    public function headings(): array
    {
        return [
            'م',
            'الاسم',
            'التخصص',
            'المرجع الإداري',
            'المهمة / المدرسة',
            'اليوم',
            'هجري',
            'ميلادي',
            'الفصل الدراسي',
            'الاسبوع',
            'العام الدراسي',
            'الادارة / مكتب التعليم',
            'الحالة',
            'حالة التنفيذ',
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
                $cellRange = 'A1:N1'; // All headers
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

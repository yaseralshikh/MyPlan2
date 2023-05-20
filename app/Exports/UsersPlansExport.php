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

class UsersPlansExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $semester_id;
    protected $office_id;
    private $index;

    function __construct($semester_id,$office_id) {
        $this->semester_id = $semester_id;
        $this->office_id = $office_id;
    }

    public function collection()
    {
        $bySemester = $this->semester_id;

        $users = User::whereStatus(true)->where('office_id', $this->office_id)->with([
            'events' => function ($query) use($bySemester) {
                $query->where('semester_id', $bySemester);
            }
        ])
        ->orderBy('name', 'asc')
        ->get();

        return $users;
    }

    public function map($user) : array {

        $this->index = $this->index + 1;

        return [
            $user->id ? $this->index : '',
            $user->name,
            $user->email,
            $user->specialization->name,
            $user->job_type->name,
            $user->office->name,
            $user->events->where('status', true)->where('task_done', true)->whereNotIn('task.name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة'])->count() ? $user->events->where('status', true)->where('task_done', true)->whereNotIn('task.name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة'])->count() : '0',
            $user->events->where('status', true)->where('task_done', true)->where('task.name','يوم مكتبي' )->count() ? $user->events->where('status', true)->where('task_done', true)->where('task.name','يوم مكتبي' )->count() : '0',
            $user->events->where('status', true)->where('task_done', true)->where('task.name','برنامج تدريبي' )->count() ? $user->events->where('status', true)->where('task_done', true)->where('task.name','برنامج تدريبي' )->count() : '0',
            $user->events->where('status', true)->where('task_done', true)->where('task.name','مكلف بمهمة' )->count() ? $user->events->where('status', true)->where('task_done', true)->where('task.name','مكلف بمهمة' )->count() : '0',
            $user->events->where('status', true)->where('task_done', true)->where('task.name','إجازة' )->count() ? $user->events->where('status', true)->where('task_done', true)->where('task.name','إجازة' )->count() : '0',
            $user->events->where('status', true)->where('task_done', true)->count() ? $user->events->where('status', true)->where('task_done', true)->count() : '0',
            $user->events->where('status', false)->count() ? $user->events->where('status', false)->count() : '0',
            $user->events->where('task_done', false)->count() ? $user->events->where('task_done', false)->count() : '0',
        ] ;
    }

    public function headings(): array
    {
        return [
            'م',
            'الاسم',
            'البريد الالكتروني',
            'ألتخصص',
            'العمل الحالي',
            'الإدارة / مكتب التعليم',
            'زيارات مدارس',
            'ايام مكتبية',
            'برامج تدريبية',
            'مكلف بمهمة',
            'إجازة',
            'مجموع الخطط',
            'الخطط الغير معتمدة',
            'الخطط الغير منفذة',
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

<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmptyTasksExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $semester_id;
    protected $office_id;
    private $index;

    public function __construct($semester_id, $office_id)
    {
        $this->semester_id = $semester_id;
        $this->office_id = $office_id;
    }

    public function collection()
    {
        $bySemester = $this->semester_id;

        $tasks = Task::whereStatus(true)->where('office_id', $this->office_id)->whereIn('level_id', [1, 2, 3, 4, 5, 6])
            ->withCount([
                'events' => function ($query) use ($bySemester) {
                    $query->where('semester_id', $bySemester)
                            ->where('office_id', $this->office_id)
                            ->where('task_done' , true);
                },
            ])
            ->having('events_count', '=', 0)
            ->orderBy('name', 'asc')
            ->orderBy('level_id', 'asc')
            ->get();

        return $tasks;
    }

    public function map($task): array
    {

        $this->index = $this->index + 1;

        return [
            $task->id ? $this->index : '',
            $task->name,
            $task->level->name,
            $task->events_count ? '' : '0',
        ];
    }

    public function headings(): array
    {
        return [
            'م',
            'اسم المدرسة',
            'المرحلة',
            'عدد الزيارات',
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
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray(
                    array(
                        'font' => array(
                            'bold' => true,
                            'color' => ['rgb' => '219b00'],
                        ),
                    )
                );
            },
        ];
    }
}

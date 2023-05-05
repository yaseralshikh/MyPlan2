<?php

namespace App\Http\Livewire\Backend\Dashboard;

use App\Models\Task;
use App\Models\User;
use App\Models\Week;
use App\Models\Event;
use App\Models\Level;
use App\Models\Office;
use Livewire\Component;
use App\Models\Semester;
use Livewire\WithPagination;
use App\Exports\EmptyTasksExport;
use App\Exports\UsersPlansExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use PDF;

class Dashboard extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';


    public $byOffice = null; // filter bt Office
    public $bySemester = null; // filter bt Semester
    public $byLevel = 2; // filter bt Task Level

    public $chartData = [];

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $emptySchoolSearchString = null;
    protected $emptySchoolQueryString = ['emptySchoolSearchString' => ['except' => '']];

    public $paginateValue = 20;
    public $emptySchoolsPaginateValue = 50;

    public function semesterActive()
    {
        $semester_active = Semester::whereActive(1)->first();
        return $semester_active->id;
    }

    public function emptySchoolsExportExcel()
    {
        $bySemester = $this->bySemester ? $this->bySemester : $this->semesterActive();
        return Excel::download(new EmptyTasksExport($bySemester,$this->byOffice ? $this->byOffice : auth()->user()->office_id), 'tasks.xlsx');
    }

    public function emptySchoolsExportPDF()
    {
        try {

            $bySemester = $this->bySemester  ? $this->bySemester : $this->semesterActive();

            $semester = Semester::findOrFail($bySemester);

            $tasks = Task::whereStatus(true)->where('office_id' , $this->byOffice ? $this->byOffice : auth()->user()->office_id)->whereIn('level_id', [1,2,3,4,5,6])
            ->withCount([
                'events' => function ($query) use($bySemester) {
                    $query->where('semester_id', $bySemester);
                }
            ])
            ->having('events_count', '=', 0)
            ->orderBy('name', 'asc')
            ->orderBy('level_id', 'asc')
            ->get();

            if ($tasks->count() <> 0) {

                return response()->streamDownload(function() use($tasks, $semester ){

                    $pdf = PDF::loadView('livewire.backend.dashboard.emptySchools_pdf',[
                        'tasks' => $tasks,
                        'semester' => $semester,
                    ]);

                    return $pdf->stream('tasks');

                },'tasks.pdf');

            } else {

                $this->alert('error', __('site.noDataFound'), [
                    'position'  =>  'center',
                    'timer'  =>  3000,
                    'toast'  =>  true,
                    'text'  =>  null,
                    'showCancelButton'  =>  false,
                    'showConfirmButton'  =>  false
                ]);
            }

        } catch (\Throwable $th) {
            $message = $this->alert('error', $th->getMessage(), [
                'position'  =>  'top-end',
                'timer'  =>  3000,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);

            return $message;
        }
    }

    public function UsersPlansExportExcel()
    {
        $bySemester = $this->bySemester ? $this->bySemester : $this->semesterActive();
        return Excel::download(new UsersPlansExport($bySemester,$this->byOffice ? $this->byOffice : auth()->user()->office_id), 'users.xlsx');
    }

    public function UsersPlansExportPDF()
    {
        try {

            $bySemester = $this->bySemester  ? $this->bySemester : $this->semesterActive();

            $semester = Semester::findOrFail($bySemester);

            $users = User::whereStatus(true)->with([
                'events' => function ($query) use($bySemester) {
                    $query->where('semester_id', $bySemester)->whereStatus(true)->where('task_done', true);
                }
            ])
            ->where('office_id' , $this->byOffice ? $this->byOffice : auth()->user()->office_id)
            ->orderBy('name', 'asc')
            ->get();

            if ($users->count() <> 0) {

                return response()->streamDownload(function() use($users, $semester){

                    $pdf = PDF::loadView('livewire.backend.dashboard.usersPlans_pdf',[
                        'users' => $users ,
                        'semester' => $semester ,
                    ]);

                    return $pdf->stream('users');

                },'users.pdf');

            } else {

                $this->alert('error', __('site.noDataFound'), [
                    'position'  =>  'center',
                    'timer'  =>  3000,
                    'toast'  =>  true,
                    'text'  =>  null,
                    'showCancelButton'  =>  false,
                    'showConfirmButton'  =>  false
                ]);
            }

        } catch (\Throwable $th) {

            $message = $this->alert('error', $th->getMessage(), [
                'position'  =>  'top-end',
                'timer'  =>  3000,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);

            return $message;
        }
    }

    public function getChartData()
    {
        $byOffice = $this->byOffice ? $this->byOffice : auth()->user()->office_id;
        $bySemester = $this->bySemester ? $this->bySemester : $this->semesterActive();
        $byLevel = $this->byLevel;

        $this->chartData = Event::whereStatus(1)
        ->where('task_done', true)
        ->where('office_id', $byOffice)
        ->with('task:id,name,level_id,need_care')
        ->whereHas('task', function ($q) {
            $q->whereNotIn('name',['إجازة']);
        })
        ->selectRaw('count(*) as count, task_id')
        ->groupBy('task_id')
        ->where('semester_id', $bySemester)
        ->get()
        ->where('task.level_id', $byLevel)
        //->pluck('count','task.name')
        ->toArray();

        $chart_title=[];
        $chart_need_care=[];
        $chart_count=[];

        foreach ($this->chartData as $event) {
            array_push($chart_title, $event['task']['name']);
            array_push($chart_count, $event['count']);
            array_push($chart_need_care, $event['task']['need_care']);
        }

        $this->chartData = [$chart_title,$chart_count,$chart_need_care];

        return json_encode($this->chartData);
    }

    public function render()
    {
        // parameters values
        $searchString = $this->searchTerm;
        $emptySchoolSearchString = $this->emptySchoolSearchString;
        $paginateValue = $this->paginateValue;
        $emptySchoolsPaginateValue = $this->emptySchoolsPaginateValue;
        $byOffice = $this->byOffice ? $this->byOffice : auth()->user()->office_id;
        $bySemester = $this->bySemester ? $this->bySemester : $this->semesterActive();

        // for users plans
        $users = User::whereStatus(true)->where('office_id', $byOffice)->with([
            'events' => function ($query) use($bySemester) {
                $query->where('semester_id', $bySemester)->whereStatus(true)->Where('task_done' , 1);
            }
        ])->search(trim(($searchString)))
        ->orderBy('name', 'asc')
        ->paginate($paginateValue);

        // for chart
        $chartData = $this->getChartData();
        $this->dispatchBrowserEvent('refreshEventChart', ['refresh' => true , 'data' => $chartData]);

        // for counting dtat
        $usersCount = User::where('office_id', $byOffice)->whereStatus(1)->count();
        $eventsCount = Event::whereStatus(1)->where('office_id', $byOffice)->where('semester_id', $bySemester)->count();
        $weeksCount = Week::whereStatus(1)->where('semester_id', $bySemester)->count();
        $eventsSchoolCount = Event::whereStatus(1)->where('office_id', $byOffice)->where('semester_id', $bySemester)->whereHas('task', function ($q) {$q->whereNotIn('name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة']);})->count();
        $eventsOfficeCount = Event::whereStatus(1)->where('office_id', $byOffice)->where('semester_id', $bySemester)->whereHas('task', function ($q) {$q->where('name','يوم مكتبي' );})->count();
        $eventsTrainingCount = Event::whereStatus(1)->where('office_id', $byOffice)->where('semester_id', $bySemester)->whereHas('task', function ($q) {$q->where('name','برنامج تدريبي' );})->count();
        $eventsTaskCount = Event::whereStatus(1)->where('office_id', $byOffice)->where('semester_id', $bySemester)->whereHas('task', function ($q) {$q->where('name','مكلف بمهمة' );})->count();
        $schoolsCount = Task::where('office_id', $byOffice)->whereStatus(1)->whereNotIn('level_id',[6,7])->count();

        $offices = Office::whereStatus(true)->where('gender', auth()->user()->gender)->where('education_id', auth()->user()->office->education->id)->get();
        $semesters =  Semester::whereStatus(true)->get();
        $levels = Level::all();

        // for schools not visited by supervisors
        $empty_schools = Task::whereStatus(true)->where('office_id', $byOffice)->whereIn('level_id', [1,2,3,4,5,6])
        ->withCount([
            'events' => function ($query) use($bySemester) {
                $query->where('semester_id', $bySemester)->whereStatus(true)->Where('task_done', true);
            }
        ])
        ->having('events_count', '=', 0)
        ->search(trim(($emptySchoolSearchString)))
        ->orderBy('name', 'asc')
        ->orderBy('level_id', 'asc')
        ->paginate($emptySchoolsPaginateValue);

        return view('livewire.backend.dashboard.dashboard', [

            'offices'               => $offices,
            'semesters'             => $semesters,
            'levels'                => $levels,
            'empty_schools'         => $empty_schools,
            'chartData'             => $chartData,
            'usersCount'            => $usersCount,
            'schoolsCount'          => $schoolsCount,
            'users'                 => $users,
            'weeksCount'            => $weeksCount,
            'eventsSchoolCount'     => $eventsSchoolCount,
            'eventsOfficeCount'     => $eventsOfficeCount,
            'eventsTrainingCount'   => $eventsTrainingCount,
            'eventsTaskCount'       => $eventsTaskCount,
            'eventsCount'           => $eventsCount,

        ])->layout('layouts.admin');
    }
}

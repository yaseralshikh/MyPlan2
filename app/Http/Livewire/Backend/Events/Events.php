<?php

namespace App\Http\Livewire\Backend\Events;

use PDF;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Week;
use App\Models\Event;
use App\Models\Level;
use App\Models\Office;
use App\Models\Subtask;
use Livewire\Component;
use App\Models\Semester;
use App\Rules\UserOverLap;
use App\Models\SectionType;
use Livewire\WithPagination;
use App\Exports\EventsExport;
use App\Rules\DateOutService;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Events extends Component
{
    use WithPagination;
    use WithFileUploads;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    public $paginateValue = 50;

    public $data = [];

    public $tasks = [];
    public $levels = [];

    public $items = [];

    public $event;

    public $byOffice = null; //filter by Office_id
    public $byLevel = null; //filter by Office_id
    public $byWeek = null; //filter by week_id
    public $bySectionType = null; // filter bt section_type_id
    public $byStatus = 0; // filter bt status

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $showEditModal = false;

    public $eventIdBeingRemoved = null;

    public $selectedRows = [];
    public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteEvents'];

    public $allowed_create_plans = null;

    public $overLapData = [];
    public $overLapStatus = null;

    // update Site Status

    public function update_allowed_create_plans()
    {
        $this->allowed_create_plans === 0 ? 1 : 0;
        $office = Office::where('id', auth()->user()->office_id)->first();

        $office->update(['allowed_create_plans' => $this->allowed_create_plans]);

        $this->alert('success', __('site.office_allowed_create_plans_updateSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);
    }

    // Updated Select Page Rows

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->events->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    // Reset Selected Rows

    public function resetSelectedRows()
    {
        $this->reset(['selectedRows', 'selectPageRows']);
    }

    // show Sweetalert Confirmation for Delete

    public function deleteSelectedRows()
    {
        $this->dispatchBrowserEvent('show-delete-alert-confirmation');
    }

    // set All selected Event As Active

    public function setAllAsActive()
    {
        Event::whereIn('id', $this->selectedRows)->update(['status' => 1]);

        $this->alert('success', __('site.eventActiveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // set All selected Event As InActive

    public function setAllAsInActive()
    {
        Event::whereIn('id', $this->selectedRows)->update([
            'status' => 0,
            'task_done' => 0,
            'display' => null,
        ]);

        $this->alert('success', __('site.eventInActiveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // set All selected Event As Done

    public function setAllAsDone()
    {
        $events_done_status = Event::whereIn('id', $this->selectedRows)->get();

        if(!$events_done_status[0]->status){

            $this->alert('error', __('site.eventsDoneStatus'), [
                'position' => 'center',
                'timer' => 4000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);

        } else {

            Event::whereIn('id', $this->selectedRows)->update([
                'task_done' => 1,
                'display' => 'background',
            ]);

            $this->alert('success', __('site.eventActiveSuccessfully'), [
                'position' => 'top-end',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
        };


        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // set All selected Event As UnDone

    public function setAllAsUnDone()
    {
        Event::whereIn('id', $this->selectedRows)->update([
            'task_done' => 0,
            'display' => null,
        ]);

        $this->alert('success', __('site.eventInActiveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // Delete Selected Event

    public function deleteEvents()
    {
        // delete selected events from database
        Event::whereIn('id', $this->selectedRows)->delete();

        $this->alert('success', __('site.deleteSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // Updated Search Term
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // show add new Event form modal

    public function addNewEvent()
    {
        $this->reset(['data','tasks']);
        //$this->resetExcept(['byStatus','byWeek','searchTerm']);
        $this->showEditModal = false;
        $this->data['status'] = 1;
        // $this->data['semester_id'] = $this->semesterActive();
        // $this->data['week_id'] = $this->weekActive();

        $this->dispatchBrowserEvent('show-form');
    }

    // Create new Event

    public function createEvent()
    {
        $validatedData = Validator::make($this->data, [
            'user_id'       => 'required',
            'level_id'      => 'required',
            'task_id'       => 'required',
            'start'         => ['required', new UserOverLap($this->data['start'], $this->data['user_id']), new DateOutService($this->data['start'], $this->data['start'])],
            'note'          => 'nullable|max:255',
            'status'        => 'required',
        ])->validate();

        $taskName = Task::whereStatus(true)->where('id', $this->data['task_id'])->pluck('name')->first();

        $color = null;

        switch ($taskName) {
            case 'إجازة':
                $color = '#f5e7fe';
                break;
            case 'يوم مكتبي':
                $color = '#f2f2f2';
                break;
            case 'برنامج تدريبي':
                $color = '#fff7e6';
                break;
            case 'مكلف بمهمة':
                $color = '#e6ffe6';
                break;
            default:
                $color = '#e6f5ff';
        }

        $semester_Id = Semester::where('start', '<=', $validatedData['start'])->where('end', '>=', $validatedData['start'])->pluck('id')->first();

        $week_Id = Week::where('start', '<=', $validatedData['start'])->where('end', '>=', $validatedData['start'])->pluck('id')->first();

        $eventStatus = Event::where('task_id', $validatedData['task_id'])->where('start', $validatedData['start'])
            ->whereHas('task', function ($q) {$q->whereNotIn('name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة']);})
            ->count() <= 0 ;

        if (!$eventStatus) {

            $this->overLapData = [

                'user_id'       => $this->data['user_id'],
                'office_id'     => auth()->user()->office_id,
                'task_id'       => $this->data['task_id'],
                'start'         => $this->data['start'],
                'end'           => $this->data['start'],
                'status'        => $this->data['status'],
                'color'         => $color,
                'semester_id'   => $semester_Id,
                'week_id'       => $week_Id,
            ];

            if (isset($this->data['note'])) {
                $this->overLapData['note'] = $this->data['note'];
            } else {
                $this->overLapData['note'] = null;
            }

            $this->overLapStatus = 'create';
            $this->dispatchBrowserEvent('show-event-overlap-modal');

        } else {

            $validatedData['color'] = $color;
            $validatedData['office_id'] = auth()->user()->office_id;
            $validatedData['semester_id'] = $semester_Id;
            $validatedData['week_id'] = $week_Id;
            $validatedData['end'] = $validatedData['start'];

            Event::create($validatedData);

            $this->dispatchBrowserEvent('hide-form');

            $this->alert('success', __('site.saveSuccessfully'), [
                'position' => 'top-end',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
        }
    }

    // Show Modal Form to Confirm Event OverLap

    public function confirmEventOverLap()
    {
        if ($this->overLapStatus == 'create') {

            Event::create($this->overLapData);

        } else {

            $this->event->update($this->overLapData);

        }

        $this->dispatchBrowserEvent('hide-event-overlap-modal');

        $this->dispatchBrowserEvent('hide-form');

        $this->alert('success', __('site.saveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->overLapStatus = null;
        $this->overLapData = null;
    }

    // show Update new event form modal

    public function edit(Event $event)
    {
        $this->reset(['data','tasks','byLevel']);

        $this->showEditModal = true;

        $this->byOffice = $event->task->office_id;

        $this->byLevel = $event->task->level_id;

        $this->OfficeOption($this->byOffice);

        $this->LevelOption($this->byLevel);

        $this->event = $event;

        $this->data = $event->toArray();

        $this->data['office_id'] = $this->byOffice;

        $this->data['level_id'] = $this->byLevel;

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Event

    public function updateEvent()
    {
        $validatedData = Validator::make($this->data, [
            'task_id'       => 'required',
            'user_id'       => 'required',
            'level_id'      => 'required',
            'start'         => ['required', new DateOutService($this->data['start'])],
            'note'          => 'nullable|max:255',
            'status'        => 'required',
        ])->validate();

        $taskName = Task::whereStatus(true)->where('id', $this->data['task_id'])->pluck('name')->first();

        $color = null;

        switch ($taskName) {
            case 'إجازة':
                $color = '#f5e7fe';
                break;
            case 'يوم مكتبي':
                $color = '#f2f2f2';
                break;
            case 'برنامج تدريبي':
                $color = '#fff7e6';
                break;
            case 'مكلف بمهمة':
                $color = '#e6ffe6';
                break;
            default:
                $color = '#e6f5ff';
        }

        $semester_Id = Semester::where('start', '<=', $validatedData['start'])->where('end', '>=', $validatedData['start'])->pluck('id')->first();
        $week_Id = Week::where('start', '<=', $validatedData['start'])->where('end', '>=', $validatedData['start'])->pluck('id')->first();

        $eventStatus = Event::where('task_id', $validatedData['task_id'])->where('start', $validatedData['start'])
        ->whereHas('task', function ($q) {$q->whereNotIn('name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة']);})
        ->count() <= 0 ;

        if (!$eventStatus) {

            $this->overLapData = [

                'user_id'       => $this->data['user_id'],
                'office_id'     => auth()->user()->office_id,
                'task_id'       => $this->data['task_id'],
                'start'         => $this->data['start'],
                'end'           => $this->data['start'],
                'status'        => $this->data['status'],
                'color'         => $color,
                'semester_id'   => $semester_Id,
                'week_id'       => $week_Id,

            ];

            if (isset($this->data['note'])) {
                $this->overLapData['note'] = $this->data['note'];
            } else {
                $this->overLapData['note'] = null;
            }

            $this->overLapStatus = 'update';
            $this->dispatchBrowserEvent('show-event-overlap-modal');

        } else {

            $validatedData['office_id'] = auth()->user()->office_id;
            $validatedData['semester_id'] = $semester_Id;
            $validatedData['week_id'] = $week_Id;
            $validatedData['end'] = $validatedData['start'];
            $validatedData['color'] = $color;

            $this->event->update($validatedData);

            $this->dispatchBrowserEvent('hide-form');

            $this->alert('success', __('site.updateSuccessfully'), [
                'position' => 'top-end',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
        }
    }


    // Show Modal Form to Confirm Event Removal

    public function confirmEventRemoval($eventId)
    {
        $this->eventIdBeingRemoved = $eventId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Event

    public function deleteEvent()
    {
        try {
            $event = Event::findOrFail($this->eventIdBeingRemoved);

            $event->delete();

            $this->dispatchBrowserEvent('hide-delete-modal');

            $this->alert('success', __('site.deleteSuccessfully'), [
                'position' => 'top-end',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);

        } catch (\Throwable$th) {
            $message = $this->alert('error', $th->getMessage(), [
                'position' => 'top-end',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
            return $message;
        }
    }


    // Export Excel File
    public function exportExcel()
    {
        $byStatus = $this->byStatus;
        $byWeek = $this->byWeek;
        $bySectionType = $this->bySectionType;
        $byOffice = auth()->user()->office_id;

        if ($byStatus) {
            if ($byWeek && $bySectionType) {
                return Excel::download(new EventsExport(
                    $this->searchTerm,
                    $this->selectedRows,
                    $this->byWeek,
                    $bySectionType,
                    $this->byStatus,
                    $byOffice),
                    'events.xlsx');
            } else {
                $this->alert('error', __('site.selectWeek') . ' وكذلك ' . __('site.sectionType'), [
                    'position' => 'center',
                    'timer' => 6000,
                    'timerProgressBar' => true,
                    'toast' => true,
                    'text' => null,
                    'showCancelButton' => false,
                    'showConfirmButton' => false,
                ]);
            }
        } else {
            $this->alert('error', __('site.selectStatus'), [
                'position' => 'center',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
        }
    }


    public function userNullPlan()
    {
        $byWeek = $this->byWeek;
        $bySectionType = $this->bySectionType;
        $byOffice = auth()->user()->office_id;

        $this->items = [];

        if ($byWeek && $bySectionType) {

            $week_range = Week::whereId($byWeek)->get()->first();

            $start = Carbon::parse($week_range->start);
            $end = Carbon::parse($week_range->end);

            $dates = [];

            while ($start->lte($end)) {
                $dates[] = $start->toDateString();
                $start->addDay();
            }

            $days_range = count($dates)-1;

            $users = User::where('status', true)->where('section_type_id', $bySectionType)->where('office_id', $byOffice ? $byOffice : auth()->user()->office_id)->with(['events' => function ($query) use ($byWeek) {
                $query->where('week_id', $byWeek)->orderBy('start', 'asc');
            }])->get();

            $table = '<table style="border-collapse: collapse;">';
            $table .= '<thead><tr><th style="border: 1px solid;text-align: center;background-color: #f2f2f2;">! مشرفين خططهم غير مكتملة</th><th style="border: 1px solid;text-align: center;background-color: #f2f2f2;">م</th></tr></thead>';
            $table .= '<tbody>';

            $index = 0;

            foreach ($users as $user) {
                if ($user->events->count() < $days_range ) {
                    $index = $index+ 1;
                    $table .= '<tr>';
                    $table .= '<td style="border: 1px solid;text-align: center;">' . $user->name . ' (<span style="color:red"> ' . $user->events->count() . ' </span>)' . '</td>';
                    $table .= '<td style="border: 1px solid;text-align: center;">' . $index . '</td>';
                    $table .= '</td>';
                }
            }

            $table .= '</tbody></table>';

            if (count($users)) {
                $this->alert('error', $table, [
                    'position' => 'center',
                    'timer' => null,
                    'toast' => true,
                    'text'  => null,
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    //'width' => '550px',
                ]);

            } else {
                $this->alert('success', __('site.noReviews'), [
                    'position' => 'center',
                    'timer' => 2000,
                    'timerProgressBar' => true,
                    'toast' => true,
                    'text' => null,
                    'showCancelButton' => false,
                    'showConfirmButton' => false,
                    //'width' => '500px',
                ]);
            }

        } else {
            $this->alert('error', __('site.selectWeek') . ' وكذلك ' . __('site.selectEduType'), [
                'position' => 'center',
                'timer' => 6000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
                'width' => '500px',
            ]);
        }
    }


    public function taskNullPlan()
    {
        $byWeek = $this->byWeek;
        $byOffice = auth()->user()->office_id;

        if ($byWeek) {

            $events = Event::where('office_id', $byOffice ? $byOffice : auth()->user()->office_id)->where('week_id', $byWeek)->pluck('task_id')->toArray();

            $tasks_null_plan = Task::where('office_id', $byOffice ? $byOffice : auth()->user()->office_id)->whereNotIn('id' , array_values($events))->whereNotIn('level_id',[5])->get();

            $chunks = $tasks_null_plan->chunk(3);

            $tableRows = '';

            foreach ($chunks as $chunk) {
                $tableRow = '<tr>';
                foreach ($chunk as $task) {
                    $tableRow .= '<td style="border: 1px solid;text-align: center;">' . $task->name . '</td>';
                }
                $tableRow .= str_repeat('<td style="border: 1px solid;text-align: center;"></td>', 3 - count($chunk));
                $tableRow .= '</tr>';
                $tableRows .= $tableRow;
            }

            $table = '<table style="border-collapse: collapse;"><thead><tr><th colspan="3" style="border: 1px solid;text-align: center;background-color: #f2f2f2;">! مدارس لم تدرج في خطة هذا الاسبوع</th></tr></thead><tbody>' . $tableRows . '</tbody></table>';

            $this->alert('warning', $table, [
                'position' => 'center',
                'timer' => null,
                'toast' => true,
                'text'  => null,
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'width' => '700px',
            ]);

        } else {
            $this->alert('error', __('site.selectWeek'), [
                'position' => 'center',
                'timer' => 6000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
                'width' => '500px',
            ]);
        }
    }

    public function exportPDF()
    {
        $selectedRows = $this->selectedRows;
        $byWeek = $this->byWeek;
        $bySectionType = $this->bySectionType;
        $byOffice = auth()->user()->office_id;

        try {

            if ($selectedRows) {

                if ($byWeek && $bySectionType) {

                    $users = User::where('status', true)->where('office_id', $byOffice)->where('section_type_id', $bySectionType)->orderBy('name', 'asc')
                        ->whereHas('events', function ($query) use ($byWeek) {
                            $query->where('week_id', $byWeek)->where('status', true);
                        })->with(['events' => function ($query) use ($byWeek, $selectedRows) {
                                $query->with('task')->whereHas('task', function ($q) {$q->whereNotIn('name', ['إجازة']);})->whereIn('id', $selectedRows)->WhereNotNull('id')->where('week_id', $byWeek)->where('status', true)->orderBy('start', 'asc');
                    }])->get();

                    if ($users->count() != null) {

                        $subtasks = Subtask::where('status', 1)->where('office_id', $byOffice)->where('section_type_id', $bySectionType)->orderBy('position', 'asc')->get();
                        $office = Office::where('id', $byOffice)->first();

                        if ($subtasks->count() == null) {

                            Log::alert(__('site.notSubtasksFound'));
                            $this->alert('error', __('site.notSubtasksFound'), [
                                'position' => 'center',
                                'timer' => 6000,
                                'timerProgressBar' => true,
                                'toast' => true,
                                'text' => null,
                                'showCancelButton' => false,
                                'showConfirmButton' => false,
                            ]);

                        } else {

                            return response()->streamDownload(function () use ($users, $subtasks, $office) {

                                $pdf = PDF::loadView('livewire.backend.events.events_pdf', [

                                    'users' => $users,
                                    'subtasks' => $subtasks,
                                    'office' => $office,

                                ]);

                                return $pdf->stream('events');

                            }, 'events.pdf');
                        }

                    } else {

                        $this->alert('error', __('site.noDataForExport'), [
                            'position' => 'center',
                            'timer' => 2000,
                            'timerProgressBar' => true,
                            'toast' => true,
                            'text' => null,
                            'showCancelButton' => false,
                            'showConfirmButton' => false,
                        ]);
                    }

                } else {

                    $this->alert('error', __('site.selectWeek') . ' وكذلك ' . __('site.selectEduType'), [
                        'position' => 'center',
                        'timer' => 6000,
                        'timerProgressBar' => true,
                        'toast' => true,
                        'text' => null,
                        'showCancelButton' => false,
                        'showConfirmButton' => false,
                    ]);
                }

            } else {

                $this->alert('error', __('site.selectRows'), [
                    'position' => 'center',
                    'timer' => 6000,
                    'timerProgressBar' => true,
                    'toast' => true,
                    'text' => null,
                    'showCancelButton' => false,
                    'showConfirmButton' => false,
                ]);
            }

        } catch (\Throwable$th) {
            
            $message = $this->alert('error', $th->getMessage(), [
                'position' => 'top-end',
                'timer' => 2000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);

            Log::debug($th->getMessage());

            return $message;
        }
    }


    //Get Semester Active
    public function semesterActive()
    {
        $semester_active = Semester::where('active', 1)->get();
        return $semester_active[0]->id;
    }

    // Get Events Property
    public function getEventsProperty()
    {
        $paginateValue = $this->paginateValue;
        $searchString = $this->searchTerm;
        $byOffice = auth()->user()->office_id;
        $byWeek = $this->byWeek;
        $bySectionType = $this->bySectionType;
        $byStatus = $this->byStatus;

        $events = Event::where('status', $byStatus)->where('semester_id', $this->semesterActive())
            ->when($byOffice, function ($query) use ($byOffice) {
                $query->where('office_id', $byOffice);
            })
            ->when($byWeek, function ($query) use ($byWeek) {
                $query->where('week_id', $byWeek);
            })->when($bySectionType, function ($query) use ($bySectionType) {
            $query->whereHas('user', function ($q) use ($bySectionType) {
                $q->where('section_type_id', $bySectionType);
            });
        })
            ->search(trim(($searchString)))
            ->orderBy('start', 'asc')
            ->latest('created_at')
            ->paginate($paginateValue);

        return $events;
    }

    public function updated()
    {
        $this->getTaskesData();
        $this->getLevelsData();
    }

    public function OfficeOption($id)
    {
        $this->byOffice = $id;
        $this->getLevelsData();
    }

    public function LevelOption($id)
    {
        $this->byLevel = $id;
        $this->getTaskesData();
    }

    public function getLevelsData()
    {
        $user_office_id = auth()->user()->office_id;

        $selected_office_id = $this->byOffice;

        $byOffice = $selected_office_id ? $selected_office_id : $user_office_id;

        $this->levels = Level::whereIn('id', [1,2,3,4,5,6, $user_office_id == $byOffice ? 7 : ''])
            ->whereHas('tasks', function ($query) use ($byOffice) {$query->where('office_id', $byOffice);})
            ->get();
    }

    public function getTaskesData()
    {
        $user_office_id = auth()->user()->office_id;

        $selected_office_id = $this->byOffice;

        $byOffice = $selected_office_id ? $selected_office_id : $user_office_id;

        $selected_level_id = $this->byLevel;

        $this->tasks = Task::where('office_id', $byOffice)
            ->whereStatus(1)->where('level_id' , $selected_level_id)
            ->orderBy('level_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function render()
    {
        $events = $this->events;

        $levels = $this->getLevelsData();

        $tasks  = $this->getTaskesData();

        $users  = User::whereStatus(1)->where('office_id', auth()->user()->office_id)->orderBy('name', 'asc')->get();

        $weeks  = Week::whereStatus(1)->where('semester_id', $this->semesterActive())->get();

        $this->allowed_create_plans = Office::where('id', auth()->user()->office_id)->pluck('allowed_create_plans')->first();

        $sctionsType = SectionType::all();

        $education_offices = Office::where('office_type', 1)
            ->where('education_id', auth()->user()->office->education->id)
            ->pluck('id')
            ->toArray();

        $offices = Office::whereStatus(true)
            ->where('gender', auth()->user()->gender)
            ->whereIn('id', array_merge($education_offices, [auth()->user()->office->id]))
            ->get();

        return view('livewire.backend.events.events', compact(
            'offices',
            'events',
            'users',
            'weeks',
            'sctionsType',
            'levels',
            'tasks',
        ))->layout('layouts.admin');
    }
}

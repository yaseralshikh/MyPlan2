<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Task;
use App\Models\User;
use App\Models\Week;
use App\Models\Event;
use App\Models\Level;
use App\Models\Office;
use Livewire\Component;
use App\Models\Semester;
use App\Rules\ArabicText;
use App\Rules\UserOverLap;
use App\Rules\EventOverLap;
use App\Rules\NoteRequired;
use App\Rules\DateOutService;
use App\Models\Specialization;
use App\Rules\MobileNumber;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Calendar extends Component
{
    use LivewireAlert;

    public $data = [];
    public $tasks = [];
    public $levels = [];

    public $officeId = null;

    public $all_user;
    public $office_id;
    public $level_id;
    public $task_id;
    public $note;
    public $start;
    public $end;
    public $event_id;

    public $weeks = [];

    public $schoolsHasEvents  = [];

    // update User Profile

    public $profileData = [];

    public $userProfile;

    public function editProfile(User $user_profile)
    {
        $this->reset('profileData');

        $this->userProfile = $user_profile;

        $this->profileData = $user_profile->toArray();

        $this->dispatchBrowserEvent('show-profile');
    }

    public function updateProfile()
    {
        $emailVerifiedMessage = null;

        $validatedData = Validator::make($this->profileData, [
            'name'              => ['required', 'string', 'max:255', new ArabicText, 'unique:users,name,'.$this->profileData['id']],
            'email'             => ['required', 'string', 'email', 'regex:/@moe\.gov.sa$/i', 'max:50', 'unique:users,email,'.$this->userProfile->id],
            'mobile'            => ['required', new MobileNumber],
            'specialization_id' => 'required',
            'email_verified_at' => 'nullable',
            'password'          => 'sometimes|confirmed',
            'status'            => 'nullable',
        ])->validate();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        if ($validatedData['email'] != $this->userProfile->email) {
            $validatedData['email_verified_at'] = null;
            $validatedData['status'] = 0;
            $emailVerifiedMessage = true;
            $this->userProfile->sendEmailVerificationNotification();
        }

        $this->userProfile->update($validatedData);

        $this->dispatchBrowserEvent('hide-profile');

        $this->alert('success', __('site.updateSuccessfully') . ($emailVerifiedMessage ? ' <p dir="rtl"> <br> ' . __('site.emailVerifiedMessage') . '</p>' : ''), [
            'position' => 'top-end',
            'timer' => 4000,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);
    }
    // End update user profile

    protected function rules(): array
    {
        return ([
            'level_id'  => ['required'],
            'task_id'   => ['required', new EventOverLap($this->start), new UserOverLap($this->start, auth()->user()->id), new DateOutService($this->start, $this->end), new NoteRequired($this->note)],
            'note'      => ['max:255'],
        ]);
    }

    public function resetErrorMsg()
    {
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $taskName = Task::findOrFail($this->task_id);

        $color = null;

        switch ($taskName->name) {
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

        $semester_Id = Semester::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();
        $week_Id = Week::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();

        if ($this->all_user) {

            $users = User::where('office_id', auth()->user()->office_id)->whereStatus(1)->get();

            foreach ($users as $user) {

                $event = Event::where('start', $this->start)->where('user_id', $user->id)->count() == 0;

                if ($event) {
                    Event::create([
                        'user_id' => $user->id,
                        'office_id' => $user->office_id,
                        'semester_id' => $semester_Id,
                        'week_id' => $week_Id,
                        'task_id' => $this->task_id,
                        'note' => $this->note,
                        'start' => $this->start,
                        'end' => $this->end,
                        'color' => $color,
                        'status' => 1,
                    ]);
                }
            }

        } else {

            Event::create([
                'user_id' => auth()->user()->id,
                'office_id' => auth()->user()->office_id,
                'semester_id' => $semester_Id,
                'week_id' => $week_Id,
                'task_id' => $this->task_id,
                'note' => $this->note,
                'start' => $this->start,
                'end' => $this->end,
                'color' => $color,
            ]);
        }

        $this->reset();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('closeModalCreate', ['close' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
        $this->dispatchBrowserEvent('swal', [
            'title' => __('site.saveSuccessfully'),
            'timer' => 2000,
            'timerProgressBar' => true,
            'icon' => 'success',
            'showConfirmButton' => false,
            'toast' => true,
            'position' => 'center',
        ]);
    }


    public function update()
    {
        $this->data = [
            'office_id' => $this->office_id,
            'level_id'  => $this->level_id,
            'task_id'   => $this->task_id,
            'note'      => $this->note,
            'start'     => $this->start,
            'end'       => $this->end,
        ];

        $eventTaskOriginalId = Event::where('id', $this->event_id)->pluck('task_id')->first();

        if ($eventTaskOriginalId <> $this->task_id) {

            $validatedData = Validator::make($this->data, [

                'level_id'  => ['required'],
                'task_id'   => ['required', new EventOverLap($this->start), new NoteRequired($this->note)],
                'note'      => ['max:255'],

            ])->validate();

        }

        $taskName = Task::whereStatus(true)->where('id', $this->task_id)->pluck('name')->first();

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

        $validatedData['office_id'] = auth()->user()->office_id;
        $validatedData['task_id'] = $this->task_id;
        $validatedData['note'] = $this->note;
        $validatedData['color'] = $color;

        Event::findOrFail($this->event_id)->update($validatedData);

        $this->reset();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('closeModalEdit', ['close' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
        $this->dispatchBrowserEvent('swal', [
            'title' => __('site.updateSuccessfully'),
            'timer' => 2000,
            'timerProgressBar' => true,
            'icon' => 'success',
            'toast' => true,
            'showConfirmButton' => false,
            'position' => 'center',
        ]);
    }

    // Delete Event
    public function delete()
    {
        Event::findOrFail($this->event_id)->delete();

        $this->dispatchBrowserEvent('closeModalEdit', ['close' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true, 'eventMoveOrRemoved' => $this->start]);
        $this->dispatchBrowserEvent('swal', [
            'title' => __('site.deleteSuccessfully'),
            'timer' => 3000,
            'timerProgressBar' => true,
            'icon' => 'success',
            'toast' => true,
            'showConfirmButton' => false,
            'position' => 'center',
        ]);
    }

    // Drag-Drop event
    public function eventDrop($event, $oldEvent)
    {
        $eventdata = Event::find($event['id']);

        if ($eventdata->status) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'تم اعتماد المهمة ، لا يمكن التعديل الا بعد فك الاعتماد',
                'timer' => 4000,
                'timerProgressBar' => true,
                'icon' => 'error',
                'toast' => true,
                'showConfirmButton' => false,
                'position' => 'center',
            ]);
        } else {

            $eventOverLap = Event::where('task_id', $eventdata->task_id)
                ->where('start', $event['start'])
                ->whereHas('task', function ($q) {$q->whereNotIn('name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة']);})
                ->count() <= auth()->user()->office->allowed_overlap;

            if ($eventOverLap) {

                $eventStart = $event['start'];
                $eventEnd   = $event['start'];

                $week_Id = Week::where('start', '<=', $eventStart)->where('end', '>=', $eventEnd)->pluck('id')->first();

                if ($week_Id) {

                    $eventdata->start = $eventStart;
                    $eventdata->end = $eventEnd;
                    $eventdata->week_id = $week_Id;

                    $eventdata->save();

                    $this->dispatchBrowserEvent('swal', [
                        'title' => __('site.updateSuccessfully'),
                        'timer' => 2000,
                        'timerProgressBar' => true,
                        'icon' => 'success',
                        'toast' => true,
                        'showConfirmButton' => false,
                        'position' => 'center',
                    ]);

                } else {
                    $this->dispatchBrowserEvent('swal', [
                        'title' => 'اليوم المحدد غير مطابق للفصل الدراسي',
                        'timer' => 3500,
                        'timerProgressBar' => true,
                        'icon' => 'error',
                        'toast' => true,
                        'showConfirmButton' => false,
                        'position' => 'center',
                    ]);
                }

            } else {
                $this->dispatchBrowserEvent('swal', [
                    'title' => 'تم حجز الزيارة في هذا الموعد لنفس المدرسة من قبل مشرف أخر.',
                    'timer' => 3500,
                    'timerProgressBar' => true,
                    'icon' => 'error',
                    'toast' => true,
                    'showConfirmButton' => false,
                    'position' => 'center',
                ]);
            }
        }

        $this->resetErrorBag();
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true, 'eventMoveOrRemoved' => $oldEvent['start']]);
    }

    // update Level & Task Options ( Livewire )
    public function updated()
    {
        $this->getTaskesData();
        $this->getLevelsData();
    }

    public function OfficeOption($id)
    {
        $this->officeId = $id;
        $this->getLevelsData();
    }

    public function LevelOption()
    {
        $this->schoolsHasEvents = null;

        $this->schoolsHasEvents = Event::where('start', $this->start)->pluck('task_id')->toArray();

        $this->getTaskesData();
    }

    public function getLevelsData()
    {
        $user_office_id = auth()->user()->office_id;
        $officeId = $this->office_id ? $this->office_id : $user_office_id;

        $this->levels = Level::whereIn('id', [1,2,3,4,5,6, $user_office_id == $officeId ? 7 : ''])
            ->whereHas('tasks', function ($query) use ($officeId) {
            $query->where('office_id', $officeId);})
            ->get();
    }

    public function getTaskesData()
    {
        $officeId = $this->office_id ? $this->office_id : auth()->user()->office_id;

        $this->tasks = Task::where('office_id', $officeId)
            ->whereStatus(1)->where('level_id', $this->level_id)
            ->whereNotIn('id', array_values($this->schoolsHasEvents))->whereNotIn('level_id', [7])
            ->orderBy('level_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function semesterActive()
    {
        $semester_active = Semester::whereActive(1)->first();
        return $semester_active->id;
    }

    public function render()
    {
        $education_offices = Office::where('office_type', 1)
            ->where('education_id', auth()->user()->office->education->id)
            ->pluck('id')
            ->toArray();

        $offices = Office::whereStatus(true)
            ->where('gender', auth()->user()->gender)
            ->whereIn('id', array_merge($education_offices, [auth()->user()->office->id]))
            ->get();

        $levels = $this->getLevelsData();

        $tasks = $this->getTaskesData();

        $specializations = Specialization::whereStatus(true)
            ->orderBy('name', 'asc')
            ->get();

        $semester_id = $this->semesterActive();

        return view('livewire.frontend.calendar', compact(
            'offices',
            'levels',
            'tasks',
            'specializations',
            'semester_id'
        ));
    }
}

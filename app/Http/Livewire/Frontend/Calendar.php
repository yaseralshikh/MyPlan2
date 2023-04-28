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
use App\Rules\UserOverLap;
use App\Rules\EventOverLap;
use App\Rules\DateOutService;
use App\Models\Specialization;
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
        try {
            $emailVerifiedMessage = null;

            $validatedData = Validator::make($this->profileData, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $this->userProfile->id,
                'specialization_id' => 'required',
                'email_verified_at' => 'nullable',
                'password' => 'sometimes|confirmed',
                'status' => 'nullable',
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

        } catch (\Throwable$th) {
            $message = $this->alert('error', $th->getMessage(), [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
            return $message;
        }
    }
    // End update user profile

    protected function rules(): array
    {
        return ([
            'level_id' => ['required'],
            'task_id' => ['required', new EventOverLap($this->start), new UserOverLap($this->start), new DateOutService($this->start, $this->end)],
        ]);
    }

    public function resetErrorMsg()
    {
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

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

        $semester_Id = Semester::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();
        $week_Id = Week::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();

        if ($this->all_user) {

            $users = User::where('office_id', auth()->user()->office_id)->whereStatus(1)->get();

            foreach ($users as $user) {
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
            'task_id'   => $this->task_id,
            'note'      => $this->note,
            'start'     => $this->start,
            'end'       => $this->end,
        ];

        $validatedData = Validator::make($this->data, [
            'task_id' => ['required', new EventOverLap($this->start)],
        ])->validate();

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
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
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
        if (($eventdata->user_id == auth()->user()->id) || (auth()->user()->roles[0]->id != 3)) {
            if ($eventdata->status && auth()->user()->roles[0]->id == 3) {
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
        } else {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'لا تملك الصلاحية للتعديل !!',
                'timer' => 2000,
                'timerProgressBar' => true,
                'icon' => 'error',
                'toast' => true,
                'showConfirmButton' => false,
                'position' => 'center',
            ]);
        }

        $this->resetErrorBag();
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
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
        $this->getTaskesData();
    }

    public function getLevelsData()
    {
        $officeId = $this->office_id ? $this->office_id : auth()->user()->office_id;

        $this->levels = Level::with('tasks')->whereHas('tasks', function ($query) use ($officeId) {$query->where('office_id', $officeId);})->get();
    }

    public function getTaskesData()
    {
        $officeId = $this->office_id ? $this->office_id : auth()->user()->office_id;

        $this->tasks = Task::where('office_id', $officeId)
            ->whereStatus(1)->where('level_id', $this->level_id)
            ->orderBy('level_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function render()
    {
        if (auth()->user()->gender == 1) {
            $office_gender = [1, 2, 3, 4, 5, 6, auth()->user()->office->id];
        } else {
            $office_gender = [7, 8, 9, 10, 12, auth()->user()->office->id];
        }

        $offices = Office::whereStatus(true)->whereIn('id', $office_gender)->where('education_id', auth()->user()->office->education->id)->get();

        $levels = $this->getLevelsData();

        $tasks = $this->getTaskesData();

        $specializations = Specialization::whereStatus(true)->orderBy('name', 'asc')->get();

        return view('livewire.frontend.calendar', compact(
            'offices',
            'levels',
            'tasks',
            'specializations',
        ));
    }
}

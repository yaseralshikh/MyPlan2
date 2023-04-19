<?php

namespace Illuminate\Foundation\Auth;

use App\Models\Office;
use App\Models\JobType;
use App\Models\SectionType;
use Illuminate\Http\Request;
use App\Models\Specialization;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $specializations = Specialization::where('status', true)->get();
        $offices = Office::where('status', true)->get();
        $job_types = JobType::whereStatus(true)->get();
        $section_types = SectionType::whereStatus(true)->get();

        $genders = [
            [
                'id'    => 1,
                'name' => __('site.male')
            ],
            [
                'id'    => 0,
                'name' => __('site.female')
            ]
        ];

        return view('auth.register', compact('specializations','offices', 'job_types', 'section_types','genders'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if(!Auth::user()->status) {
            Auth::logout();
            return redirect('login')->withErrors(['تم التسجيل بنجاح ،، تواصل مع صاحب الصلاحية في ادارتك لتفعيل الحساب !']);
        }

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}

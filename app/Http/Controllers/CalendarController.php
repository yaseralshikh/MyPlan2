<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = Event::with('user:id,name')->where('user_id',auth()->user()->id)
            ->with('task:id,name,level_id')
            ->with('week:id,name')
            ->with('semester:id,name,school_year')
            ->with('office:id,name')
            ->Where('office_id', auth()->user()->office_id)->get();
        return $events ;
    }
}

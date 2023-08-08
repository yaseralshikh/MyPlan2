<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfficeDropdownController extends Controller
{
    public function getOffices(Request $request)
    {
        $data['offices'] = Office::where("education_id", $request->education_id)->where('gender',$request->gender)->get(["name", "id"]);

        return response()->json($data);
    }
}

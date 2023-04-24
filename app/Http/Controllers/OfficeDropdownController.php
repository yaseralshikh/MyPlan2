<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeDropdownController extends Controller
{
    public function getOffices(Request $request)
    {
        $data['offices'] = Office::where("education_id", $request->education_id)->get(["name", "id"]);

        return response()->json($data);
    }
}

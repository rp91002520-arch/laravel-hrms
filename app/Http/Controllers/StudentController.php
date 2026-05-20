<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Data inserted',
            'data' => $student
        ]);
    }
    public function destroy($id)
{
    Student::find($id)->delete();

    return response()->json([
        'message' => 'Deleted Successfully'
    ]);
}
}
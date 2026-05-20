<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\models\Student;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\{AuthController,LeaveApplicationController};
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/student', [StudentController::class, 'store']);
Route::delete('/student/{id}', [StudentController::class, 'destroy']);
Route::get('/users', function () {
    return Student::all();
});

Route::post(
    '/login',
    [AuthController::class, 'login']
);

Route::apiResource(
    'employees',
    EmployeeController::class
);

Route::apiResource(
    'leaves',
    LeaveApplicationController::class
);

Route::middleware([
    'auth:sanctum',
    'admin'
])

->group(function () {

    Route::post(
        '/leave/{id}/approve',
        [LeaveApplicationController::class, 'approve']
    );

    Route::post(
        '/leave/{id}/reject',
        [LeaveApplicationController::class, 'reject']
    );

    Route::get(
        '/leave-statistics',
        [LeaveApplicationController::class, 'statistics']
    );
});

Route::middleware([
    'auth:sanctum',
    'admin'
])

->get(
    '/attendance-summary',
    function () {

        $totalEmployees =
        \App\Models\Employee::count();

        $onLeave =
        \App\Models\LeaveApplication::where(
            'status',
            'Approved'
        )->count();

        $weekOff =
        \Carbon\Carbon::now()->isWeekend() ? 1 : 0;

        $present =
        $totalEmployees - $onLeave;

        return response()->json([

            'Total Employees' =>
            $totalEmployees,

            'Present' =>
            $present,

            'Absent' =>
            0,

            'On Leave' =>
            $onLeave,

            'Week Off' =>
            $weekOff
        ]);
    }
);
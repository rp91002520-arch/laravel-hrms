<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LeaveApplication;
use App\Http\Requests\LeaveRequest;

class LeaveApplicationController extends Controller
{
    public function index()
    {
        return LeaveApplication::with('employee')
            ->latest()
            ->paginate(10);
    }

    public function store(LeaveRequest $request)
    {
        DB::beginTransaction();

        try {

            $exists = LeaveApplication::where('employee_id',$request->employee_id)
            ->where('status', '!=', 'Rejected')
            ->where(function ($query) use ($request) {
                $query->whereBetween(
                    'from_date',
                    [
                        $request->from_date,
                        $request->to_date
                    ]
                )
                ->orWhereBetween(
                    'to_date',
                    [
                        $request->from_date,
                        $request->to_date
                    ]
                )

                ->orWhere(function ($q) use ($request) {

                    $q->where(
                        'from_date',
                        '<=',
                        $request->from_date
                    )

                    ->where(
                        'to_date',
                        '>=',
                        $request->to_date
                    );
                });
            })

            ->exists();

            if ($exists) {

                return response()->json([
                    'message' =>
                    'Overlapping leave already exists'
                ], 422);
            }

            $totalDays = $this->calculateSandwichLeave(
                $request->from_date,
                $request->to_date
            );

            $leave = LeaveApplication::create([

                'employee_id' => $request->employee_id,

                'leave_type' => $request->leave_type,

                'from_date' => $request->from_date,

                'to_date' => $request->to_date,

                'total_days' => $totalDays,

                'reason' => $request->reason
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Leave Applied',
                'data' => $leave
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function approve(
        Request $request,
        $id
    ) {

        DB::beginTransaction();

        try {

            $leave = LeaveApplication::findOrFail($id);

            if ($leave->status == 'Approved') {

                return response()->json([
                    'message' =>
                    'Already Approved'
                ]);
            }

            $employee = Employee::findOrFail(
                $leave->employee_id
            );

            $days = $leave->total_days;

            if ($leave->leave_type == 'Paid') {

                if (
                    $employee->paid_leave_balance < $days
                ) {

                    return response()->json([
                        'message' =>
                        'Insufficient Paid Leave'
                    ]);
                }

                $employee->paid_leave_balance -= $days;
            }

            if ($leave->leave_type == 'Sick') {

                if (
                    $employee->sick_leave_balance < $days
                ) {

                    return response()->json([
                        'message' =>
                        'Insufficient Sick Leave'
                    ]);
                }

                $employee->sick_leave_balance -= $days;
            }

            if ($leave->leave_type == 'Casual') {

                if (
                    $employee->casual_leave_balance < $days
                ) {

                    return response()->json([
                        'message' =>
                        'Insufficient Casual Leave'
                    ]);
                }

                $employee->casual_leave_balance -= $days;
            }

            $employee->save();

            $leave->status = 'Approved';

            $leave->remarks =
            $request->remarks;

            $leave->save();

            DB::commit();

            return response()->json([
                'message' =>
                'Leave Approved Successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reject(
        Request $request,
        $id
    ) {

        $leave = LeaveApplication::findOrFail($id);

        $leave->status = 'Rejected';

        $leave->remarks =
        $request->remarks;

        $leave->save();

        return response()->json([
            'message' =>
            'Leave Rejected'
        ]);
    }

    private function calculateSandwichLeave(
        $fromDate,
        $toDate
    ) {

        $start = Carbon::parse($fromDate);

        $end = Carbon::parse($toDate);

        $days = 0;

        while ($start <= $end) {

            $days++;

            $start->addDay();
        }

        return $days;
    }

    public function statistics()
    {
        return response()->json([

            'total_leave' =>
            LeaveApplication::count(),

            'approved_leave' =>
            LeaveApplication::where(
                'status',
                'Approved'
            )->count(),

            'rejected_leave' =>
            LeaveApplication::where(
                'status',
                'Rejected'
            )->count(),

            'pending_leave' =>
            LeaveApplication::where(
                'status',
                'Pending'
            )->count(),
        ]);
    }
}
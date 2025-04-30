<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function home()
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        // collect information about the organization
        $data = [];

        // get total number of colaboratores
        $data['total_colaborators'] = User::count();

        // total colaborators deleted
        $data['total_colaborators_deleted'] = User::onlyTrashed()->count();

        // total salary for all colaborators
        $data['total_salary'] = number_format(
            User::with('detail')
                ->get()
                ->sum(function ($colaborator) {
                    return $colaborator->detail->salary;
                })
            ,
            2,
            ',',
            '.'
        ) . '$';

        // total colaborators by department
        $data['total_colaborators_per_department'] = User::with('department')
            ->get()
            ->groupBy('department_id')
            ->map(function ($department) {
                return [
                    'department' => $department->first()->department->name ?? '-',
                    'total' => $department->count()
                ];
            });

        // total salary by department
        $data['total_salary_by_department'] = User::with('department', 'detail')
            ->get()
            ->groupBy('department_id')
            ->map(function ($department) {
                return [
                    'department' => $department->first()->department()->name ?? '-',
                    'total' => number_format(
                        $department->sum(function ($colaborator) {
                            return $colaborator->detail->salary;
                        }),
                        2,
                        ',',
                        '.'
                    ) . '$'
                ];
            });

        // display admin home page
        return view('home', compact('data'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $departments = Department::all();
        return view('department.departments', compact('departments'));
    }

    public function newDepartment(): View
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        return view('department.add-department');
    }

    public function createDepartment(Request $request)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        // form validation
        $request->validate([
            'name' => 'required|string|max:50|unique:departments'
        ]);

        Department::create([
            'name' => $request->name
        ]);

        return redirect()->route('departments');
    }

    public function editDepartment($id)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }


        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail($id);

        return view('department.edit-department', compact('department'));
    }

    public function updateDepartment(Request $request)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $id = $request->id;

        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments');
        }

        $request->validate([
            'id' => 'required',
            'name' => "required|string|min:3|max:50|unique:departments,name,$id"
        ]);

        $department = Department::findOrFail($id);
        $department->update(['name' => $request->name]);
        return redirect()->route('departments');
    }

    public function deleteDepartment($id)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail($id);

        // display page for confirmation
        return view('department.delete-department-confirm', compact('department'));
    }

    public function deleteDepartmentConfirm($id)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments');
        }

        $department = Department::findOrFail($id);
        $department->delete();

        // update all colaborators department to null
        User::where('department_id', $id)->update(['department_id' => null]);

        return redirect()->route('departments');
    }

    private function isDepartmentBlocked($id): bool
    {
        return in_array(intval($id), [1, 2]);
    }
}

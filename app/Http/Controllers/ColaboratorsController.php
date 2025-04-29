<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ColaboratorsController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborators = User::withTrashed()
            ->with('detail', 'department')
            ->where('role', '<>', 'admin')
            ->get();

        return view('colaborators.admin-all-colaborators', compact('colaborators'));
    }

    public function showDetails($id)
    {
        if (!Auth::user()->can('admin', 'rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        // check if id is the same as the auth user
        if (Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::with('detail', 'department')
            ->where('id', $id)
            ->first();

        // check if colaborator exists
        if (!$colaborator) {
            abort(404);
        }

        return view('colaborators.show-details', compact('colaborator'));
    }

    public function deleteColaborator($id)
    {
        if (!Auth::user()->can('admin', 'rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        // check if id is the same as the auth user
        if (Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail($id);
        return view('colaborators.delete-colaborator-confirm', compact('colaborator'));
    }

    public function deleteColaboratorConfirm($id)
    {
        if (!Auth::user()->can('admin', 'rh')) {
            abort(403, 'You are not authorized to access this page');
        }

        // check if id is the same as the auth user
        if (Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail($id);
        $colaborator->delete();
        return redirect()->route('colaborators.all-colaborators');
    }

    public function restoreColaborator($id)
    {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        $colaborator = User::withTrashed()->findOrFail($id);
        $colaborator->restore();
        return redirect()->route('colaborators.all-colaborators');
    }

    public function home()
    {
        if (!Auth::user()->can('colaborator'))
            abort(403, 'You are not authorized to access this page');

        // get colaborator data
        $colaborator = User::with('detail', 'department')->findOrFail(Auth::user()->id);

        return view('colaborators.show-details', compact('colaborator'));
    }
}

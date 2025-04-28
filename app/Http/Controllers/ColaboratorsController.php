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

        $colaborators = User::with('detail', 'department')
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

        return view('colaborators.show-details', compact('colaborator'));
    }
}

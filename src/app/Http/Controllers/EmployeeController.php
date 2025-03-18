<?php

namespace App\Http\Controllers;

use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::paginate(5);
        return view('EmployeeDirectory', compact('employees'));
    }
}
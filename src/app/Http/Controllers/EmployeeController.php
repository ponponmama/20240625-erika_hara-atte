<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::paginate(5);
        return view('EmployeeDirectory', compact('employees'));
    }
}

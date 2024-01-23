<?php

namespace App\Http\Controllers;

use App\Models\DemandeTransfert;
use App\Models\Solde;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWebController extends Controller
{
    public function index()
    {
        $marchands = User::where('user_role', 'marchand')->count();
        $all  = all_trans();
        return view('admin/index', compact('marchands', 'all'));
    }

    public function transaction()
    {
        return view('admin/transaction');
    }

    public function cash_out()
    {
        return view('admin/cash_out');
    }

    public function merchant()
    {
        return view('admin/merchant');
    }

    public function feedback()
    {
        return view('admin/feedback');
    }
}

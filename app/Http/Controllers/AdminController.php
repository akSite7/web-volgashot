<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'products' => Product::latest()->get(),
            'leads' => Lead::latest()->get(),
            'users' => User::oldest()->get(['id', 'login', 'approved_at', 'created_at']),
        ]);
    }
}

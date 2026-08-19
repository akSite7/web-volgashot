<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:255'],
            'comment' => ['nullable', 'string', 'max:3000'],
        ], [
            'phone.required_without' => 'Укажите телефон или почту.',
        ]);

        Lead::create($data);

        return back()->with('success', 'Заявка отправлена. Мы скоро свяжемся с вами.');
    }
}

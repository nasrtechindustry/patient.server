<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientRegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.patient-register');
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|max:10',
            'password' => 'required|min:6|confirmed',
        ])->validate();

        $patient = Patient::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        auth()->guard('patient')->login($patient);

        return redirect()->route('patient.dashboard')->with('success', 'Registration successful!');
    }
}

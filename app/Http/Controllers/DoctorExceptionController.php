<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorException;
use Illuminate\Support\Facades\Auth;

class DoctorExceptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'exception_date' => 'required|date|after_or_equal:today',
        ]);

        $doctor = Auth::guard('doctor')->user();

        $doctor->exceptions()->create([
            'date' => $request->exception_date,
        ]);

        return redirect()->back()->with('success', 'Exception date added.');
    }

    public function destroy($id)
    {
        $doctor = Auth::guard('doctor')->user();

        $exception = $doctor->exceptions()->where('id', $id)->firstOrFail();

        $exception->delete();

        return redirect()->back()->with('success', 'Exception date removed.');
    }
}



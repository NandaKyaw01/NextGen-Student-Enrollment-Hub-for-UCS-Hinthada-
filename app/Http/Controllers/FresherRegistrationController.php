<?php

namespace App\Http\Controllers;



use App\Models\User;
use App\Models\Fresher;
use Illuminate\Support\Str;
use App\Helper\Facades\File;
use Illuminate\Http\Request;
use App\Mail\FresherAcceptedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FresherRegistrationController extends Controller
{
    public function fresherRegister()
    {
        return view('Auth.fresher_register');
    }

    public function fresherStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:freshers,email',
            'admission_docs' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fresher = new Fresher();
        $fresher->name = $request->name;
        $fresher->email = $request->email;

        if ($request->hasFile('admission_docs')) {
            $fresher->admission_document_screenshot = File::upload($request->file('admission_docs'), 'admission_docs_ss');
        }

        $fresher->save();

        return redirect()->route('ui.home')->with('success', 'Registration successful! Admin will review and email you.');
    }

    public function fresherRegList()
    {
        $freshers = Fresher::paginate(10);
        return view('admin.fresher_reg.list', compact('freshers'));
    }

    public function fresherAccept(Fresher $fresher)
    {
        return view('admin.fresher_reg.accept', compact('fresher'));
    }

    public function fresherAcceptStore(Request $request, Fresher $fresher)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@ucsh\.edu\.mm$/'],
            'uni_id_no' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $fresher->name,
            'email' => $request->email,
            'uni_id_no' => $request->uni_id_no,
            'image' => 'default.png',
            'password' => 'P@ssw0rd',
            'uuid' => Str::uuid()
        ]);

        if ($user) {
            try {
                Mail::to($fresher->email)->send(new FresherAcceptedMail($user));
            } catch (\Exception $e) {
                // Log the error for debugging
                Log::error('Mail sending failed: ' . $e->getMessage());
            }
        }

        $fresher->update([
            'status' => 'accepted',
        ]);

        return redirect()->route('fresher.reg.list')->with('success', 'Fresher registration accepted successfully.');
    }

    public function fresherDelete(Fresher $fresher)
    {
        if ($fresher->admission_document_screenshot) {
            File::delete('app/admission_docs_ss/' . $fresher->admission_document_screenshot);
        }
        $fresher->delete();

        return redirect()->route('fresher.reg.list')->with('success', 'Fresher registration deleted successfully.');
    }
}

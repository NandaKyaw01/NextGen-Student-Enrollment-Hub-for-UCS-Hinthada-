<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notice;
use App\Helper\Facades\File;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;

use PhpOffice\PhpWord\IOFactory;
use App\Exports\StopStudentExport;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransferStudentExport;
use App\Exports\FilteredStudentsExport;

class AdminController extends Controller
{

    public function acceptSearch(Request $request)
    {

        $searchKey = $request->input('student_name');
        $academicYearId = $request->input('academic_year_id');
        $specialist = $request->input('specialist');
        $query = StudentRegistration::query()->where('status', 'confirm');
        $years = AcademicYear::all();
        if ($searchKey) {
            $query->whereHas('user', function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }
        if ($specialist) {
            $query->where('specialist', $specialist);
        }

        $query->whereHas('user', function ($query) {
            $query->where('stop', 'no');
        });
        $regs = $query->paginate(10);
        return view('admin.accept.acceptList', compact('regs', 'years'));
    }


    public function acceptList()
    {
        $years = AcademicYear::all();
        $regs = StudentRegistration::where('status', 'confirm')
            ->whereHas('user', function ($query) {
                $query->where('stop', 'no');
            })
            ->wherehas('user', function ($query) {
                $query->where('transfer', 'present');
            })
            ->paginate(5);
        return view('admin.accept.acceptList', compact('regs', 'years'));
    }

    public function index()
    {
        $mails = User::where('role', 'user')->get();
        $regs = StudentRegistration::where('status', 'pending')->get();
        $notices = Notice::all();
        $years = AcademicYear::all();
        return view('admin.index', compact('mails', 'regs', 'notices', 'years'));
    }

    public function addAdmin()
    {
        return view('admin.admin.add');
    }

    public function adminList(Request $request)
    {
        $searchKey = $request->input('search');
        $admins = User::where('role', 'user')
            ->where('stop', 'no')
            ->whereIn('transfer', ['present', 'in']);
        if ($searchKey) {
            $admins->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }

        // $user = User::find(15);
        // dd($user->studentRegistrations()->latest()->first());

        $admins = $admins->latest()->paginate(10);



        return view('admin.admin.list', compact('admins'));
    }

    public function studentsList(Request $request)
    {
        $searchKey = $request->input('search');
        $major = $request->input('major');
        $year = $request->input('academic_year');
        $academicClasses = AcademicYear::all()->pluck('name', 'id')->toArray();

        $academicYears = StudentRegistration::distinct()->pluck('academic_year')->toArray();

        // Start query builder
        $students = User::with('studentRegistrations')
            ->where('role', 'user')
            ->whereIn('transfer', ['present', 'in']);

        if (!empty($searchKey)) {
            $students->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }

        if (!empty($major)) {
            $students->whereHas('studentRegistrations', function ($query) use ($major) {
                $query->where('major', $major);
            });
        }

        if (!empty($year)) {
            $students->whereHas('studentRegistrations', function ($query) use ($year) {
                $query->where('academic_year', $year);
            });
        }

        if (!empty($request->academic_class)) {
            $students->whereHas('studentRegistrations', function ($query) use ($request) {
                $query->where('academic_year_id', $request->academic_class);
            });
        }


        if ($request->has('is_register')) {
            if ($request->is_register == '1') {
                $students->whereHas('studentRegistrations');
            } else if ($request->is_register == '0') {
                $students->whereDoesntHave('studentRegistrations');
            }
        }

        // Final result
        $students = $students->latest()->paginate(10);

        return view('admin.students.index', compact('students', 'academicYears', 'academicClasses'));
    }

    public function stopStuList(Request $request)
    {
        $searchKey = $request->input('search');
        $admins = User::where('role', 'user')->where('stop', 'yes');
        if ($searchKey) {
            $admins->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }
        $admins = $admins->latest()->paginate(10);
        return view('admin.stopStu.list', compact('admins'));
    }

    public function transferStuList(Request $request)
    {
        $searchKey = $request->input('search');
        $admins = User::where('role', 'user')->whereIn('transfer', ['out', 'in']);
        if ($searchKey) {
            $admins->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }
        $admins = $admins->latest()->paginate(10);
        return view('admin.transfer.list', compact('admins'));
    }

    public function export()
    {
        return Excel::download(new StopStudentExport, 'stop_student_list.xlsx');
    }

    public function transferExport()
    {
        return Excel::download(new TransferStudentExport, 'transfer_student_list.xlsx');
    }

    public function studentsExport(Request $request)
    {
        return Excel::download(new FilteredStudentsExport($request), 'students_list.xlsx');
    }

    public function adminEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.admin.edit', compact('user'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            "name" => "required",
            "email" => 'required|email|unique:users,email,' . $user->id,
            "uni_id_no" => 'required|unique:users,uni_id_no,' . $user->id,
            "image" => "nullable|image|mimes:png,jpg,jpeg"
        ], [
            "name.required" => "Name is required.",
            "email.required" => "Email is required.",
            "email.email" => "Please enter a valid email address.",
            "email.unique" => "This email is already registered.",
            "uni_id_no.required" => "University ID number is required.",
            "uni_id_no.unique" => "This University ID number is already registered.",
            "image.required" => "Image is required.",
            "image.image" => "The file must be an image.",
            "image.mimes" => "The image must be a file of type: png, jpg, jpeg."
        ]);

        // Process the uploaded image

        if ($request->hasFile('image')) {
            $data['image'] = File::Upload($request->file('image'), 'images/' . $user->uuid);
        } else {
            $data['image'] = $user->image;
        }

        $user->update($data);

        return redirect()->route('admin.list')->with('success', ' အောင်မြင်စွာပြင်လိုက်ပါပြီ');
    }

    public function adminDelete($id)
    {
        try {
            $userId = Auth::user()->id;
            if ($id == $userId) {
                return back()->with('error', 'သင်သည် ကိုယ့်ကိုကိုယ် ဖြတ်၍မရပါ။');
            } else {
                $user = User::findOrFail($id);
                $user->delete();
                return back()->with('success', 'အောင်မြင်စွာဖြတ်လိုက်ပါပြီ');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'မူလကျားကိုဖြတ်၍မရပါ။');
        }
    }


    public function adminProfile()
    {
        $user = User::find(Auth::user()->id);
        return view('admin.profile.index', compact('user'));
    }

    public function adminEditProfile()
    {
        $user = User::find(Auth::user()->id);
        return view('admin.profile.edit', compact('user'));
    }

    public function adminUpdateProfile(Request $request)
    {
        $data = $request->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required|min:5",
            "image" => "required|image|mimes:png,jpg,jpeg"

        ]);

        $data['image'] = File::upload($request->file('image'), 'images/' . Auth::user()->uuid);

        $user = User::find(Auth::user()->id);
        $user->update($data);
        return redirect()->route('admin.profile')->with('success', 'အောင်မြင်စွာပြင်လိုက်ပါပြီ');
    }

    public function stopMail(User $user)
    {
        $user->stop = "yes";
        $user->save();
        return back()->with('success', 'ကျောင်းသားအား ရပ်နားထားသည်');
    }

    public function transferStudent(User $user)
    {
        $user->update(['transfer' => 'out']);
        return back()->with('success', 'ကျောင်းသားအား လွှဲပြောင်းထားသည်');
    }

    public function nostopMail($id)
    {
        $data = User::find($id);
        $data->stop = "no";
        $data->save();
        return back()->with('success', 'ကျောင်းသားအား ပြန်လည်ခွင့်ပြုသည်');
    }

    public function stopdownloadWordFile()
    {
        // Fetch the data you want to include in the Word document
        $admins = User::where('role', 'user')->where('stop', 'yes')->get(); // Adjust this to your actual data fetching logic

        // Create a new PhpWord object
        $phpWord = new PhpWord();

        // Add a section to the document
        $section = $phpWord->addSection();
        $section->addText(
            'ကျောင်းရပ်နားသူများစာရင်း',
            ['name' => 'Arial', 'size' => 14, 'bold' => true],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]  // Center the text
        );

        // Add table to the document
        $table = $section->addTable(['borderSize' => 6, 'cellMargin' => 80]);

        // Define table header
        $table->addRow();
        $table->addCell()->addText('စဉ်', ['size' => 10]);
        $table->addCell()->addText('အမည်', ['size' => 10]);
        $table->addCell()->addText('Email', ['size' => 10]);
        //$table->addCell()->addText('ကျောင်း oင်ခွင့်အမှတ်', ['size' => 10]);

        $table->addCell()->addText('ရပ်နားစရင်းထည့်ထားသည်', ['size' => 10]);



        $key = 1;
        foreach ($admins as $admin) {
            $table->addRow();
            $table->addCell()->addText($key++); // Replace with appropriate data
            $table->addCell()->addText($admin->name);
            $table->addCell()->addText($admin->email);


            // Image handling in Word documents can be complex; consider leaving this empty or use another method
            $table->addCell()->addText('ရပ်နားထားသည်');
            // Link handling in Word documents is complex; consider leaving this empty or using another method
            // Similarly, handle download links
        }

        // Save the document to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'Word');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Return the document as a download response
        return response()->download($tempFile, 'admins_list.docx')->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Helper\Facades\File;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Mail\FresherAcceptedMail;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StudentRegistrationRequest;
use App\Mail\RegistrationSuccessMail;

class StudentRegistrationController extends Controller
{
    public function sturegStore(StudentRegistrationRequest $request)
    {
        $attributes = $request->validated();

        if (auth()->user()->stop === "no") {
            $lastRegistration = StudentRegistration::where('user_id', auth()->user()->id)->where('status', 'pending')->latest()->first();

            if ($lastRegistration) {
                return redirect()->back()->with('error', 'ကျောင်းသားတစ်ယောက်သည် တစ်နှစ်လျှင် စာသင်နှစ်တစ်ခုကိုသာ ကျောင်းအပ်ခွင့်လျှောက်ထားနိုင်သည်။');
            };



            $academicYear = AcademicYear::findOrFail($attributes['last_academic_year']);




            //dd(($academicYear->name == 'ပထမနှစ် ပထမနှစ်ဝက်' || $academicYear->name == 'ပထမနှစ် ဒုတိယနှစ်ဝက်') && $attributes['major'] !== 'CST');

            // if (($academicYear->name == 'ပထမနှစ် ပထမနှစ်ဝက်' || $academicYear->name == 'ပထမနှစ် ဒုတိယနှစ်ဝက်') && $attributes['major'] !== 'CST') {
            //     return redirect()->back()->with('error', 'ပထမနှစ်အတွက် CST ကိုသာရွေးချယ်နိုင်ပါသည်။');
            // } elseif (($academicYear->name !== 'ပထမနှစ် ပထမနှစ်ဝက်' || $academicYear->name !== 'ပထမနှစ် ဒုတိယနှစ်ဝက်') && in_array($attributes['major'], ['computer science', 'computer technology'])) {
            //     return redirect()->back()->with('error', 'ဤနှစ်အတွက် CS သို့မဟုတ် CT ကိုသာရွေးချယ်နိုင်ပါသည်။');
            // }




            $search = Auth::user()->name;

            $response = Http::get('https://student-grading-system-mauve.vercel.app/api/results-by-semester', [
                'search' => $search,
                'class' => $academicYear->ename,
                'semester' => $academicYear->esemester,
            ]);

            $res = $response->json()['results'][0] ?? null;

            if ($res) {
                if ($res['status'] == 'FAIL' || $res['status'] == 'INCOMPLETE') {
                    return back()
                        ->with('error', 'You are "Failed" in your last academic exam. Son you can\'t register for this academic year.');
                }
            } else {
                return back()
                    ->with('error', 'No results found for your last academic exam.');
            }

            $path = 'images/' . Auth::user()->uuid . '/';
            $registration['profile'] = File::upload($request->file('profile'), $path);
            $registration['matriculation_result'] = File::upload($request->file('matriculation_result'), $path);
            $registration['matriculation_certificate'] = File::upload($request->file('matriculation_certificate'), $path);
            $registration['last_academic_year'] = $attributes['last_academic_year'];
            $registration['nrc_student_front'] = File::upload($request->file('nrc_student_front'), $path);
            $registration['nrc_student_back'] = File::upload($request->file('nrc_student_back'), $path);
            $registration['nrc_father_front'] = File::upload($request->file('nrc_father_front'), $path);
            $registration['nrc_father_back'] = File::upload($request->file('nrc_father_back'), $path);
            $registration['nrc_mother_front'] = File::upload($request->file('nrc_mother_front'), $path);
            $registration['nrc_mother_back'] = File::upload($request->file('nrc_mother_back'), $path);
            $registration['family_member_docs'] = File::upload($request->file('family_member_docs'), $path);
            $registration['payment_screenshot'] = File::upload($request->file('payment_screenshot'), $path);
            $registration['user_id'] = Auth::user()->id;
            $registration['academic_year_id'] = $attributes['academic_year_id'];
            $registration['academic_year'] = $attributes['academic_year'];
            $registration['major'] = $attributes['major'];
            $registration['roll_no'] = $attributes['roll_no'];
            $registration['reg_email'] = $attributes['reg_email'];
            $registration['phone'] = $attributes['phone'];
            $registration['dob'] = $attributes['dob'];
            $registration['present_address'] = $attributes['present_address'];
            $registration['nrc_student'] = $attributes['nrc_student'];
            $registration['race'] = $attributes['race'];
            $registration['religion'] = $attributes['religion'];
            $registration['blood_type'] = $attributes['blood_type'];
            $registration['father_name'] = $attributes['father_name'];
            $registration['father_job'] = $attributes['father_job'];
            $registration['mother_name'] = $attributes['mother_name'];
            $registration['mother_job'] = $attributes['mother_job'];
            $registration['permanent_address'] = $attributes['permanent_address'];
            $registration['guardian_name'] = $attributes['guardian_name'];
            $registration['guardian_relation'] = $attributes['guardian_relation'];
            $registration['guardian_job'] = $attributes['guardian_job'];
            $registration['guardian_phone'] = $attributes['guardian_phone'];
            $registration['payment_method'] = $attributes['payment_method'];
            $registration['transaction_id'] = $attributes['transaction_id'];
            $registration['payment_note'] = $attributes['payment_note'] ?? null;
            $registration['agree_rules'] = $attributes['agree_rules'] == "on" ? true : false;

            auth()->user()->update([
                'image' => $registration['profile'],
            ]);

            $user = Auth::user();

            $studentReg = StudentRegistration::create($registration);

            return redirect()->route('ui.home')->with('success', 'Student Registration Form submitted successfully!');
        }

        return back()->with('error', 'ကျောင်းရပ်နားသူဖြစ်သောကြောင့် Registration လုပ်မရပါ');
    }

    public function acceptClasses()
    {
        $years = AcademicYear::all();

        return view('admin.accept.acceptClasses', ['years' => $years]);
    }

    public function stuRegList(Request $request)
    {
        $searchKey = $request->input('student_name');
        $major = $request->input('specialist');
        $query = StudentRegistration::query()->where('status', 'pending')->whereHas('user', function ($query) {
            $query->where('stop', 'no');
        });

        if ($searchKey) {
            $query->whereHas('user', function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }

        if ($major) {
            $query->where('major', $major);
        }

        if ($request->has('no_reg_std')) {
            $query->whereDoesntHave('user');
        }

        $registrations = $query->paginate(10);
        return view('admin.studentRegistation.list', ['registrations' => $registrations]);
    }




    public function stuRegDetail($id)
    {
        $registration = StudentRegistration::find($id);
        return view('admin.studentRegistation.detail', compact('registration'));
    }

    public function showImage($name)
    {
        $image = $name;

        return view('admin.studentRegistation.imageDetail', compact('image'));
    }


    public function stuRegEdit($id)
    {
        $student = StudentRegistration::find($id);
        $years = AcademicYear::all();
        return view('admin.studentRegistation.edit', compact('student', 'years'));
    }

    public function stuRegUpdate($id, Request $request)
    {
        $messages = [
            'parent_phone.required' => 'Parent phone number is required.',
            'parent_phone.regex' => 'ဖုန်းနံပါတ်သည် မြန်မာပြည်တွင်းသုံးဖုန်းနံပါတ်နှင့် ကိုက်ညီမှုရှိရမည်',
            'student_phone.required' => 'Student phone number is required.',
            'student_phone.regex' => 'ဖုန်းနံပါတ်သည် မြန်မာပြည်တွင်းသုံးဖုန်းနံပါတ်နှင့် ကိုက်ညီမှုရှိရမည်',
        ];
        // Validate inputs
        $request->validate([
            // 'student_id' => 'required|exists:users,id',
            'acedmic_year_id' => 'required|exists:academic_years,id',
            'specialist' => 'required',
            'roll_no' => 'required|integer',
            'father_name' => 'required',
            'father_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'father_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mother_name' => 'required',
            'mother_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mother_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_phone' => ['required', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'student_phone' => ['required', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'address' => 'required',
            'student_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'family' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        // Fetch the academic year based on the provided ID
        $academicYear = AcademicYear::findOrFail($request->input('acedmic_year_id'));

        // Check the academic year name and validate the specialist
        $specialist = $request->input('specialist');
        if ($academicYear->name == 'ပထမနှစ်') {
            if ($specialist != 'cst') {
                return redirect()->back()->with('error', 'ပထမနှစ်အတွက် CST ကိုသာရွေးချယ်နိုင်ပါသည်။');
            }
        } else {
            if (!in_array($specialist, ['computer_science', 'computer_technology'])) {
                return redirect()->back()->with('error', 'ဤနှစ်အတွက် CST သို့မဟုတ် CT ကိုသာရွေးချယ်နိုင်ပါသည်။');
            }
        }

        // Handle file uploads
        $fatherNRCImage = $request->file('father_nrc_photo');
        $fatherNRCBackImage = $request->file('father_nrc_back');
        $motherNRCImage = $request->file('mother_nrc_photo');
        $motherNRCBackImage = $request->file('mother_nrc_back');
        $studentNRCImage = $request->file('student_nrc_photo');
        $studentNRCBackImage = $request->file('student_nrc_back');
        $studentImage = $request->file('student_image');
        $familyImage = $request->file('family');
        $paymentImage = $request->file('payment_img');

        $fatherNRCImageName = uniqid() . 'father_' . $fatherNRCImage->getClientOriginalName();
        $fatherNRCBackImageName = uniqid() . 'father_' . $fatherNRCBackImage->getClientOriginalName();
        $motherNRCImageName = uniqid() . 'mother_' . $motherNRCImage->getClientOriginalName();
        $motherNRCBackImageName = uniqid() . 'mother_' . $motherNRCBackImage->getClientOriginalName();
        $studentNRCImageName = uniqid() . 'student_' . $studentNRCImage->getClientOriginalName();
        $studentNRCBackImageName = uniqid() . 'student_' . $studentNRCBackImage->getClientOriginalName();
        $studentImageName = uniqid() . 'student_' . $studentImage->getClientOriginalName();
        $familyImageName = uniqid() . 'family_' . $familyImage->getClientOriginalName();
        $paymentImageName = uniqid() . 'payment_' . $paymentImage->getClientOriginalName();

        // Store images in storage
        $fatherNRCImage->storeAs('public/images', $fatherNRCImageName);
        $fatherNRCBackImage->storeAs('public/images', $fatherNRCBackImageName);
        $motherNRCImage->storeAs('public/images', $motherNRCImageName);
        $motherNRCBackImage->storeAs('public/images', $motherNRCBackImageName);
        $studentNRCImage->storeAs('public/images', $studentNRCImageName);
        $studentNRCBackImage->storeAs('public/images', $studentNRCBackImageName);
        $studentImage->storeAs('public/images', $studentImageName);
        $familyImage->storeAs('public/images', $familyImageName);
        $paymentImage->storeAs('public/images', $paymentImageName);

        // Update the student record
        $student = StudentRegistration::find($id);
        $student->update([
            'academic_year_id' => $request->input('acedmic_year_id'),
            'specialist' => $specialist,
            'roll_no' => $request->input('roll_no'),
            'user_id' => $request->input('user_id'),
            'father_name' => $request->input('father_name'),
            'father_nrc_photo' => $fatherNRCImageName,
            'father_nrc_back' => $fatherNRCBackImageName,
            'mother_name' => $request->input('mother_name'),
            'mother_nrc_photo' => $motherNRCImageName,
            'mother_nrc_back' => $motherNRCBackImageName,
            'student_nrc_photo' => $studentNRCImageName,
            'student_nrc_back' => $studentNRCBackImageName,
            'parent_phone' => $request->input('parent_phone'),
            'student_phone' => $request->input('student_phone'),
            'address' => $request->input('address'),
            'student_image' => $studentImageName,
            'family' => $familyImageName,
            'payment_img' => $paymentImageName,
        ]);

        return redirect()->route('admin.stu.reg.list')->with('success', 'အောင်မြင်စွာပြင်လိုက်ပါပြီ');
    }


    public function stuRegDelete(StudentRegistration $studentRegistration)
    {
        // $da = StudentRegistration::find($id);
        // $stu_id = $da->user_id;
        // try {
        //     $data = User::find($stu_id);

        //     if ($data) {
        //         $data->stop = "yes";
        //         $data->save();
        //         return back()->with('success', 'ကျောင်းသားအား ရပ်နားထားသည်');
        //     } else { 
        //         return back()->with('error', 'ကျောင်းသားကို မတွေ့ပါ');
        //     }
        // } catch (\Exception $e) {
        //     return back()->with('error', 'တစ်စုံတစ်ရာအမှားအယွင်းရှိပါသည်: ' . $e->getMessage());
        // }

        $studentRegistration->update(['status' => 'rejected']);
        return back()->with('success', 'ကျောင်းသား registration ကို ပယ်ဖျက်လိုက်ပါပြီ။');
    }

    public function form()
    {
        $years = AcademicYear::all();
        $students = User::where('role', 'user')->where('stop', 'no')->get();
        return view('admin.studentRegistation.add', compact('years', 'students'));
    }

    public function firstYrStore(Request $request)
    {
        $messages = [
            'parent_phone.required' => 'Parent phone number is required.',
            'parent_phone.regex' => 'ဖုန်းနံပါတ်သည် မြန်မာပြည်တွင်းသုံးဖုန်းနံပါတ်နှင့် ကိုက်ညီမှုရှိရမည်',
            'student_phone.required' => 'Student phone number is required.',
            'student_phone.regex' => 'ဖုန်းနံပါတ်သည် မြန်မာပြည်တွင်းသုံးဖုန်းနံပါတ်နှင့် ကိုက်ညီမှုရှိရမည်',
        ];
        // Validate inputs
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'acedmic_year_id' => 'required|exists:academic_years,id',
            'specialist' => 'required',
            'roll_no' => 'required|integer',
            'father_name' => 'required',
            'father_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'father_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mother_name' => 'required',
            'mother_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mother_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_phone' => ['required', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'student_phone' => ['required', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'address' => 'required',
            'student_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'family' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);
        $academicYearId = $request->input('acedmic_year_id');
        $academicYear = AcademicYear::find($academicYearId);
        $specialist = $request->input('specialist');

        // Validate specialist based on academic year
        if ($academicYear->name === 'ပထမနှစ်' && $specialist !== 'CST') {
            return redirect()->back()->with('error', 'ပထမနှစ်အတွက် CST ကိုသာရွေးချယ်နိုင်ပါသည်။');
        } elseif ($academicYear->name !== 'ပထမနှစ်' && !in_array($specialist, ['computer_science', 'computer_technology'])) {
            return redirect()->back()->with('error', 'ဤနှစ်အတွက် CST သို့မဟုတ် CT ကိုသာရွေးချယ်နိုင်ပါသည်။');
        }

        // Check if student is already registered for the academic year
        $userId = $request->input('student_id');
        $user = User::find($userId);
        if ($user->stop === "no") {
            $existingRegistration = StudentRegistration::where('user_id', $userId)

                ->first();

            if ($existingRegistration) {
                return redirect()->back()->with('error', 'ကျောင်းသားတစ်ယောက်သည် တစ်နှစ်လျှင် စာသင်နှစ်တစ်ခုကိုသာ ကျောင်းအပ်ခွင့်လျှောက်ထားနိုင်သည်။');
            }

            // Handle file uploads
            $fatherNRCImage = $request->file('father_nrc_photo');
            $fatherNRCBackImage = $request->file('father_nrc_back');
            $motherNRCImage = $request->file('mother_nrc_photo');
            $motherNRCBackImage = $request->file('mother_nrc_back');
            $studentImage = $request->file('student_image');
            $studentNRCImage = $request->file('student_nrc_photo');
            $studentNRCBackImage = $request->file('student_nrc_back');
            $familyImage = $request->file('family');
            $paymentImage = $request->file('payment_img');

            $fatherNRCImageName = uniqid() . 'father_' . $fatherNRCImage->getClientOriginalName();
            $fatherNRCBackImageName = uniqid() . 'father_' . $fatherNRCBackImage->getClientOriginalName();
            $motherNRCImageName = uniqid() . 'mother_' . $motherNRCImage->getClientOriginalName();
            $motherNRCBackImageName = uniqid() . 'mother_' . $motherNRCBackImage->getClientOriginalName();
            $studentImageName = uniqid() . 'student_' . $studentImage->getClientOriginalName();
            $familyImageName = uniqid() . 'family_' . $familyImage->getClientOriginalName();
            $PaymentImageName = uniqid() . 'family_' . $paymentImage->getClientOriginalName();
            $studentNRCImageName = uniqid() . 'student_nrc_' . $studentNRCImage->getClientOriginalName();
            $studentNRCBackImageName = uniqid() . 'student_nrc_back_' . $studentNRCBackImage->getClientOriginalName();

            // Store images in storage
            $fatherNRCImage->storeAs('public/images', $fatherNRCImageName);
            $fatherNRCBackImage->storeAs('public/images', $fatherNRCBackImageName);
            $motherNRCImage->storeAs('public/images', $motherNRCImageName);
            $motherNRCBackImage->storeAs('public/images', $motherNRCBackImageName);
            $studentImage->storeAs('public/images', $studentImageName);
            $studentNRCImage->storeAs('public/images', $studentNRCImageName);
            $studentNRCBackImage->storeAs('public/images', $studentNRCBackImageName);
            $familyImage->storeAs('public/images', $familyImageName);
            $paymentImage->storeAs('public/images', $PaymentImageName);

            // Save form data to database
            $studentRegistration = new StudentRegistration([
                'academic_year_id' => $academicYearId,
                'specialist' => $specialist,
                'roll_no' => $request->input('roll_no'),
                'user_id' => $request->input('student_id'),
                'father_name' => $request->input('father_name'),
                'father_nrc_photo' => $fatherNRCImageName,
                'father_nrc_back' => $fatherNRCBackImageName,
                'mother_name' => $request->input('mother_name'),
                'mother_nrc_photo' => $motherNRCImageName,
                'mother_nrc_back' => $motherNRCBackImageName,
                'parent_phone' => $request->input('parent_phone'),
                'student_phone' => $request->input('student_phone'),
                'address' => $request->input('address'),
                'student_nrc_photo' => $studentNRCImageName,
                'student_nrc_back' => $studentNRCBackImageName,
                'student_image' => $studentImageName,
                'family' => $familyImageName,
                'payment_img' => $PaymentImageName,
            ]);

            $studentRegistration->save();

            // Redirect or return response as needed
            return redirect()->route('admin.stu.reg.list')->with('success', 'Student Registration Form submitted successfully!');
        }

        return back()->with('error', 'ကျောင်းရပ်နားသူဖြစ်သောကြောင့် Registration လုပ်မရပါ');
    }



    public function regUpdate(Request $request, $id)
    {
        $messages = [
            'parent_phone.required' => 'Parent phone number is required.',
            'parent_phone.regex' => 'ဖုန်းနံပါတ်သည် မြန်မာပြည်တွင်းသုံးဖုန်းနံပါတ်နှင့် ကိုက်ညီမှုရှိရမည်',
            'student_phone.required' => 'Student phone number is required.',
            'student_phone.regex' => 'ဖုန်းနံပါတ်သည် မြန်မာပြည်တွင်းသုံးဖုန်းနံပါတ်နှင့် ကိုက်ညီမှုရှိရမည်',
        ];
        // Validate inputs
        $request->validate([
            //'student_id' => 'required|exists:users,id',
            'acedmic_year_id' => 'required|exists:academic_years,id',
            'specialist' => 'required',
            'roll_no' => 'required|integer',
            'father_name' => 'required',
            'father_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'father_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mother_name' => 'required',
            'mother_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mother_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_phone' => ['required', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'student_phone' => ['required', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'address' => 'required',
            'student_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_nrc_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_nrc_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'family' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        // Get the academic year name based on the provided academic year ID
        $academicYear = AcademicYear::find($request->input('acedmic_year_id'));

        // Check if the academic year name is 'ပထမနှစ်' and the specialist is 'cst'
        if ($academicYear->name === 'ပထမနှစ်' && $request->input('specialist') !== 'cst') {
            return redirect()->back()->with('error', 'For ပထမနှစ်, only the CST specialist can be chosen.');
        }

        // If the academic year is not 'ပထမနှစ်' but CST is chosen, allow CST or CT
        if ($academicYear->name !== 'ပထမနှစ်' && !in_array($request->input('specialist'), ['computer_science', 'computer_technology'])) {
            return redirect()->back()->with('error', 'Only CST and CT specialists can be chosen for this academic year.');
        }

        $studentRegistration = StudentRegistration::findOrFail($id);

        // Handle file uploads if any new images are provided
        if ($request->hasFile('father_nrc_photo')) {
            $fatherNRCImage = $request->file('father_nrc_photo');
            $fatherNRCImageName = uniqid() . 'father_' . $fatherNRCImage->getClientOriginalName();
            $fatherNRCImage->storeAs('public/images', $fatherNRCImageName);
            $studentRegistration->father_nrc_photo = $fatherNRCImageName;
        }

        if ($request->hasFile('father_nrc_back')) {
            $fatherNRCBackImage = $request->file('father_nrc_back');
            $fatherNRCBackImageName = uniqid() . 'father_' . $fatherNRCBackImage->getClientOriginalName();
            $fatherNRCBackImage->storeAs('public/images', $fatherNRCBackImageName);
            $studentRegistration->father_nrc_back = $fatherNRCBackImageName;
        }

        if ($request->hasFile('mother_nrc_photo')) {
            $motherNRCImage = $request->file('mother_nrc_photo');
            $motherNRCImageName = uniqid() . 'mother_' . $motherNRCImage->getClientOriginalName();
            $motherNRCImage->storeAs('public/images', $motherNRCImageName);
            $studentRegistration->mother_nrc_photo = $motherNRCImageName;
        }

        if ($request->hasFile('mother_nrc_back')) {
            $motherNRCBackImage = $request->file('mother_nrc_back');
            $motherNRCBackImageName = uniqid() . 'mother_' . $motherNRCBackImage->getClientOriginalName();
            $motherNRCBackImage->storeAs('public/images', $motherNRCBackImageName);
            $studentRegistration->mother_nrc_back = $motherNRCBackImageName;
        }

        if ($request->hasFile('student_nrc_photo')) {
            $studentNRCImage = $request->file('student_nrc_photo');
            $studentNRCImageName = uniqid() . 'student_nrc_' . $studentNRCImage->getClientOriginalName();
            $studentNRCImage->storeAs('public/images', $studentNRCImageName);
            $studentRegistration->student_nrc_photo = $studentNRCImageName;
        }

        if ($request->hasFile('student_nrc_back')) {
            $studentNRCBackImage = $request->file('student_nrc_back');
            $studentNRCBackImageName = uniqid() . 'student_nrc_back_' . $studentNRCBackImage->getClientOriginalName();
            $studentNRCBackImage->storeAs('public/images', $studentNRCBackImageName);
            $studentRegistration->student_nrc_back = $studentNRCBackImageName;
        }

        if ($request->hasFile('student_image')) {
            $studentImage = $request->file('student_image');
            $studentImageName = uniqid() . 'student_' . $studentImage->getClientOriginalName();
            $studentImage->storeAs('public/images', $studentImageName);
            $studentRegistration->student_image = $studentImageName;
        }

        if ($request->hasFile('family')) {
            $familyImage = $request->file('family');
            $familyImageName = uniqid() . 'family_' . $familyImage->getClientOriginalName();
            $familyImage->storeAs('public/images', $familyImageName);
            $studentRegistration->family = $familyImageName;
        }

        if ($request->hasFile('payment_img')) {
            $paymentImage = $request->file('payment_img');
            $paymentImageName = uniqid() . 'payment_' . $paymentImage->getClientOriginalName();
            $paymentImage->storeAs('public/images', $paymentImageName);
            $studentRegistration->payment_img = $paymentImageName;
        }

        // Update the remaining fields
        $studentRegistration->academic_year_id = $request->input('acedmic_year_id');
        $studentRegistration->specialist = $request->input('specialist');
        $studentRegistration->roll_no = $request->input('roll_no');
        $studentRegistration->father_name = $request->input('father_name');
        $studentRegistration->mother_name = $request->input('mother_name');
        $studentRegistration->parent_phone = $request->input('parent_phone');
        $studentRegistration->student_phone = $request->input('student_phone');
        $studentRegistration->address = $request->input('address');
        $studentRegistration->status = "pending";

        // Save the updated record to the database
        $studentRegistration->save();

        // Redirect or return response as needed
        return redirect()->route('ui.home')->with('success', 'Student Registration Form updated successfully!');
    }



    public function giveEdit($id)
    {
        $data = StudentRegistration::find($id);
        $data->status = "edit";
        $data->save();
        return back()->with('success', 'ကျောင်းသားအား ကိုယ်တိုင် ပြင်ဆင်ခွင့်ပေးလိုက်ပါပြီ');
    }

    public function regAccept(StudentRegistration $studentRegistration)
    {
        $studentRegistration->update(['status' => 'confirm']);
        Mail::to($studentRegistration->reg_email)->send(new RegistrationSuccessMail($studentRegistration));
        return back()->with('success', 'ကျောင်းအပ် လက်ခံလိုက်ပါပြီ');
    }

    public function exportToWord(Request $request)
    {
        // Get the input parameters from the request
        $searchKey = $request->input('search');
        $academicYearid = $request->input('academic_year_id');
        $specialist = $request->input('specialist'); // Add this line to get the specialist

        // Start building the query
        $query = StudentRegistration::query()
            ->where('status', 'pending')
            ->whereHas('user', function ($query) {
                $query->where('stop', 'no');
            });

        // Apply filters based on input parameters
        if ($searchKey) {
            $query->whereHas('user', function ($query) use ($searchKey) {
                $query->where('name', 'like', '%' . $searchKey . '%');
            });
        }

        if ($academicYearid) {
            // Retrieve the academic year ID based on the name
            $academicYear = AcademicYear::where('id', $academicYearid)->first();
            if ($academicYear) {
                $query->where('academic_year_id', $academicYear->id);
            } else {
                return response()->json(['message' => 'No matching academic year found.'], 404);
            }
        }

        if ($specialist) { // Apply the specialist filter
            $query->where('specialist', $specialist);
        }

        // Execute the query and get the results
        $registrations = $query->get();

        // Create a new Word document
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Add logo and title
        $header = $section->addHeader();
        $header->addImage('user/images/logo.png', [ // Replace with your logo file path
            'width' => 100,
            'height' => 100,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ]);

        $header->addText('Computer University Hinthada', [
            'name' => 'Arial',
            'size' => 24,
            'bold' => true,
            'color' => '#306754'
        ], [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ]);
        $header->addText(
            'ကျောင်းအပ်လက်ခံထားမူ',
            [
                'name' => 'Arial',
                'size' => 12,
                'bold' => true,
                'color' => '#306754'
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]
        );


        if ($registrations->isEmpty()) {
            $section->addText('No results found for the search criteria.');
        } else {
            // Add a table to the document
            $table = $section->addTable();

            // Add table header
            $table->addRow();
            $table->addCell()->addText('စဉ်');
            $table->addCell()->addText('ကျောင်းသားအမည်');
            $table->addCell()->addText('သင်တန်းနှစ်');
            $table->addCell()->addText('ခုံနံပါတ်');
            $table->addCell()->addText('ကျောင်းသား ဖုန်းနံပါတ်');
            $table->addCell()->addText('ကျောင်းအပ်သည့်နေ့စွဲ');
            $table->addCell()->addText('အခြေအနေ');

            // Add data rows
            $counter = 1;
            foreach ($registrations as $registration) {
                $table->addRow();
                $table->addCell()->addText($counter++);
                $table->addCell()->addText($registration->user->name);
                $table->addCell()->addText($registration->academicYear ? $registration->academicYear->name : 'N/A');

                $formattedRollNumber = '';
                if ($registration->specialist === 'computer_science') {
                    $formattedRollNumber = "CS-{$registration->roll_no}";
                } elseif ($registration->specialist === 'computer_technology') {
                    $formattedRollNumber = "CT-{$registration->roll_no}";
                } else {
                    $formattedRollNumber = "CST-{$registration->roll_no}";
                }
                $table->addCell()->addText($formattedRollNumber);
                $table->addCell()->addText($registration->student_phone);
                $table->addCell()->addText($registration->created_at->format('d-m-Y'));
                $table->addCell()->addText($registration->status === "pending" ? "စောင့်ဆိုင်းနေသည်" : ($registration->status === "confirm" ? "ကျောင်းအပ်လက်ခံထားသည်" : "ပြန်ပြင်ခိုင်းထားသည်"));
            }
        }

        // Save the Word document to a temporary file
        $filename = 'student_registration_list.docx';
        $filePath = storage_path('app/public/' . $filename);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);

        // Return the file as a download response
        return response()->download($filePath)->deleteFileAfterSend(true);
    }


    public function aceeptwordDownload(Request $request)
    {
        // Get the input parameters from the request
        $searchKey = $request->input('student_name');
        $academicYearId = $request->input('academic_year_id');
        $specialist = $request->input('specialist');

        // Start building the query
        $query = StudentRegistration::query()
            ->where('status', 'confirm')
            ->whereHas('user', function ($query) {
                $query->where('stop', 'no');
            });

        // Apply filters based on input parameters
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

        // Execute the query and get the results
        $registrations = $query->get();

        // Create a new Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText(
            'ကျောင်းအပ်သူများစာရင်း',
            ['name' => 'Arial', 'size' => 14, 'bold' => true],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]  // Center the text
        );

        if ($registrations->isEmpty()) {
            // If no registrations found, add an empty message
            $section->addText('No results found for the search criteria.');
        } else {
            // Add a table to the document
            $table = $section->addTable();


            // Add table header
            $table->addRow();
            $table->addCell(2000)->addText('စဉ်');
            $table->addCell(4000)->addText('ကျောင်းသားအမည်');
            $table->addCell(4000)->addText('တတ်ရောက်သောနှစ်');
            $table->addCell(4000)->addText('ခုံနံပါတ်');
            $table->addCell(6000)->addText('ဖုန်းနံပါတ်');
            $table->addCell(8000)->addText('ကျောင်းအပ်သည့်နေ့စွဲ');
            $table->addCell(8000)->addText('ကျောင်းအပ်ခြင်းအခြေနေ');

            // Add data rows
            $counter = 1;

            foreach ($registrations as $registration) {
                $table->addRow();
                $table->addCell(2000)->addText($counter++);
                $table->addCell(4000)->addText($registration->user->name);
                $table->addCell(4000)->addText($registration->academicYear->name);

                $rollNumber = $registration->roll_no;
                if ($registration->specialist === 'computer_science') {
                    $formattedRollNumber = "$rollNumber";
                } elseif ($registration->specialist === 'computer_technology') {
                    $formattedRollNumber = "$rollNumber";
                } else {
                    $formattedRollNumber = "$rollNumber";
                }
                $table->addCell(4000)->addText($formattedRollNumber);
                $table->addCell(6000)->addText($registration->phone);
                $table->addCell(8000)->addText($registration->created_at->format('d-m-Y'));
                $table->addCell(8000)->addText($registration->status === "pending" ? "စောင့်ဆိုင်းနေသည်" : ($registration->status === "confirm" ? "ကျောင်းအပ်လက်ခံထားသည်" : "ပြန်ပြင်ခိုင်းထားသည်"));
            }
        }

        // Save the Word document to a temporary file
        $filePath = storage_path('app/registrations.docx');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);

        // Return the file as a download response
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}

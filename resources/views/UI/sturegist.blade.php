@extends('UI.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="wizard-container">
                <div class="card wizard-card" data-color="blue" id="wizardProfile">
                    <form action="{{ route('stu.reg.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="wizard-header text-center">
                            <h3 class="wizard-title">Student Registration System</h3>
                            <p class="category">Please fill in the details accurately</p>
                        </div>

                        <div class="wizard-navigation">
                            <div class="progress-with-circle">
                                <div class="progress-bar" role="progressbar" aria-valuenow="1"
                                     aria-valuemin="1" aria-valuemax="3" style="width: 21%;"></div>
                            </div>
                            <ul>                                                                                  
                                <li><a href="#information" data-toggle="tab"><div class="icon-circle"><i class="ri-user-line" style="position: absolute; bottom: 18px; right: 23px;"></i></div>Information</a></li>
                                <li><a href="#bio" data-toggle="tab"><div class="icon-circle"><i class="ri-info-card-line" style="position: absolute; bottom: 18px; right: 23px;"></i></div>Biography / Family</a></li>
                                <li><a href="#payment" data-toggle="tab"><div class="icon-circle"><i class="ri-wallet-3-line" style="position: absolute; bottom: 18px; right: 23px;"></i></div>Payment</a></li>
                                <li><a href="#rules" data-toggle="tab"><div class="icon-circle"><i class="ri-shield-check-line" style="position: absolute; bottom: 18px; right: 23px;"></i></div>Rules</a></li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <!-- INFORMATION TAB -->
                            <div class="tab-pane" id="information">
                                <div class="row">
                                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                                        <div class="picture-container mb-3">
                                            <div class="picture">
                                                <img src="{{ asset('storage/images/' .  auth()->user()->image) }}" class="picture-src" id="wizardPicturePreview" title="Profile Photo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" />
                                                <input type="file" id="wizard-picture" name="profile" class="mt-2">
                                            </div>
                                            <h6 class="mt-2">Profile Photo</h6>
                                            @error('profile')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="row">
                                            <input type="hidden" name="step" value="1">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ပညာသင်နှစ်</label>
                                                    <input name="academic_year" value="{{ old('academic_year') ?? \Carbon\Carbon::now()->format('Y') . '-' . (\Carbon\Carbon::now()->addYear()->format('y')) }}" type="text" class="form-control" readonly>
                                                    @error('academic_year')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>သင်တန်းနှစ်</label>
                                                    <select name="academic_year_id" class="form-control" id="academic_year_id" required>
                                                        <option value="">-- သင်တန်းနှစ် --</option>
                                                        @foreach($years as $year)
                                                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year ? 'selected' : '' }}>{{ $year->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('academic_year_id')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ခုံနံပတ်</label>
                                                    <input name="roll_no" value="{{ old('roll_no') }}" type="text" class="form-control" placeholder="3CS1-2" required>
                                                    @error('roll_no')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>အထူးပြုဘာသာ</label>
                                                    <select name="major" class="form-control" required>
                                                        <option value="">-- Select Major --</option>
                                                        <option value="CST" {{ old('major') == 'CST' ? 'selected' : '' }}>CST</option>
                                                        <option value="computer science" {{ old('major') == 'computer science' ? 'selected' : '' }}>Computer Science</option>
                                                        <option value="computer technology" {{ old('major') == 'computer technology' ? 'selected' : '' }}>Computer Technology</option>
                                                    </select>
                                                    @error('major')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>                                 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>တက္ကသိုလ်မှက်ပုံတင်အမှက်</label>
                                                    <input name="uni_reg_no" value="{{ auth()->user()->uni_id_no }}" type="text" class="form-control" required disabled>
                                                    @error('uni_reg_no')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- End Information Row -->
                            </div>

                            <!-- BIOGRAPHY / FAMILY INFO TAB -->
                            <div class="tab-pane" id="bio">
                                <div class="row mx-2">
                                    <div class="col-sm-12"><h5 class="info-text">Personal and Family Details</h5></div>

                                    <input type="hidden" name="step" value="2">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>‌‌တက္ကသိုလ်ဝင်တန်း စာမေးပွဲရမှက် (ပုံ)</label>
                                            <input name="matriculation_result" value="{{ old('matriculation_result') }}" class="form-control" type="file" required>
                                            @error('matriculation_result')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ဆယ်တန်းအောင်လက်မှက်</label>
                                            <input name="matriculation_certificate" value="{{ old('matriculation_certificate') }}" class="form-control" type="file" required>
                                            @error('matriculation_certificate')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ပြီးခဲ့သောနှစ် </label>
                                            <select name="last_academic_year" class="form-control" id="last_academic_year" required>
                                                <option value="">-- သင်တန်းနှစ် --</option>
                                                @foreach($years as $year)
                                                    <option value="{{ $year->id }}" {{ old('last_academic_year') == $year ? 'selected' : '' }}>{{ $year->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('last_academic_year')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>အီးမေးလ်</label>
                                            <input name="reg_email" value="{{ old('reg_email') }}" class="form-control" type="reg_email" required>
                                            @error('reg_email')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ဖုန်း</label>
                                            <input name="phone" value="{{ old('phone') }}" class="form-control" type="text" required>
                                            @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>DOB</label>
                                            <input name="dob" value="{{ old('dob') }}" class="form-control" type="date" required>
                                            @error('dob')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>လက်ရှိနေရပ်လိပ်စာ</label>
                                            <input name="present_address" value="{{ old('present_address') }}" class="form-control" type="text" required>
                                            @error('present_address')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- NRC Student -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NRC Student</label>
                                            <input name="nrc_student" value="{{ old('nrc_student') }}" class="form-control" type="text" required>
                                            @error('nrc_student')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NRC Front Photo</label>
                                            <input name="nrc_student_front" class="form-control" type="file">
                                            @error('nrc_student_front')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NRC Back Photo</label>
                                            <input name="nrc_student_back" class="form-control" type="file">
                                            @error('nrc_student_back')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Race, Religion, Blood Type -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>လူမျိုး</label>
                                            <input name="race" value="{{ old('race') }}" class="form-control" type="text" required>
                                            @error('race')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ဘာသာ</label>
                                            <input name="religion" value="{{ old('religion') }}" class="form-control" type="text" required>
                                            @error('religion')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>သွေးအမျိုးအစား</label>
                                            <input name="blood_type" value="{{ old('blood_type') }}" class="form-control" type="text" required>
                                            @error('blood_type')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 my-3">
                                        <hr>
                                    </div>

                                    <!-- Father Info -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>အဘ မည်</label>
                                            <input name="father_name" value="{{ old('father_name') }}" class="form-control" type="text" required>
                                            @error('father_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>NRC Father Front Photo</label>
                                            <input name="nrc_father_front" class="form-control" type="file">
                                            @error('nrc_father_front')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>NRC Father Back Photo</label>
                                            <input name="nrc_father_back" class="form-control" type="file">
                                            @error('nrc_father_back')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>အလုပ်အကိုင်</label>
                                            <input name="father_job" value="{{ old('father_job') }}" class="form-control" type="text" required>
                                            @error('father_job')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Mother Info -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>အမိအမည်</label>
                                            <input name="mother_name" value="{{ old('mother_name') }}" class="form-control" type="text" required>
                                            @error('mother_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>NRC Mother Front Photo</label>
                                            <input name="nrc_mother_front" class="form-control" type="file">
                                            @error('nrc_mother_front')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>NRC Mother Back Photo</label>
                                            <input name="nrc_mother_back" class="form-control" type="file">
                                            @error('nrc_mother_back')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>အလုပ်အကိုင်</label>
                                            <input name="mother_job" value="{{ old('mother_job') }}" class="form-control" type="text" required>
                                            @error('mother_job')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>အိမ်ထောင်စုစာရင်း (ပုံ)</label>
                                            <input name="family_member_docs" value="{{ old('family_member_docs') }}" class="form-control" type="file" required>
                                            @error('family_member_docs')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>အမြဲတမ်းနေရပ်လိပ်စာ</label>
                                            <input name="permanent_address" value="{{ old('permanent_address') }}" class="form-control" type="text" required>
                                            @error('permanent_address')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Guardian Info -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ကျောင်းနေရန်ထောက်ပံ့သူအမည်</label>
                                            <input name="guardian_name" value="{{ old('guardian_name') }}" class="form-control" type="text" required>
                                            @error('guardian_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>တော်စပ်ပုံ</label>
                                            <input name="guardian_relation" value="{{ old('relation') }}" class="form-control" type="text" required>
                                            @error('relation')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>အလုပ်အကိုင်</label>
                                            <input name="guardian_job" value="{{ old('guardian_job') }}" class="form-control" type="text" required>
                                            @error('guardian_job')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ဖုန်း</label>
                                            <input name="guardian_phone" value="{{ old('guardian_phone') }}" class="form-control" type="text" required>
                                            @error('guardian_phone')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div> <!-- End Bio Row -->
                            </div>

                            <!-- PAYMENT TAB -->
                            <div class="tab-pane" id="payment">
                                <div class="row">
                                    <div class="col-sm-12 text-center mb-4">
                                        <h5 class="info-text">Payment Instructions</h5>
                                        <p>Please scan the QR code below using your preferred payment app and upload proof of transaction.</p>
                                    </div>

                                    <!-- QR Code Display -->
                                    <div class="col-md-12 d-flex justify-content-center mb-3">
                                        <img src="{{ asset('user/images/Qr.jpg') }}" alt="Payment QR Code" class="img-fluid mx-2" style="max-width: 300px;">
                                        <img src="{{ asset('user/images/Qr.jpg') }}" alt="Payment QR Code" class="img-fluid mx-2" style="max-width: 300px;">
                                    </div>

                                    <input type="hidden" name="step" value="3">

                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                            <select name="payment_method" class="form-control" required>
                                                <option value="">-- Select Payment Method --</option>
                                                <option value="KBZ Pay" {{ old('payment_method') == 'KBZ Pay' ? 'selected' : '' }}>KBZ Pay</option>
                                                <option value="CB Pay" {{ old('payment_method') == 'CB Pay' ? 'selected' : '' }}>CB Pay</option>
                                                <option value="AYA Pay" {{ old('payment_method') == 'AYA Pay' ? 'selected' : '' }}>AYA Pay</option>
                                                <option value="Wave Pay" {{ old('payment_method') == 'Wave Pay' ? 'selected' : '' }}>Wave Pay</option>
                                            </select>
                                            @error('payment_method')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Transaction Screenshot -->
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <label for="payment_screenshot">Transaction Screenshot <span class="text-danger">*</span></label>
                                            <input type="file" name="payment_screenshot" class="form-control" required>
                                            @error('payment_screenshot')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Transaction ID -->
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <label for="transaction_id">Last 6-Digit Transaction ID <span class="text-danger">*</span></label>
                                            <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" class="form-control" pattern="\d{6}" maxlength="6" required placeholder="e.g. 123456">
                                            <small class="text-muted">Enter the exact last 6-digit transaction ID from your payment receipt.</small>
                                            @error('transaction_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Optional Note -->
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <label for="payment_note">Payment Note (Optional)</label>
                                            <textarea name="payment_note" class="form-control" rows="3" placeholder="Any remarks...">{{ old('payment_note') }}</textarea>
                                            @error('payment_note')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RULES TAB -->
                            <div class="tab-pane" id="rules">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5 class="info-text">ကျောင်းသား၊ ကျောင်းသူများလိုက်နာရန်စည်းကမ်းချက်များ</h5>
                                        <div style="height:300px; overflow-y:auto; border:1px solid #ccc; padding:15px;">
                                            <p>၁။ ကျောင်းသားကျောင်းသူများသည် ပါတီနိုင်ငံရေးကင်းရှင်းစေရန်နဲ့ နိုင်ငံရေးလှုပ်ရှားမှုများပြုလုပ်ခြင်း၊ ဆန္ဒထုပ်ဖော်ခြင်း၊ တရားမဝင်သောအဖွဲစည်းများနင့် ၎င်းတို့နဲ့ပတ်သက်သော လူပုဂ္ဂိုလ်များနှင်းဆက်သွယ်ခြင်း၊ CDM လှုပ်ရှားမှုများပြုလုပ်ခြင်း စသည်တို့တွင် ကိုယ်တိုင်ပါဝင်ဆောင်ရွက်ခြင်း၊ လှုံဆော်ခြင်း၊ သွေးဆောင်ဖျားယောင်းခြင်း မပြလုပ်ကြရန်။</p>
                                            <p>၂။ လူမှုကွန်ယက်စာမျက်နှာများ၊ messageများ၊ Media များတွင်အထက်ပါအကြောင်းအရာများအား ရေးသားခြင်း၊ ဖြန့်ဝေခြင်း၊ လှုံဆော်ခြင်း၊ ခြိမ်းခြောက်ခြင်းများအား မပြုလုပ်ကြရန်။</p>
                                            <p>၃။ မသမာသောနိုင်ငံရေးအမျက်ထုပ်လိုသူများ ကျောင်းဝင်းအတွင်ဝင်မလာစေရေးအတွက် လုံခြုံရေးအရပူပေါင်းဆောင်ရွက်ရန်၊ ကျောင်းဂိတ်ဝတွင်လုံခြုံရေးအစစစ်ဆေးနေသည်ကိုကြည်ဖြူစွာလက်ခံရန်။</p>
                                            <p>၄။ ကျောင်းပရဝန်းအတွင်ကျောင်းသားကျောင်းသူများ ခိုက်ရန်ဖြစ်ပွားခြင်း၊ မူးယစ်စေတက်သောဆေးဝါးများ၊ အရက်သောက်သုံးခြင်း၊ ကွမ်းစားခြင်း၊ လောက်ကစားပြုလုပ်ခြင်း၊ ဖဲရိုက်ခြင်း၊ တရားဉပဒေနှင့်မညီသောကိစ္စများ ဆောင်ရွက်ခြင်းမပြုကြရန်။</p>
                                            <p>၅။ ဉပဒေနှင့်မလျော်ညီသော ကိုင်ဆောင်ခွင်မပြုသောပစ္စည်းများအား အသုံးပြုခြင်း လက်ဝယ်ထားရှိခြင်း သိုလှောင်ထားခြင်း ရောင်းဝယ်ဖောက်ကားခြင်း မပြုကြရန်။</p>
                                            <p>၆။ Covid 19 ကူးစက်ရောဂါအတွက် ချမှက်ထားသောစည်းမျဉ်စည်းကမ်းများကိုလိုက်နာကြရန်။</p>
                                            <p>၇။ ကျောင်းမှသက်မှက်ထားသော အချိန်ဇယာအတိုင်း နေ့စဉ်ကျောင်းတက်ကြရန်။</p>
                                            <p>၈။ ဘာသာရပ်တစ်ခုစီအတွက် သက်မှက်ထားသော အနည်းဆုံးစာသင်ချိန် ပြည့်မှီအောင်တက်ရောက်သင်ကြားကြရန်။</p>
                                            <p>၉။ ဘာသာရပ်ဆိုင်ရာ Tutorial, Practical, Quiz, Project များကို မပျက်မကွက်လုပ်ဆောင်ကြရန်။</p>
                                            <P>၁၀။ ကျောင်းအတွင်း မြန်မာ့ယျဉ်ကျေးမှုနှင်းဆန့်ကျင်သော ဝတ်စားဆင်းယင်မှုများ ဝတ်ဆင်ခြင်းမပြကြရန်။</P>
                                            <p>၁၁။ ကျောင်းအတွင်းမိမိတို့သက်ဆိုင်ရာ ကျောင်းသားကဒ်များ ချိတ်ဆွဲထားကြရန်။</p>
                                            <p>၁၂။ ကျောင်းနံရံများ ကြေညာသင်ပုန်းများနှင်း စာသင်ခုံများတွင် မည်သည်စာရွက်စာတမ်းများနှင့် ရပ်ပုံကိုမျှတပ်ခြင်း၊ ရေးခြစ်ခြင်းမပြုကြရန်။</p>
                                            <p>၁၃။ အေးချမ်းစွာပညာသင်ကြားခြင်းကို အနှောက်ယျက်ဖြစ်စေမည်အော်ဟစ်ခြင်း၊ ဆူညံခြင်း၊ ခိုက်ရန်ဖြစ်ပွားခြင်းများကို မပြုလုပ်ကြရန်။</p>
                                            <p>၁၄။ ကျောင်းအဆောက်အအုံးတွင် မည်သည့်အားကစားကိုမှ မပြုလုပ်ရ သက်မှာထားသော စကားကွင်းနေရာများတွင်သာ အားကစားပြုလုပ်ကြရန်။</p>
                                            <p>၁၅။ ကျောင်းကော်ရီတာ လက်တန်းပေါ်တွင်မထိုင်ရ။</p>
                                            <p>၁၆။ ကျောင်းနှင့် ဒေသဆိုင်ရာအာဏာပိုင်များက အခါအားလျော်စွာထုပ်ပြန်သော အမိန့်၊ ညွှန်ကြားချက်နှင့် စည်းကမ်းများကိုလိုက်နာရမည်။</p>
                                            <p>၁၇။ ဟီရိသြတ္တပတရားကင်းမဲ့သော အပြောအဆို၊ အနေအထိုင်၊ အပြုအမူများကို မပြုလုပ်ကြရန်။</p>
                                            <p>၁၈။ ဆရာ၊ ဆရာမများ၏ သွန်သင်ဆုံးမမှုကို နာခံရမည်။</p>
                                            <!-- Add as many rules as needed -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="step" value="4">
                                    <div class="col-sm-12 text-center">
                                        <div class="checkbox">
                                            <label class="mt-2">
                                                <input type="checkbox" name="agree_rules" required> အထက်ပါလိုက်နာရမည့် စည်းကမ်းချက်များကို လိုက်နာဆောင်ရွက်ပါမည်ဟု ဝန်ခံကတိပြုပါသည်။
                                            </label>
                                            @error('agree_rules')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- END TAB CONTENT -->

                        <div class="wizard-footer">
                            <div class="pull-right">
                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd' name='next' value='Next' />
                                <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd' name='finish' value='Finish' />
                            </div>
                            <div class="pull-left">
                                <input type='button' class='btn btn-previous btn-default btn-wd' name='previous' value='Previous' />
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div> <!-- End Card -->
            </div> <!-- End Wizard Container -->
        </div> <!-- End Col-12 -->
    </div> <!-- End Row -->
</div> <!-- End Container -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var selectedYear,
                selectedSemester;
            $(document).on('change', '#academic_year_id', function() {
                selectedYear = $(this).val();
                if (selectedYear == 1) {
                    showWarningMessage();
                }
            });

            function showWarningMessage() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'ပထနှစ် ပထမနှစ်ဝက်ကျောင်းအပ်ရန် ကျောင်းသိုလာရန်လိုအပ်ပါသည်။',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
@endpush
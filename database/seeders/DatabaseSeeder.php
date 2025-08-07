<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notice;
use App\Models\Semester;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            SemesterSeeder::class
        ]);

        User::create([
            'name' => 'Admin',
            'uuid' => Str::uuid(),
            'email' => 'admin@ucsh.edu.mm',
            'role' => 'admin',
            'password' => 'P@ssw0rd',
            'image' => 'logo.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);

        User::create([
            'name' => 'စိုးဇေယျာကျော်',
            'uuid' => Str::uuid(),
            'email' => 'student1@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၇၆၈၈၉",
            'password' => 'P@ssw0rd',
            'image' => 'logo.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'နိုင်၀င်းကို',
            'uuid' => Str::uuid(),
            'email' => 'student13@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၅၇၉၁၀",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ဟိန်းမင်းထွန်း',
            'uuid' => Str::uuid(),
            'email' => 'student2@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၃၈၆၂၁",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'သော်ဇင်ဖြိုး',
            'uuid' => Str::uuid(),
            'email' => 'student3@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၇၈၃၈၄",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'စုစု',
            'uuid' => Str::uuid(),
            'email' => 'student4@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၅၇၆၉၀",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ခင်ခင်',
            'uuid' => Str::uuid(),
            'email' => 'student5@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၈၂၇၁၆",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ဖြူဖြူ',
            'uuid' => Str::uuid(),
            'email' => 'student6@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၃၂၉၄၁",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ဇင်မင်းထွဠ်',
            'uuid' => Str::uuid(),
            'email' => 'student8@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၆၄၇၉၈",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ရဲမြတ်အောင်',
            'uuid' => Str::uuid(),
            'email' => 'student9@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၅၃၃၈၅",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ဖြိုးမင်း',
            'uuid' => Str::uuid(),
            'email' => 'student10@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၂၂၆၄၈",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ဟိန်းလင်းသန့်',
            'uuid' => Str::uuid(),
            'email' => 'student11@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၃၈၉၀၅",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ပိုင်စိုးလင်း',
            'uuid' => Str::uuid(),
            'email' => 'student12@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၄၆၃၄၇",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ထက်ဝေယံဦး',
            'uuid' => Str::uuid(),
            'email' => 'student51@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၅၈၂၄၉",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'တင်သန့်လွင်',
            'uuid' => Str::uuid(),
            'email' => 'student14@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၈၂၃၅၆",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);
        User::create([
            'name' => 'ထက်အောင်ကျော်',
            'uuid' => Str::uuid(),
            'email' => 'student15@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၇၆၃၂၉",
            'password' => 'P@ssw0rd',
            'image' => 'default.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);

        User::create([
            'name' => 'Student-9',
            'uuid' => Str::uuid(),
            'email' => 'student_9@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၇၆၃၂၉",
            'password' => 'P@ssw0rd',
            'image' => 'logo.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);

        User::create([
            'name' => 'Student-2',
            'uuid' => Str::uuid(),
            'email' => 'student_2@ucsh.edu.mm',
            'role' => 'user',
            //'sch_no'=>"၀၇၆၃၂၉",
            'password' => 'P@ssw0rd',
            'image' => 'logo.jpg',
            'uni_Id_no' => 'CU(Hinthada)' . rand(0000, 9999),
        ]);

        AcademicYear::create([
            "name" => "ပထမနှစ် ပထမနှစ်ဝက်",
            "enrollment" => 35000,
            "ename" => "First Year",
            "esemester" => "First Semester"
        ]);

        AcademicYear::create([
            "name" => "ပထမနှစ် ဒုတိယနှစ်ဝက်",
            "enrollment" => 0,
            "ename" => "First Year",
            "esemester" => "Second Semester"
        ]);

        AcademicYear::create([
            "name" => "ဒုတိယနှစ် ပထမနှစ်ဝက်",
            "enrollment" => 38000,
            "ename" => "Second Year",
            "esemester" => "First Semester"
        ]);

        AcademicYear::create([
            "name" => "ဒုတိယနှစ် ဒုတိယနှစ်ဝက်",
            "enrollment" => 0,
            "ename" => "Second Year",
            "esemester" => "Second Semester"
        ]);

        AcademicYear::create([
            "name" => "တတိယနှစ် ပထမနှစ်ဝက်",
            "enrollment" => 40000,
            "ename" => "Third Year",
            "esemester" => "First Semester"
        ]);

        AcademicYear::create([
            "name" => "တတိယနှစ် ဒုတိယနှစ်ဝက်",
            "enrollment" => 0,
            "ename" => "Third Year",
            "esemester" => "Second Semester"
        ]);
        AcademicYear::create([
            "name" => "စတုတ္ထနှစ် ပထမနှစ်ဝက်",
            "enrollment" => 45000,
            "ename" => "Fourth Year",
            "esemester" => "First Semester"
        ]);

        AcademicYear::create([
            "name" => "စတုတ္ထနှစ် ဒုတိယနှစ်ဝက်",
            "enrollment" => 0,
            "ename" => "Fourth Year",
            "esemester" => "Second Semester"
        ]);

        AcademicYear::create([
            "name" => "ပဉ္စမနှစ် ပထမနှစ်ဝက်",
            "enrollment" => 55000,
            "ename" => "Fifth Year",
            "esemester" => "First Semester"
        ]);
        AcademicYear::create([
            "name" => "ပဉ္စမနှစ် ဒုတိယနှစ်ဝက်",
            "enrollment" => 0,
            "ename" => "Fifth Year",
            "esemester" => "Second Semester"
        ]);
        Notice::create([
            "image" => "logo.png",
            "text" => "ကွန်ပျူတာတက္ကသိုလ်(ဟင်္သာတ)၂၀၂၄-၂၀၂၅ ပညာသင်နှစ်ပထမနှစ်၊ ဒုတိယနှစ်၊ တတိယနှစ်၊ စတုတ္ထနှစ်နှင့် D.C.Sc.(ပထမနှစ်ဝက်နှင့် ဒုတိယနှစ်ဝက်)သင်တန်းများအားလုံးကျောင်းအပ်စတင်လက်ခံမည့်ရက်		-၂၇.၅.၂၀၂၅ (၂ပတ်အတွင်းကျောင်းအပ်ရန်)
            ကျောင်းစတင်ဖွင့်လှစ်မည့်ရက်	-၃.၆.၂၀၂၅"
        ]);
        Notice::create([
            "image" => "logo.png",
            "text" => "တတိယနှစ်(ပထမနှစ်ဝက်)သင်တန်း ကျောင်းအပ်ရန်အတွက်
    လိုအပ်သောစာရွက်စာတမ်းများ
    ၁။ တက္ကသိုလ်ဝင်စာမေးပွဲအောင်လက်မှတ်မိတ္တူ (၁)စုံ
    ၂။ အိမ်ထောင်စုစာရင်းမိတ္တူ(၁)စုံ
    ၃။ (၆)လအတွင်းရိုက်ကူးထားသောပတ်စပို့ဓါတ်ပုံ(အင်္ကျီအဖြူ)	(၅)ပုံ
    ၄။ မှတ်ပုံတင်မိတ္တူ (ကျောင်းသားနှင့်မိဘ(၂)ဦး)(၁)စုံ
    ၅။ ကိုဗစ်ကာကွယ်ဆေးထိုးမှတ်တမ်းမိတ္တူ (၁)စုံ"
        ]);
        Notice::create([
            "image" => "logo.png",
            "text" => "ကျောင်းအပ်ရန်အတွက်လိုအပ်သောစာရွက်စာတမ်းများ(ဒုတိယနှစ်မှ နောက်ဆုံးနှစ်ထိ)

    ၁။ သန်းခေါင်စာရင်းမိတ္တူ(၁)စုံ
    ၂။ (၆)လအတွင်းရိုက်ကူးထားသောပတ်စပို့ဓါတ်ပုံ(အင်္ကျီအဖြူ)	(၅)ပုံ
    ၃။ မှတ်ပုံတင်မိတ္တူ (ကျောင်းသားနှင့်မိဘ(၂)ဦး)(၁)စုံ
    ၄။ ကိုဗစ်ကာကွယ်ဆေးထိုးမှတ်တမ်း"
        ]);
        Notice::create([
            "image" => "logo.png",
            "text" => "Web Development Training သင်တန်းအား(၁.၈.၂၀၂၅)ရက်နေ့မှစ၍ညနေ(၄း၀၀ )နာရီမှ(၅း၃၀)နာရီအထိဖွင့်လှစ်မည်ဖြစ်ပါသဖြင့်သင်တန်းရေးရာဌာနသို့( ၃၁.၇.၂၀၂၅)ရက်နေ့နောက်ဆုံးထား၍စာရင်းပေးသွင်းနိုင်ပါသည်"
        ]);
    }
}

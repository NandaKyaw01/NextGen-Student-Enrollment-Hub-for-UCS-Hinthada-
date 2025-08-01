<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Helper\Facades\File;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function list()
    {
        $notices = Notice::paginate(10);
        return view('admin.Notice.list', compact('notices'));
    }

    public function add()
    {
        return view('admin.Notice.add');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            "image" => "required|image",
            "text" => "required"
        ]);


        $data['image'] = File::NoticeImageUpload($request->file('image'), 'images/notice');

        Notice::create($data);
        return redirect()->route('notice.list')->with('success', 'အောင်မြင်စွာ ဖန်တီးပီးပါပြီ');
    }

    public function edit($id)
    {
        $notice = Notice::find($id);
        return view('admin.Notice.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {

        $data = $request->validate([
            "image" => "nullable|image",
            "text" => "required"
        ]);

        $notice = Notice::find($id);

        if ($request->hasFile('image')) {
            $data['image'] = File::NoticeImageUpload($request->file('image'), 'images/notice');
        } else {
            $data['image'] = $notice->image;
        }


        $notice->update($data);
        return redirect()->route('notice.list')->with('success', 'အောင်မြင်စွာ ပြင်ဆင်ပီးပါပြီ');
    }

    public function delete($id)
    {
        Notice::find($id)->delete();
        return back()->with('success', 'အောင်မြင်စွာဖြတ်လိုက်ပါပြီ');
    }

    public function uiAll()
    {
        $notices = Notice::latest()->get();
        return view('UI.notice', compact('notices'));
    }
}

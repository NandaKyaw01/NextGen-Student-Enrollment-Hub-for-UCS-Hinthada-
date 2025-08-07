@extends('admin.master')
@section('content')
    <div class="page-wrapper mt-0">
        <div class="card bg-dark text-white">
            <img class="w-100" src="{{ asset('user/images/ucsh1.jpg') }}" alt="Card image"
                style="height: 780px; filter: blur(60px); object-fit: cover;">
            <div class="card-img-overlay">
                <div class="content container-fluid">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center gap-3">
                                    <div class="col-10">
                                        <form class="form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
                                            action="{{ route('admin.stu.reg.accept.list.search') }}" method="GET">
                                            @csrf
                                            <div class="input-group mb-2  mx-2">
                                                <input type="text" name="student_name"
                                                    class="form-control border-success small" placeholder="Student Name"
                                                    value="{{ request('student_name') }}">
                                            </div>

                                            <div class="input-group mb-2">
                                                <select name="academic_year_id" class="form-control border-success">
                                                    <option value="">Select Academic Year</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->id }}"
                                                            {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                            {{ $year->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="input-group mb-2 mx-2">
                                                <select name="specialist" class="form-control border-success">
                                                    <option value="">Select Specialist</option>
                                                    <option value="computer_science"
                                                        {{ request('specialist') == 'computer_science' ? 'selected' : '' }}>
                                                        Computer Science</option>
                                                    <option value="computer_technology"
                                                        {{ request('specialist') == 'computer_technology' ? 'selected' : '' }}>
                                                        Computer Technology</option>
                                                    <option value="CST"
                                                        {{ request('specialist') == 'CST' ? 'selected' : '' }}>CST</option>
                                                </select>
                                            </div>

                                            <div class="input-group-append">
                                                <button class="btn wbtn mb-2" type="submit">
                                                    <i class="fas fa-search fa-sm text-white"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-2">
                                        <!-- Updated Download Button URL with parameters -->
                                        <a href="{{ route('admin.registrations.accept.word.download', [
                                            'student_name' => request('student_name'),
                                            'academic_year_id' => request('academic_year_id'),
                                            'specialist' => request('specialist')
                                        ]) }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <!-- Your additional content here -->
                    </div>
                    @if ($regs->isEmpty())
                        <p class="text-danger fw-bold border p-2 rounded text-center my-5">No Registration Accept has found!
                        </p>
                    @else
                        <table class="custom-table border-success table-hover">
                            <thead class="border-success">
                                <tr>
                                    <th  style="font-size: 1.3rem">စဉ်</th>
                                    <th  style="font-size: 1.3rem">အမည်</th>
                                    <th  style="font-size: 1.3rem">သင်တန်းနှစ်</th>

                                    <th  style="font-size: 1.3rem">ခုံနံပါတ်</th>
                                    <th  style="font-size: 1.3rem"> ဖုန်းနံပါတ်</th>

                                    <th  style="font-size: 1.3rem">အခြေအနေ</th>
                                    <th style="font-size: 1.3rem">View</th>
                                </tr>
                            </thead>
                            <tbody class="border-success">
                                @php
                                    $offset = ($regs->currentPage() - 1) * $regs->perPage();
                                @endphp
                                @foreach ($regs as $reg)
                                    <tr>
                                        <td  style="font-size: 1.1rem">{{ $offset + $loop->iteration }}</td>
                                        <td  style="font-size: 1.1rem">{{ $reg->user->name }}</td>
                                        <td  style="font-size: 1.1rem">{{ $reg->academicYear ? $reg->academicYear->name : 'N/A' }}</td>

                                        <td  style="font-size: 1.1rem">
                                            {{-- @if ($reg->specialist === 'computer_science')
                                                CS-{{ $reg->roll_no }}
                                            @elseif ($reg->specialist === 'computer_technology')
                                                CT-{{ $reg->roll_no }}
                                            @else
                                                CST-{{ $reg->roll_no }}
                                            @endif --}}
                                            {{ $reg->roll_no }}
                                        </td>
                                        <td  style="font-size: 1.1rem">{{ $reg->phone }}</td>

                                        <td>
                                            @if ($reg->status === 'pending')
                                                <p  style="font-size: 1.1rem">စောင့်ဆိုင်းနေသည်</p>
                                            @elseif ($reg->status === 'confirm')
                                                <p  style="font-size: 1.1rem">ကျောင်းအပ်လက်ခံထားသည်</p>
                                            @else
                                                <p style="font-size: 1.1rem">ပြန်ပြင်ခိုင်းထားသည်</p>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('admin.stu.reg.detail', $reg->id) }}"><i class="fas fa-eye"
                                                    style="color: azure"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center custom-pagination pagination">
                                {{ $regs->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

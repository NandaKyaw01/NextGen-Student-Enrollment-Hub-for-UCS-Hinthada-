@extends('admin.master')
@section('content')
<div class="page-wrapper mt-0">
    <div class="card bg-dark text-white">
        <img class="w-100" src="{{ asset('user/images/ucsh1.jpg') }}" alt="Card image" style="height: 780px; filter: blur(60px); object-fit: cover;">
        <div class="card-img-overlay">
            <!-- Page Content -->
                <!-- Page Content -->
                <div class="content container-fluid">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-11">
                                    <div class="d-flex justify-content-center gap-3">
                                        <div class="col-12">
                                            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                                <div class="input-group">
                                                    <form action="{{ route('students.list') }}" method="GET">
                                                        @csrf
                                                        <select name="academic_year" class="form-control mx-2 border-success">
                                                            <option value="">Select Academic Year</option>
                                                            @foreach ($academicYears as $key => $year)
                                                                <option value="{{ $year }}"
                                                                    {{ request('academic_year') == $year ? 'selected' : '' }}>
                                                                    {{ $year }}</option>
                                                            @endforeach
                                                        </select>

                                                        <select name="major" class="form-control border-success">
                                                            <option value="">Select Major</option>
                                                            <option value="computer science"
                                                                {{ request('major') == 'computer science' ? 'selected' : '' }}>
                                                                Computer Science</option>
                                                            <option value="computer technology"
                                                                {{ request('major') == 'computer technology' ? 'selected' : '' }}>
                                                                Computer Technology</option>
                                                            <option value="CST"
                                                                {{ request('major') == 'CST' ? 'selected' : '' }}>CST</option>
                                                        </select>

                                                        <select name="academic_class" class="form-control border-success mx-2">
                                                            <option value="">Select Academic Class</option>
                                                            @foreach ($academicClasses as $key => $class)
                                                                <option value="{{ $key }}"
                                                                    {{ request('academic_class') == $key ? 'selected' : '' }}>
                                                                    {{ $class }}</option>
                                                            @endforeach
                                                        </select>

                                                        <select name="is_register" id="" class="form-control border-success mx-2">
                                                            <option value="">Select Registration Status</option>
                                                            <option value="1"
                                                                {{ request('is_register') == '1' ? 'selected' : '' }}>
                                                                Registered</option>
                                                            <option value="0"
                                                                {{ request('is_register') == '0' ? 'selected' : '' }}>
                                                                Not Registered</option>
                                                        </select>

                                                        <input type="text" name="search" class="form-control border-success small"
                                                               aria-label="Search" aria-describedby="basic-addon2" placeholder="Student name"
                                                               value="{{ request('search') }}">


                                                        <div class="input-group-append mx-2">
                                                            <button class="btn wbtn" type="submit">
                                                                <i class="fas fa-search fa-sm text-white"></i>
                                                            </button>
                                                            <a href="{{ route('students.list') }}" class="btn wbtn mx-2 text-white">All</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end my-3 mx-3">
                                        <a href="{{ route('students.export', request()->query()) }}" class="w-auto btn btn-success d-flex align-items-center gap-2">
                                            <i class="fas fa-file-excel"></i>
                                            Export
                                        </a>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="row align-items-center">


                        </div>
                    </div>





                    @if ($students->isEmpty())
                        <p class="text-danger fw-bold border p-2 rounded text-center my-5">No Reviews has found ! </p>
                    @else
                        <table class="custom-table  border-success table-hover">
                            <thead class=" border-success">
                                <tr>
                                    <th  style="font-size: 1.3rem">စဉ်</th>
                                    <th style="font-size: 1.3rem">အမည်</th>
                                    <th style="font-size: 1.3rem">Email</th>
                                    <th style="font-size: 1.3rem">သင်တန်းနှစ်</th>
                                    <th style="font-size: 1.3rem">တက္ကသိုလ် မှက်ပုံတင်အမှက်</th>
                                </tr>
                            </thead>
                            <tbody class=" border-success">
                                @php
                                    $offset = ($students->currentPage() - 1) * $students->perPage();
                                @endphp
                                @foreach ($students as $student)
                                    <tr>
                                        <td style="font-size: 1.1rem">{{ $offset + $loop->iteration }}</td>

                                        <td  style="font-size: 1.1rem">
                                            {{ $student->name }}
                                        </td>

                                        <td style="font-size: 1.1rem">{{ $student->email }}</td>

                                        <td style="font-size: 1.1rem">{{ $student->CurrentUserAcademicYear() ?? '-' }}</td>
                                        <td style="font-size: 1.1rem">{{ $student->uni_id_no }}</td>





                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center custom-pagination pagination">
                                {{ $students->links() }}
                            </div>

            </div>
            @endif
        @endsection


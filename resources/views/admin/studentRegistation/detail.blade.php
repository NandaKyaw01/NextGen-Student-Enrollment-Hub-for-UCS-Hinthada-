@extends('admin.master')

@section('content')
@php
    use App\Helper\Facades\File;
@endphp

<div class="container py-4">
    <h3 class="text-center pcolor mb-4">Student Registration Details</h3>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Personal Information</strong>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->profile) }}" class="profileimg img-thumbnail" style="width: 300px" alt="Profile">
            </div>
            <table class="table custom-table">
                <tr>
                    <th>Name</th>
                    <td>{{ $registration->user->name }}</td>
                    <th>Academic Year</th>
                    <td>{{ $registration->academic_year }}</td>
                </tr>
                <tr>
                    <th>Year</th>
                    <td>{{ $registration->academicYear->name }}</td>
                    <th>Major</th>
                    <td>{{ $registration->major }}</td>
                </tr>
                <tr>
                    <th>Roll No</th>
                    <td>{{ $registration->roll_no }}</td>
                    <th>Phone</th>
                    <td>{{ $registration->phone }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $registration->reg_email }}</td>
                    <th>DOB</th>
                    <td>{{ $registration->dob }}</td>
                </tr>
                <tr>
                    <th>Present Address</th>
                    <td colspan="3">{{ $registration->present_address }}</td>
                </tr>
                <tr>
                    <th>NRC</th>
                    <td>{{ $registration->nrc_student }}</td>
                    <th>Race / Religion</th>
                    <td>{{ $registration->race }} / {{ $registration->religion }}</td>
                </tr>
                <tr>
                    <th>Blood Type</th>
                    <td>{{ $registration->blood_type }}</td>
                    <th>Matriculation Result</th>
                    <td><a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->matriculation_result) }}" target="_blank">View File</a></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <strong>Family Details</strong>
        </div>
        <div class="card-body">
            <table class="table custom-table">
                <tr>
                    <th>Father Name / Job</th>
                    <td>{{ $registration->father_name }} / {{ $registration->father_job }}</td>
                    <th>Mother Name / Job</th>
                    <td>{{ $registration->mother_name }} / {{ $registration->mother_job }}</td>
                </tr>
                <tr>
                    <th>Guardian Name</th>
                    <td>{{ $registration->guardian_name }}</td>
                    <th>Relation</th>
                    <td>{{ $registration->guardian_relation }}</td>
                </tr>
                <tr>
                    <th>Guardian Job</th>
                    <td>{{ $registration->guardian_job }}</td>
                    <th>Guardian Phone</th>
                    <td>{{ $registration->guardian_phone }}</td>
                </tr>
                <tr>
                    <th>Permanent Address</th>
                    <td colspan="3">{{ $registration->permanent_address }}</td>
                </tr>
                <tr>
                    <th>Family Docs</th>
                    <td colspan="3">
                        <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->family_member_docs) }}" target="_blank">View File</a>
                    </td>
                </tr>
            </table>

            {{-- NRC Photos Section --}}
            <h5 class="text-center mt-4 pcolor">Submitted Document</h5>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Student NRC Front</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_student_front) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_student_front) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Student NRC Back</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_student_back) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_student_back) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Father NRC Front</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_father_front) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_father_front) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Father NRC Back</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_father_back) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_father_back) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Mother NRC Front</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_mother_front) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_mother_front) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Mother NRC Back</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_mother_back) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->nrc_mother_back) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Matriculation certificate</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->matriculation_certificate) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->matriculation_certificate) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Last year pass document screenshot</label>
                    <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->last_year_pass_document_screenshot) }}" target="_blank">
                        <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->last_year_pass_document_screenshot) }}" class="img-thumbnail" width="150">
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <strong>Payment Information</strong>
        </div>
        <div class="card-body">
            <table class="table custom-table">
                <tr>
                    <th>Payment Method</th>
                    <td>{{ $registration->payment_method }}</td>
                    <th>Transaction ID</th>
                    <td>{{ $registration->transaction_id }}</td>
                </tr>
                <tr>
                    <th>Payment Screenshot</th>
                    <td colspan="3">
                        <a href="{{ asset(File::GetStudentDataPath($registration->User) . $registration->payment_screenshot) }}" target="_blank">
                            <img src="{{ asset(File::GetStudentDataPath($registration->User) . $registration->payment_screenshot) }}" width="150" class="img-thumbnail">
                        </a>
                    </td>
                </tr>
                @if ($registration->payment_note)
                <tr>
                    <th>Note</th>
                    <td colspan="3">{{ $registration->payment_note }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('admin.stu.reg.list', ['academic_year' => $registration->academic_year_id]) }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
</div>
@endsection

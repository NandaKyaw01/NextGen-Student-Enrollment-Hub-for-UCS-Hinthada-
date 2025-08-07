<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_registrations', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id');
            $table->string('profile');
            $table->string('academic_year');
            $table->foreignId('academic_year_id');
            $table->string('major');
            $table->string('roll_no');

            $table->string('matriculation_result');
            $table->string('matriculation_certificate');
            $table->string('last_academic_year');
            $table->string('reg_email');
            $table->string('phone');
            $table->date('dob');
            $table->text('present_address');

            $table->string('nrc_student');
            $table->string('nrc_student_front')->nullable();
            $table->string('nrc_student_back')->nullable();

            $table->string('race');
            $table->string('religion');
            $table->string('blood_type');

            $table->string('father_name');
            $table->string('nrc_father_front')->nullable();
            $table->string('nrc_father_back')->nullable();
            $table->string('father_job');

            $table->string('mother_name');
            $table->string('nrc_mother_front')->nullable();
            $table->string('nrc_mother_back')->nullable();
            $table->string('mother_job');

            $table->string('family_member_docs');
            $table->text('permanent_address');

            $table->string('guardian_name');
            $table->string('guardian_relation');
            $table->string('guardian_job');
            $table->string('guardian_phone');

            $table->string('payment_method');
            $table->string('payment_screenshot');
            $table->string('transaction_id', 6);
            $table->text('payment_note')->nullable();

            $table->boolean('agree_rules')->default(true);

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->enum('status', ['pending', 'confirm', 'rejected'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};

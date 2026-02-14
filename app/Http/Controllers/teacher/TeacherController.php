<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Teacher;
use App\Models\TeacherClass;
use App\Models\TeacherAttendance;
use Exception;
use App\Models\AcademicSession;
use Carbon\Carbon;
class TeacherController extends Controller
{
    public function saveTeacher(Request $request)
    {


        DB::beginTransaction();

        try {
            // 2. File Uploads





            $request->validate([
                'full_name' => 'required|string|max:100',
                'mobile_no' => 'required|digits:10|unique:users,mobile',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|string|min:6',

                'qualification' => 'required|string',
                'joining_date' => 'required|date',
                'experience' => 'nullable|integer|min:0',
                'gender' => 'required|in:male,female,Male,Female',
                'dob' => 'nullable|date',
                'address' => 'nullable|string',

                'aadhar_no' => 'nullable',

                'academic_session_id' => 'required|exists:academic_sessions,id',
                'class_ids' => 'required|string',
            ]);









            $photoPath = null;


            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_photo_' . $file->getClientOriginalName();
                $file->move(public_path('teachers/photos'), $filename);
                $photoPath = 'teachers/photos/' . $filename;
            }

            $aadharCopyPath = null;
            if ($request->hasFile('aadhar_copy')) {
                $file = $request->file('aadhar_copy');
                $filename = time() . '_aadhar_' . $file->getClientOriginalName();
                $file->move(public_path('teachers/aadhar'), $filename);
                $aadharCopyPath = 'teachers/aadhar/' . $filename;
            }

            $qualificationCertPath = null;
            if ($request->hasFile('qualification_certificate')) {
                $file = $request->file('qualification_certificate');
                $filename = time() . '_cert_' . $file->getClientOriginalName();
                $file->move(public_path('teachers/certificates'), $filename);
                $qualificationCertPath = 'teachers/certificates/' . $filename;
            }

            // 3. Create User
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'mobile' => $request->mobile_no,
                'password' => $request->password,
                'role' => 'teacher',
                'profile_image' => $photoPath,
                'status' => 'active',
            ]);

            // 4. Create Teacher Profile
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'qualification' => $request->qualification,
                'joining_date' => $request->joining_date,
                'experience_years' => $request->experience,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'address' => $request->address,
                'aadhar_number' => $request->aadhar_no,
                'profile_photo' => $photoPath,
                'aadhar_copy' => $aadharCopyPath,
                'qualification_certificate' => $qualificationCertPath,
                'academic_session_id' => $request->academic_session_id,
            ]);

            // 5. Assign Classes (TeacherClass)
            $classIds = json_decode($request->class_ids, true);
            if (is_array($classIds)) {
                foreach ($classIds as $classId) {
                    TeacherClass::create([
                        'teacher_id' => $teacher->id,
                        'class_id' => $classId,
                        'academic_session_id' => $request->academic_session_id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Teacher registered successfully',
                'teacher_id' => $teacher->id
            ], 201);

        } catch (Exception $e) {
            DB::rollback();
            \Log::info('Error saving teacher: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error saving teacher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }








    public function getAllTeachers()
    {
        $teachers = Teacher::with('user', 'classes.class', 'classes.academicSession')->get();
        \Log::info($teachers);
        return response()->json([
            'status' => true,
            'message' => 'Teachers fetched successfully',
            'teachers' => $teachers
        ], 200);
    }

    public function getTeacherById($teacherId)
    {
        $teacher = Teacher::with(['user', 'classes.class'])->find($teacherId);
        \Log::info($teacher);

        if (!$teacher) {
            return response()->json([
                'status' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Teacher fetched successfully',
            'teacher' => $teacher
        ], 200);
    }

    public function updateTeacher(Request $request, $teacherId)
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::find($teacherId);

            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $user = User::find($teacher->user_id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User associated with teacher not found'
                ], 404);
            }

            // Validation (Keep simple for now, can be expanded)
            $request->validate([
                'full_name' => 'required|string|max:100',
                'mobile_no' => 'required|digits:10|unique:users,mobile,' . $user->id,
                'email' => 'nullable|email|unique:users,email,' . $user->id,

                'qualification' => 'required|string',
                'joining_date' => 'required|date',
                'experience' => 'nullable|integer|min:0',
                'gender' => 'required|in:male,female,Male,Female',
                'dob' => 'nullable|date',
                'address' => 'nullable|string',
                'aadhar_no' => 'nullable',
                'academic_session_id' => 'required|exists:academic_sessions,id',
                'class_ids' => 'required|string',
            ]);

            // File Upload Logic
            $photoPath = $teacher->profile_photo;
            if ($request->hasFile('photo')) {
                // Delete old photo if exists? (Optional, but good practice)
                $file = $request->file('photo');
                $filename = time() . '_photo_' . $file->getClientOriginalName();
                $file->move(public_path('teachers/photos'), $filename);
                $photoPath = 'teachers/photos/' . $filename;
            }

            $aadharCopyPath = $teacher->aadhar_copy;
            if ($request->hasFile('aadhar_copy')) {
                $file = $request->file('aadhar_copy');
                $filename = time() . '_aadhar_' . $file->getClientOriginalName();
                $file->move(public_path('teachers/aadhar'), $filename);
                $aadharCopyPath = 'teachers/aadhar/' . $filename;
            }

            $qualificationCertPath = $teacher->qualification_certificate;
            if ($request->hasFile('qualification_certificate')) {
                $file = $request->file('qualification_certificate');
                $filename = time() . '_cert_' . $file->getClientOriginalName();
                $file->move(public_path('teachers/certificates'), $filename);
                $qualificationCertPath = 'teachers/certificates/' . $filename;
            }

            // Update User
            $userData = [
                'name' => $request->full_name,
                'email' => $request->email,
                'mobile' => $request->mobile_no,
                'profile_image' => $photoPath,
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password; // Should ideally be hashed if Model mutator doesn't handle it, but following saveTeacher pattern
            }

            $user->update($userData);

            // Update Teacher
            $teacher->update([
                'qualification' => $request->qualification,
                'joining_date' => $request->joining_date,
                'experience_years' => $request->experience,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'address' => $request->address,
                'aadhar_number' => $request->aadhar_no,
                'profile_photo' => $photoPath,
                'aadhar_copy' => $aadharCopyPath,
                'qualification_certificate' => $qualificationCertPath,
                'academic_session_id' => $request->academic_session_id,
            ]);

            // Sync Classes
            // Delete existing class assignments for this teacher
            TeacherClass::where('teacher_id', $teacher->id)->delete();

            // Add new class assignments
            $classIds = json_decode($request->class_ids, true);
            if (is_array($classIds)) {
                foreach ($classIds as $classId) {
                    TeacherClass::create([
                        'teacher_id' => $teacher->id,
                        'class_id' => $classId,
                        'academic_session_id' => $request->academic_session_id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Teacher updated successfully',
                'teacher' => $teacher
            ], 200);

        } catch (Exception $e) {
            DB::rollback();
            \Log::info('Error updating teacher: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error updating teacher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function deleteTeacher($teacherId)
    {
        $teacher = Teacher::find($teacherId);
        // also delete uploaded file
        if (!$teacher) {
            return response()->json([
                'status' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        // also delete from user table
        $teacher->user()->delete();

        $teacher->delete();
        return response()->json([
            'status' => true,
            'message' => 'Teacher deleted successfully'
        ], 200);
    }







    public function markAttendance(Request $request)
    {
        DB::beginTransaction();

        try {

            $userId = $request->user_id;
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $teacher = Teacher::where('user_id', $userId)->first();
            $active_academic_session_id = AcademicSession::where('is_active', 1)->first()->id;
            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $teacher_id = $teacher->id;

            $request->merge(['teacher_id' => $teacher_id, 'academic_session_id' => $active_academic_session_id]);

            // \Log::info('Marking attendance for teacher ID: ' . $teacher_id);

            $request->validate([
                'teacher_id' => 'required|exists:teachers,id',
                'academic_session_id' => 'required|exists:academic_sessions,id',
                'attendance_date' => 'required|date',
                'status' => 'required|in:present,absent,half_day,leave,outdoor',
                'in_time' => 'nullable',
                'out_time' => 'nullable',
                'remarks' => 'nullable|string|max:255',
                'marked_by' => 'nullable|in:teacher,admin',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
            ]);
            // [2026-01-18 13:13:33] local.INFO: Attendance request: {"user_id":102,"attendance_date":"2026-01-18","out_time":"18:43:31","status":"present","latitude":18.680141,"longitude":73.8513312,"teacher_id":2,"academic_session_id":1}  

            // \Log::info('Attendance request: ' . json_encode($request->all()));
            // Check if attendance already exists for this day
            $existingAttendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
                ->where('attendance_date', $request->attendance_date)
                ->first();

            if ($existingAttendance) {

                $existingAttendance->update([
                    'status' => $request->status,
                    'out_cordinate' => json_encode([$request->latitude, $request->longitude]),
                    'out_time' => $request->input('out_time'),
                    'remarks' => $request->input('remarks'),
                    'marked_by' => $request->input('marked_by', 'teacher'),
                    'academic_session_id' => $request->academic_session_id,
                ]);
                $attendance = $existingAttendance;
            } else {
                // Create new
                $attendance = TeacherAttendance::create([
                    'teacher_id' => $request->teacher_id,
                    'academic_session_id' => $request->academic_session_id,
                    'attendance_date' => $request->attendance_date,
                    'status' => $request->status,
                    'in_cordinate' => json_encode([$request->latitude, $request->longitude]),
                    'in_time' => $request->input('in_time'),
                    'remarks' => $request->input('remarks'),
                    'marked_by' => $request->input('marked_by', 'teacher'),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Attendance marked successfully',
                'attendance' => $attendance
            ], 200);

        } catch (Exception $e) {
            DB::rollback();
            \Log::info('Error marking attendance: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error marking attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }







    public function getTeacherAttendanceByDate(Request $request, $userId)
    {
        try {


            // $attendance_date = $request->attendance_date;
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $teacher = Teacher::where('user_id', $userId)->first();
            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $teacher_id = $teacher->id;

            // Merge teacher_id into request so validation passes
            $request->merge(['teacher_id' => $teacher_id]);



            $attendance = TeacherAttendance::where('teacher_id', $request->teacher_id)
                ->where('attendance_date', $request->attendance_date)
                ->first();

            if ($attendance) {
                return response()->json([
                    'status' => true,
                    'message' => 'Attendance found',
                    'attendance' => $attendance
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Attendance not found'
                ], 404);
            }

        } catch (Exception $e) {
            \Log::info('Error getting attendance: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error getting attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getTeacherAttendanceHistory($userId, Request $request)
    {
        $month = (int) $request->month;
        $year = (int) $request->year;

        // 🔹 Active Academic Session
        $sessionId = AcademicSession::where('is_active', 1)->value('id');

        if (!$sessionId) {
            return response()->json(['message' => 'Academic session not found'], 404);
        }

        // 🔹 Date range
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // 🔹 Teacher
        $teacherId = Teacher::where('user_id', $userId)->value('id');

        if (!$teacherId) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        // 🔹 Attendance records
        $attendance = DB::table('teacher_attendance')
            ->where('teacher_id', $teacherId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get()
            ->keyBy('attendance_date');

        // 🔹 Holidays
        $holidays = DB::table('school_holidays')
            ->where('academic_session_id', $sessionId)
            ->whereBetween('holiday_date', [$startDate, $endDate])
            ->get()
            ->keyBy('holiday_date');

        // 🔹 Weekly offs
        $weeklyOffs = DB::table('weekly_offs')
            ->where('academic_session_id', $sessionId)
            ->where('is_off', 1)
            ->pluck('day_of_week')
            ->toArray();

        $stats = [
            'present' => 0,
            'absent' => 0,
            'leave' => 0,
            'holiday' => 0,
            'weekly_off' => 0,
        ];

        $finalList = [];

        // 🔹 Main Loop
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

            $dateStr = $date->format('Y-m-d');
            $dayName = $date->format('l');

            $status = 'absent';
            $note = '';
            $times = [];
            $in_cordinate = null;
            $out_cordinate = null;

            // Holiday
            if (isset($holidays[$dateStr])) {
                $status = 'holiday';
                $note = $holidays[$dateStr]->holiday_name;
            }
            // Weekly Off
            elseif (in_array($dayName, $weeklyOffs)) {
                $status = 'weekly_off';
                $note = $dayName;
            }

            // Attendance Override
            if (isset($attendance[$dateStr])) {
                $rec = $attendance[$dateStr];
                $status = $rec->status;
                $in_cordinate = $rec->in_cordinate ? json_decode($rec->in_cordinate) : null;
                $out_cordinate = $rec->out_cordinate ? json_decode($rec->out_cordinate) : null;
                $times = [
                    'in' => $rec->in_time,
                    'out' => $rec->out_time,
                ];
            }
            // Future Date
            elseif ($date->isFuture()) {
                $status = '-';
            }

            if (isset($stats[$status])) {
                $stats[$status]++;
            }

            $finalList[] = [
                'date' => $dateStr,
                'day' => $date->format('D'),
                'status' => $status,
                'note' => $note,
                'times' => $times,
                'in_cordinate' => $in_cordinate,
                'out_cordinate' => $out_cordinate,
            ];
        }

        return response()->json([
            'stats' => $stats,
            'attendance_list' => $finalList,
        ]);
    }











}

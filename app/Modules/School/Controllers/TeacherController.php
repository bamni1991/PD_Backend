<?php

namespace App\Modules\School\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\School\Models\User;
use App\Modules\School\Models\Teacher;
use App\Modules\School\Models\TeacherClass;
use App\Modules\School\Models\TeacherAttendance;
use App\Modules\School\Models\School;
use Exception;
use App\Modules\School\Models\AcademicSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Modules\School\Models\TeacherLeave;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        Log::info('Error saving teacher: ' . $e->getMessage());
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
        Log::info($teachers);
        return response()->json([
            'status' => true,
            'message' => 'Teachers fetched successfully',
            'teachers' => $teachers
        ], 200);
    }

    public function getTeacherById($teacherId)
    {
        $teacher = Teacher::with(['user', 'classes.class'])->find($teacherId);
        Log::info($teacher);

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
            Log::info('Error updating teacher: ' . $e->getMessage());
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
        Log::info('Entering markAttendance');
        Log::info($request->all());
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

            $teacher = Teacher::where('user_id', $userId)->with('school')->first();
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

            // ✅ LOCATION VALIDATION - Check if teacher is within allowed radius
            if ($request->latitude && $request->longitude && $teacher->school_id) {
                $school = School::find($teacher->school_id);
                
                if ($school && $school->latitude && $school->longitude) {
                    // Check if teacher is within the allowed radius
                    $isWithinRadius = $school->isWithinRadius($request->latitude, $request->longitude);
                    
                    if (!$isWithinRadius) {
                        // Calculate actual distance for error message
                        $distance = $school->calculateDistance(
                            $school->latitude,
                            $school->longitude,
                            $request->latitude,
                            $request->longitude
                        );
                        
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message' => 'You are outside the allowed radius for marking attendance',
                            'error_code' => 'LOCATION_OUT_OF_RANGE',
                            'details' => [
                                'school_name' => $school->school_name,
                                'allowed_radius' => $school->allowed_radius,
                                'current_distance' => round($distance, 2),
                                'message_marathi' => 'तुम्ही शाळेच्या परवानगी असलेल्या क्षेत्राबाहेर आहात. कृपया शाळेच्या परिसरात या.',
                            ]
                        ], 403);
                    }
                }
            }

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
            Log::info('Error marking attendance: ' . $e->getMessage());
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
            Log::info('Error getting attendance: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error getting attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getTeacherAttendanceHistory($userId, Request $request)
    {

        Log::info('Entering getTeacherAttendanceHistory');
        Log::info('User ID: ' . $userId);
        Log::info('Request Query: ' . $request->getQueryString());

        try {
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
        } catch (Exception $e) {
            Log::info('Error fetching attendance history: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching attendance history',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // CREATE TABLE teacher_leaves (
//     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     teacher_id INT(11) NOT NULL,
//     academic_session_id INT(11) NOT NULL, -- To match attendance table
//     leave_type VARCHAR(50) NOT NULL,
//     start_date DATE NOT NULL,
//     end_date DATE NOT NULL,
//     days_count INT DEFAULT 1,
//     reason TEXT,
//     status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
//     admin_remark TEXT,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//     FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
// );

    // days_count
// : 
// 3
// end_date
// : 
// "2026-01-31"
// leave_type
// : 
// "outdoor"
// reason
// : 
// "Village Visit hhhj"
// start_date
// : 
// "2026-01-29"
// status
// : 
// "pending"
// teacher_id
// : 
// 120
// [[Prototype]]
// : 
// Object

    // applyLeave



    public function applyLeave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'teacher_id' => 'required',
                'leave_type' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'days_count' => 'required',
                'reason' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Resolve Teacher ID
            // Input 'teacher_id' might be a User ID (common in frontend). 
            // We first check if a Teacher exists with this user_id.
            $teacher = Teacher::where('user_id', $request->teacher_id)->first();

            if (!$teacher) {
                // If not found by user_id, check if it's directly a teacher_id
                $teacher = Teacher::find($request->teacher_id);
            }

            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found (checked both User ID and Teacher ID)',
                ], 404);
            }

            // Get Active Academic Session
            $activeSession = AcademicSession::where('is_active', 1)->first();
            if (!$activeSession) {
                return response()->json([
                    'status' => false,
                    'message' => 'Active academic session not found',
                ], 404);
            }

            // Merge correct IDs
            $request->merge([
                'teacher_id' => $teacher->id,
                'academic_session_id' => $activeSession->id
            ]);

            $leave = TeacherLeave::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Leave applied successfully',
                'leave' => $leave,
            ], 200);
        } catch (Exception $e) {
            Log::info('Error applying leave: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error applying leave',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    // {
//   "leaves": [
//     {
//       "id": 1,
//       "start_date": "2024-02-01",
//       "end_date": "2024-02-03",
//       "leave_type": "sick",
//       "status": "approved",
//       "days_count": 3,
//       "reason": "Fever"
//     }
//   ]
// }


    public function getTeacherHistory(Request $request, $userId)
    {
        try {
            // Resolve Teacher 
            // $userId = $request->user_id;
            $yearmonth = $request->month;
            $year = substr($yearmonth, 0, 4);
            $month = substr($yearmonth, 5, 2);
            $teacher = Teacher::where('user_id', $userId)->first();
            Log::info('Teacher leave history fetched successfully' . $userId);
            if (!$teacher) {
                $teacher = Teacher::find($userId);
            }
            Log::info('Teacher leave history fetched successfully' . $teacher->id);

            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found',
                ], 404);
            }

            // Get leaves for this teacher
            $leaves = TeacherLeave::where('teacher_id', $teacher->id)
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month)
                ->orderBy('start_date', 'desc')
                ->get();

            Log::info('Teacher leave history fetched successfully' . $leaves);
            return response()->json([
                'status' => true,
                'message' => 'Teacher leave history fetched successfully',
                'leaves' => $leaves,
            ], 200);

        } catch (Exception $e) {
            Log::info('Error fetching teacher history: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching teacher history',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}



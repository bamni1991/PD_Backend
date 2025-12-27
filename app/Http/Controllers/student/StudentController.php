<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CommonController;
use App\Models\Student;
use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\Guardian;
use App\Models\User;
use App\Models\StudentKit;


use App\Models\KitItem;
use App\Models\Religion;
use App\Models\CasteCategory;
use App\Models\Nationality;
use App\Models\State;
use App\Models\MotherTongue;
use App\Models\ClassFee;
use App\Models\StudentFee;


use Illuminate\Support\Facades\Hash;
class StudentController extends Controller
{

    private $commonController;
    public $ActiveSessionID = false;
    public function __construct(CommonController $commonController)
    {
        $this->commonController = $commonController;
        $activeSession = $commonController->academicSessions();
        $this->ActiveSessionID = $activeSession ? $activeSession->id : null;
    }

    private function firstLatterCapitalize($string)
    {
        return ucfirst(strtolower(trim($string)));
    }








    private function fetchData($model, $id = null, array $extraWhere = [], array $select = [])
    {
        $query = $model::query();
        if (!empty($select)) {
            $query->select($select);
        }

        if ($id !== null) {
            return $query->where('id', $id)->first();
        }

        if (!empty($extraWhere)) {
            $query->where($extraWhere);
        }

        return $query->get(); // Return collection, consistent with Eloquent
    }

    public function getClasses($id = null)
    {
        return $this->fetchData(SchoolClass::class, $id, [], ['id', 'class_name']);
    }

    public function getAcademicSession($id = null)
    {
        $where = ($id === null) ? ['is_active' => 1] : [];
        return $this->fetchData(AcademicSession::class, $id, $where, ['id', 'session_name']);
    }

    public function getKitItems($id = null)
    {
        return $this->fetchData(KitItem::class, $id, [], ['id', 'item_name']);
    }

    public function getReligions($id = null)
    {
        return $this->fetchData(Religion::class, $id, ['is_active' => 1], ['id', 'religion_name']);
    }

    public function getCasteCategories($id = null)
    {
        return $this->fetchData(CasteCategory::class, $id, [], ['id', 'caste_name']);
    }

    public function getNationalities($id = null)
    {
        return $this->fetchData(Nationality::class, $id, [], ['id', 'nationality_name']);
    }

    public function getStates($id = null)
    {
        return $this->fetchData(State::class, $id, ['is_active' => 1], ['id', 'state_name']);
    }

    public function getMotherTongues($id = null)
    {
        return $this->fetchData(MotherTongue::class, $id, ['is_active' => 1], ['id', 'tongue_name']);
    }

    public function getClassFees($id = null)
    {
        $academic_session = AcademicSession::where('is_active', 1)->first();
        $academic_session_id = $academic_session ? $academic_session->id : null;

        return $this->fetchData(ClassFee::class, $id, ['academic_session_id' => $academic_session_id], ['id', 'fee_amount', 'class_id']);
    }

    public function getAcademicSessions($id = null)
    {
        return $this->fetchData(AcademicSession::class, $id, ['is_active' => 1]);
    }

    public function getStudentFromCreatingData()
    {
        try {



            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'classes' => $this->getClasses(),

                    'academic_session' => $this->getAcademicSession(),
                    'kit_items' => $this->getKitItems(),
                    'religions' => $this->getReligions(),
                    'caste_categories' => $this->getCasteCategories(),
                    'nationalities' => $this->getNationalities(),
                    'states' => $this->getStates(),
                    'mother_tongues' => $this->getMotherTongues(),
                    'class_fees' => $this->getClassFees(),
                    'academic_sessions' => $this->getAcademicSession(),

                ],
            ]);
        } catch (Exception $e) {
            \Log::info($e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function saveStudent(Request $request)
    {
        $data = $request->json()->all();
        $request->validate([
            'classId' => 'required|exists:classes,id',
            'admissionDate' => 'required|date',
            'firstName' => 'required',
            'middleName' => 'required',
            'lastName' => 'required',
            'fatherName' => 'required',
            'motherName' => 'required',
            'residentialAddress' => 'required',
            'parentMobile1' => 'required',
            'parentMobile2' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'birthPlace' => 'required',
            'nationality' => 'required',
            'motherTongue' => 'required',
            'state' => 'required',
            'caste' => 'required',
            'fatherOccupation' => 'required',
            'motherOccupation' => 'required',
            'aadharNo' => 'required',
        ]);

        DB::beginTransaction();

        try {

            //   check student name and that sesiion id not exit
            $student = Student::where('student_name', trim($this->firstLatterCapitalize($request->firstName) . ' ' . $this->firstLatterCapitalize($request->middleName) . ' ' . $this->firstLatterCapitalize($request->lastName)))->where('academic_session_id', $this->ActiveSessionID)->first();

            if ($student) {

                return response()->json([
                    'status' => 400,
                    'message' => 'Student already exists',
                ], 400);
            }

            $photoPath = null;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                $fileName = time() . '_' . $photo->getClientOriginalName();

                // This will store the file in: public/students/photos
                $photo->move(public_path('students/photos'), $fileName);

                // Save relative path in DB
                $photoPath = 'students/photos/' . $fileName;
            }

            $birthCertificatePath = null;

            if ($request->hasFile('birthCertificate')) {
                $birthCertificate = $request->file('birthCertificate');

                $fileName = time() . '_' . $birthCertificate->getClientOriginalName();

                // This will store the file in: public/students/birth
                $birthCertificate->move(public_path('students/birth'), $fileName);

                // Save relative path in DB
                $birthCertificatePath = 'students/birth/' . $fileName;
            }

            $aadharCardPath = null;

            if ($request->hasFile('aadharCard')) {
                $aadharCard = $request->file('aadharCard');

                $fileName = time() . '_' . $aadharCard->getClientOriginalName();

                // This will store the file in: public/students/aadhar
                $aadharCard->move(public_path('students/aadhar'), $fileName);

                // Save relative path in DB
                $aadharCardPath = 'students/aadhar/' . $fileName;
            }





            // Handle Parent/Guardian Creation
            $guardian = Guardian::where('mobile', $request->parentMobile1)->first();

            if (!$guardian) {
                // Check if user exists for this mobile
                $user = User::where('mobile', $request->parentMobile1)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $request->fatherName,
                        'email' => $request->parentMobile1 . '@parent.com', // Dummy unique email
                        'password' => $request->parentMobile1,
                        'mobile' => $request->parentMobile1,
                        'role' => 'parent',
                    ]);
                }

                $guardian = Guardian::create([
                    'user_id' => $user->id,
                    'father_name' => $request->fatherName,
                    'mother_name' => $request->motherName,
                    'mobile' => $request->parentMobile1,
                    'alt_mobile' => $request->parentMobile2,
                    'address' => $request->residentialAddress,
                    'father_occupation' => $request->fatherOccupation,
                    'mother_occupation' => $request->motherOccupation,
                    'aadhar_no' => $request->aadharNo,
                ]);
            }

            $student = Student::create([
                'guardian_id' => $guardian->id,
                'student_name' => trim($this->firstLatterCapitalize($request->firstName) . ' ' . $this->firstLatterCapitalize($request->middleName) . ' ' . $this->firstLatterCapitalize($request->lastName)),
                'class_id' => $request->classId,
                'academic_session_id' => $this->ActiveSessionID,
                'religion_id' => $this->commonController->getReligionIDOrName(null, $request->religion),
                'caste_category_id' => $this->commonController->casteCategories(null, $request->caste),
                'gender' => strtolower($request->gender),
                'dob' => $request->dob,
                'birthPlace' => $request->birthPlace,
                'admissionDate' => $request->admissionDate,
                'photo' => $photoPath,
                'aadhar_copy' => $aadharCardPath,
                'birth_certificate' => $birthCertificatePath,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'mobile1' => $request->parentMobile1,
                'mobile2' => $request->parentMobile2,
                'address' => $request->residentialAddress,
                'nationality_id' => $this->commonController->nationalities(null, $request->nationality),
                'state_id' => $this->commonController->getStateIDOrName(null, $request->state),
                'aadharNo' => $request->aadharNo,
                'fatherOccupation' => $request->fatherOccupation,
                'motherOccupation' => $request->motherOccupation,
                'mother_tongue_id' => $this->commonController->getMotherTongueIDOrName(null, $request->motherTongue),
            ]);

            $studentId = $student->id;





            $kitItems = json_decode($request->kitItems, true);

            foreach ($kitItems as $kitItem) {
                StudentKit::create([
                    'student_id' => $studentId,
                    'kit_item_id' => $this->commonController->kit_itemsId(null, $kitItem),
                    'academic_session_id' => $this->ActiveSessionID,
                    'issued_date' => date('Y-m-d'),
                ]);
            }

            if ($request->filled('discount')) {
                StudentFee::create([
                    'student_id' => $studentId,
                    'amount' => $request->discount,
                    'academic_session_id' => $this->ActiveSessionID,
                    'type' => 'discount',
                    'class_fee_id' => $request->classId,
                    'status' => 'paid',
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Student saved successfully',
                'student_id' => $studentId
            ]);

        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Error saving student: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error saving student',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function feachStudentAdminScreenData()
    {
        try {
            $getAllCurentSeeeionIdANdNAme = AcademicSession::Select('session_name as label', 'id as value')->get();
            // opush one entry label all and value '' and frist entry

            $getAllClasses = SchoolClass::Select('class_name as label', 'id as value')->get();

            $getStudentData = Student::join('classes', 'students.class_id', '=', 'classes.id')
                ->select('students.*', 'classes.class_name as className')
                ->orderBy('students.id', 'desc')
                ->get();



            // \Log::info('Data fetched successfully: ' . json_encode($getStudentData));

            $data = [
                'sessions' => $getAllCurentSeeeionIdANdNAme,
                'classes' => $getAllClasses,
                'students' => $getStudentData
            ];
            // \Log::info('Data fetched successfully: ' . json_encode($data));
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => $data

            ]);

        } catch (Exception $e) {
            \Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getStudentById($id)
    {
        try {
            $student = Student::leftJoin('classes', 'students.class_id', '=', 'classes.id')
                ->leftJoin('academic_sessions', 'students.academic_session_id', '=', 'academic_sessions.id')
                ->leftJoin('religions', 'students.religion_id', '=', 'religions.id')
                ->leftJoin('caste_categories', 'students.caste_category_id', '=', 'caste_categories.id')
                ->leftJoin('nationalities', 'students.nationality_id', '=', 'nationalities.id')
                ->leftJoin('states', 'students.state_id', '=', 'states.id')
                ->leftJoin('mother_tongues', 'students.mother_tongue_id', '=', 'mother_tongues.id')

                ->leftJoin('student_fees as discounts', function ($join) {
                    $join->on('students.id', '=', 'discounts.student_id')
                        ->on('students.academic_session_id', '=', 'discounts.academic_session_id')
                        ->where('discounts.type', '=', 'discount');
                })

                ->leftJoin('class_fees', function ($join) {
                    $join->on('students.class_id', '=', 'class_fees.class_id')
                        ->on('students.academic_session_id', '=', 'class_fees.academic_session_id');
                })

                ->select(
                    'students.*',
                    'discounts.amount as discountAmount',
                    'class_fees.fee_amount as feeAmount',

                    'classes.class_name as className',
                    'academic_sessions.session_name as academicSession',
                    'religions.religion_name as religionName',
                    'caste_categories.caste_name as casteCategory',
                    'nationalities.nationality_name as nationalityName',
                    'states.state_name as stateName',
                    'mother_tongues.tongue_name as motherTongue'
                )
                ->where('students.id', $id)
                ->first();




            $kitItems = DB::table('student_kits')
                ->join('kit_items', 'student_kits.kit_item_id', '=', 'kit_items.id')
                ->where('student_kits.student_id', $id)
                ->where('student_kits.academic_session_id', $student->academic_session_id)
                ->select(

                    'kit_items.item_name'

                )
                ->get()->pluck('item_name')->toArray();




            $student->kitItems = $kitItems;





            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Student found',
                'data' => $student,
            ]);
        } catch (Exception $e) {
            \Log::error('Error fetching student: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching student',
                'error' => $e->getMessage(),
            ], 500);
        }



    }













    public function updateStudent(Request $request)
    {
        $request->validate([
            'studentId' => 'required|exists:students,id',
            'classId' => 'required|exists:classes,id',
            'admissionDate' => 'required|date',
            'firstName' => 'required',
            'middleName' => 'required',
            'lastName' => 'required',
            'fatherName' => 'required',
            'motherName' => 'required',
            'residentialAddress' => 'required',
            'parentMobile1' => 'required',
            'parentMobile2' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'birthPlace' => 'required',
            'nationality' => 'required',
            'motherTongue' => 'required',
            'state' => 'required',
            'caste' => 'required',
            'fatherOccupation' => 'required',
            'motherOccupation' => 'required',
            'aadharNo' => 'required',
        ]);

        DB::beginTransaction();

        try {

            // 🔹 Get Student
            $student = Student::where('id', $request->studentId)
                ->where('academic_session_id', $this->ActiveSessionID)
                ->firstOrFail();

            /* -------------------------------------------------
               FILE UPDATES (optional)
            ------------------------------------------------- */

            $photoPath = $student->photo;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('students/photos'), $name);
                $photoPath = 'students/photos/' . $name;
            }

            $birthCertificatePath = $student->birth_certificate;
            if ($request->hasFile('birthCertificate')) {
                $file = $request->file('birthCertificate');
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('students/birth'), $name);
                $birthCertificatePath = 'students/birth/' . $name;
            }

            $aadharCardPath = $student->aadhar_copy;
            if ($request->hasFile('aadharCard')) {
                $file = $request->file('aadharCard');
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('students/aadhar'), $name);
                $aadharCardPath = 'students/aadhar/' . $name;
            }



            $guardian = Guardian::find($student->guardian_id);

            if ($guardian) {

                // 🔹 Check if parent mobile changed
                if ($guardian->mobile !== $request->parentMobile1) {

                    // 🔸 Check mobile already exists in users table
                    $existingUser = User::where('mobile', $request->parentMobile1)
                        ->where('id', '!=', $guardian->user_id)
                        ->first();

                    if ($existingUser) {
                        throw new \Exception('This mobile number is already linked to another parent');
                    }

                    // 🔸 Update users table mobile
                    User::where('id', $guardian->user_id)->update([
                        'mobile' => $request->parentMobile1,
                        'name' => $request->fatherName,
                        'email' => $request->parentMobile1 . '@parent.com',
                    ]);
                }

                // 🔹 Update guardian details
                $guardian->update([
                    'father_name' => $request->fatherName,
                    'mother_name' => $request->motherName,
                    'mobile' => $request->parentMobile1,
                    'alt_mobile' => $request->parentMobile2,
                    'address' => $request->residentialAddress,
                    'father_occupation' => $request->fatherOccupation,
                    'mother_occupation' => $request->motherOccupation,
                    'aadhar_no' => $request->aadharNo,
                ]);
            }

            /* -------------------------------------------------
               STUDENT UPDATE
            ------------------------------------------------- */

            $student->update([
                'class_id' => $request->classId,
                'student_name' => trim(
                    $this->firstLatterCapitalize($request->firstName) . ' ' .
                    $this->firstLatterCapitalize($request->middleName) . ' ' .
                    $this->firstLatterCapitalize($request->lastName)
                ),
                'religion_id' => $this->commonController->getReligionIDOrName(null, $request->religion),
                'caste_category_id' => $this->commonController->casteCategories(null, $request->caste),
                'gender' => strtolower($request->gender),
                'dob' => $request->dob,
                'birthPlace' => $request->birthPlace,
                'admissionDate' => $request->admissionDate,
                'photo' => $photoPath,
                'aadhar_copy' => $aadharCardPath,
                'birth_certificate' => $birthCertificatePath,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'mobile1' => $request->parentMobile1,
                'mobile2' => $request->parentMobile2,
                'address' => $request->residentialAddress,
                'nationality_id' => $this->commonController->nationalities(null, $request->nationality),
                'state_id' => $this->commonController->getStateIDOrName(null, $request->state),
                'mother_tongue_id' => $this->commonController->getMotherTongueIDOrName(null, $request->motherTongue),
                'fatherOccupation' => $request->fatherOccupation,
                'motherOccupation' => $request->motherOccupation,
                'aadharNo' => $request->aadharNo,
            ]);

            /* -------------------------------------------------
               KIT ITEMS (DELETE + INSERT)
            ------------------------------------------------- */

            StudentKit::where('student_id', $student->id)
                ->where('academic_session_id', $this->ActiveSessionID)
                ->delete();

            $kitItems = json_decode($request->kitItems, true);

            if (is_array($kitItems)) {
                foreach ($kitItems as $kitItem) {
                    StudentKit::create([
                        'student_id' => $student->id,
                        'kit_item_id' => $this->commonController->kit_itemsId(null, $kitItem),
                        'academic_session_id' => $this->ActiveSessionID,
                        'issued_date' => now()->format('Y-m-d'),
                    ]);
                }
            }

            /* -------------------------------------------------
               DISCOUNT (UPDATE OR CREATE)
            ------------------------------------------------- */

            if ($request->filled('discount')) {
                StudentFee::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'academic_session_id' => $this->ActiveSessionID,
                        'type' => 'discount',
                    ],
                    [
                        'amount' => $request->discount,
                        'class_fee_id' => $request->classId,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Student updated successfully',
                'student_id' => $student->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Student update failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error updating student',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getUserFeesHistoryData($studentId)
    {
        try {
            // 1️⃣ Validate student
            $student = Student::find($studentId);
            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            // 2️⃣ Get active academic session
            $academicSessionId = AcademicSession::where('is_active', 1)->value('id');

            // 3️⃣ Get class fees
            $classId = $student->class_id;
            $classFee = $this->getClassFees($classId);

            // 4️⃣ Get fees transactions
            $transactions = StudentFee::where('student_id', $studentId)
                ->where('academic_session_id', $academicSessionId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => (string) $row->id,
                        'date' => $row->created_at
                            ? \Carbon\Carbon::parse($row->created_at)->format('Y-m-d')
                            : null,
                        'amount' => (float) $row->amount,
                        'type' => $row->type === 'discount'
                            ? 'Discount'
                            : 'Fees',
                        'mode' => $row->mode ?? 'N/A',
                        'status' => ucfirst($row->status),
                        'receiptNo' => 'REC-' . str_pad($row->id, 5, '0', STR_PAD_LEFT),
                        'paidBy' => $row->paidBy ?? 'N/A',
                        'collectedBy' => $row->collectedBy ?? 'N/A',
                    ];
                });

            // 5️⃣ Final response (MATCHES FRONTEND EXPECTATION)
            return response()->json([
                'status' => true,
                'data' => [
                    'totalClassFees' => (float) ($classFee->fee_amount ?? 0),
                    'transactions' => $transactions,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Fees history error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error fetching fees history',
            ], 500);
        }
    }


    public function collectFees(Request $request)
    {
        try {
            // {"student_id":1,"date":"2025-12-27","amount":3000,"type":"fees","mode":"Cash","status":"Paid","paidBy":"Paid by","collectedBy":"School Admin","remarks":null}  
// CREATE TABLE `student_fees` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `student_id` int(11) NOT NULL,
//   `class_fee_id` int(11) NOT NULL,
//   `academic_session_id` int(11) NOT NULL,
//   `amount` decimal(10,2) NOT NULL,
//   `type` enum('fees','discount') NOT NULL DEFAULT 'fees',
//   `status` enum('pending','paid') DEFAULT 'pending',
//   `paidBy` varchar(256) DEFAULT NULL,
//   `collectedBy` varchar(256) DEFAULT NULL,
//   `mode` varchar(256) DEFAULT NULL,
//   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
//             \Log::info('collectFees: ' . json_encode($request->all()));

            $validateData = $request->validate([
                'student_id' => 'required|exists:students,id',

                'amount' => 'required|numeric',
                'type' => 'required|in:fees,discount',

            ]);

            $student = Student::find($validateData['student_id']);
            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            $classFee = $this->getClassFees($student->class_id);

            $studentFee = StudentFee::create([
                'student_id' => $student->id,
                'class_fee_id' => $classFee->id,
                'academic_session_id' => $this->ActiveSessionID,
                'amount' => $validateData['amount'],
                'type' => $validateData['type'],
                'status' => 'paid',
                'paidBy' => $request->paidBy,
                'collectedBy' => $request->collectedBy,
                'mode' => $request->mode,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Fees collected successfully',
                'student_fee_id' => $studentFee->id,
            ]);

        } catch (\Exception $e) {
            \Log::error('collectFees error: ' . $e->getMessage());
        }
    }

    public function updateFees(Request $request, $studentFeeId)
    {
        try {

            if ($studentFeeId) {
                $studentFee = StudentFee::find($studentFeeId);
                if (!$studentFee) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Student fee not found',
                    ], 404);
                }
            }
            $validateData = $request->validate([
                'student_id' => 'required|exists:students,id',

                'amount' => 'required|numeric',
                'type' => 'required|in:fees,discount',

            ]);

            $student = Student::find($validateData['student_id']);
            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            $classFee = $this->getClassFees($student->class_id);

            // $studentFee = StudentFee::find($studentFeeId);
            $studentFee->update([
                'student_id' => $student->id,
                'class_fee_id' => $classFee->id,
                'academic_session_id' => $this->ActiveSessionID,
                'amount' => $validateData['amount'],

                'status' => 'paid',
                'paidBy' => $request->paidBy,
                'collectedBy' => $request->collectedBy,
                'mode' => $request->mode,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Fees collected successfully',
                'student_fee_id' => $studentFee->id,
            ]);

        } catch (\Exception $e) {
            \Log::error('updateFees error: ' . $e->getMessage());
        }
    }



    public function deleteFees($studentFeeId)
    {
        try {
            $studentFee = StudentFee::find($studentFeeId);
            if (!$studentFee) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student fee not found',
                ], 404);
            }
            $studentFee->delete();
            return response()->json([
                'status' => true,
                'message' => 'Fees deleted successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('deleteFees error: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentMarks;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('student.student-list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = User::all(); // dd($teachers);
        return view('student.student-add', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchStudents(Request $request)
    {
        $students = Student::join('users as teachers', 'teachers.id', '=', 'students.reporting_teacher')
                            ->select('students.id', 'students.name', 'students.age', 'students.gender', 'teachers.name as reporting_teacher')
                            ->get();

        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => count($students),
            'recordsFiltered' => count($students),
            'data' => $students,
        );
        // dd($homeworks);
        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validatedData = \Validator::make($request->all(), [
                'name' => 'required|max:50',
                'age' => 'required|min:0|numeric',
                'gender' => 'required|in:M,F',
                'reporting_teacher' => 'required'
            ]);

            if ($validatedData->fails()) {
                return response()->json(['status' => false, 'message' => $validatedData->messages()->first()], 200);
            }

            $student = new Student;
            $student->name = $request->name;
            $student->age = $request->age;
            $student->gender = $request->gender;
            $student->reporting_teacher = $request->reporting_teacher;
            $student->save();

            return response()->json([
                'status' => true,
                'message' => 'Student saved'
            ], 200);

        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()->first()
            ], 200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    { 
        $teachers = User::all();

        $studentDetails = Student::find($request->id);

        return view('student.student-edit', compact('teachers', 'studentDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $student = Student::find($request->hiddenid);

        if($student) {

            $student->name = $request->name;//(["name" => $request->name, "age" => $request->age, "gender" => $request->gender, "reporting_teacher" => $request->reporting_teacher]);
            $student->age = $request->age;
            $student->gender = $request->gender;
            $student->reporting_teacher = $request->reporting_teacher;
            $student->save(); 
            
            return redirect('/students')->with('success', 'Updated successfully');

        } else {
            return redirect()->back()->with('message', 'Some error occured');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $studentId = $request->id;

            StudentMarks::where('student_id', '=', $studentId)->delete();
    
            Student::find($studentId)->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Deleted'
            ], 200);
    
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()->first()
            ], 200);    
        }
    }
}

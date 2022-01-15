<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use DataTables;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $companies = Company::all();
        return view('employees.index', compact(['companies']));
    }

    public function getEmployees(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if ($request->has('company') && $request->company) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['company_id'], $request->get('company'));
                        });
                    }
                })
                ->addColumn('company', function ($row){
                    return $row->company->name;
                })
                ->addColumn('image', function($row){
                    $path = asset('storage/uploads/employees/' . $row->image) ;
                    return '<img src="' . $path . ' " width="50px" height="50px">';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a id="editEmployee" href="javascript:void(0)" value="'. $row->id .'" class="edit btn btn-success btn-sm">Edit</a>
                    <a id="deleteEmployee" href="javascript:void(0)" value="'. $row->id .'" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email',
            'password' => 'min:8|required_with:confirmPassword|same:confirmPassword',
            'confirmPassword' => 'min:8',
            'company' => 'required'
        ]);
        $employee = new Employee;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $employee->company_id = $request->company;

        if ($request->file('employeeImage')) {
            $image = $request->file('employeeImage');
            $imageName = time().'.jpg';
            $request->file('employeeImage')->storeAs('uploads/employees', $imageName, 'public');
            $employee->image = $imageName;
        }
        $employee->save();

        \Mail::to($request->email)->send(new \App\Mail\HRMMail($request->email));
        return response()->json(['success'=>'Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'editName'          => 'required',
            'editEmail'         => 'required|email',
            'editPassword' => 'min:8|required_with:editConfirmPassword|same:editConfirmPassword',
            'editConfirmPassword' => 'min:8',
            'editCompany' => 'required'
        ]);
        $employee = Employee::find($id);
        $employee->name = $request->editName;
        $employee->email = $request->editEmail;
        $employee->password = Hash::make($request->editPassword);
        $employee->company_id = $request->editCompany;

        if ($request->file('editEmployeeImage')) {
            $image = $request->file('editEmployeeImage');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $request->file('editEmployeeImage')->storeAs('uploads/employees', $imageName, 'public');
            $employee->image = $imageName;
        }
        $employee->save();

        return response()->json(['success'=>'Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}

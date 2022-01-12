<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('companies.index');
    }

    public function getCompanies(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function($row){
                    $path = asset('storage/uploads/companies/' . $row->logo) ;
                    return '<img src="' . $path . ' " width="50px" height="50px">';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a id="editCompany" href="javascript:void(0)" value="'. $row->id .'" class="edit btn btn-success btn-sm">Edit</a>
                    <a id="deleteCompany" href="javascript:void(0)" value="'. $row->id .'" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['logo', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
            'address'         => 'required',
        ]);
        $company = new Company();
        $company->name = $request->name;
        $company->address = $request->address;

        if ($request->file('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $request->file('logo')->storeAs('uploads/companies', $imageName, 'public');
            $company->logo = $imageName;
        }
        $company->save();

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
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'editName'          => 'required',
            'editAddress'         => 'required',
        ]);
        $company = Company::find($id);
        $company->name = $request->editName;
        $company->address = $request->editAddress;

        if ($request->file('editLogo')) {
            $image = $request->file('editLogo');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $request->file('editLogo')->storeAs('uploads/companies', $imageName, 'public');
            $company->logo = $imageName;
        }
        $company->save();

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
        Company::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}

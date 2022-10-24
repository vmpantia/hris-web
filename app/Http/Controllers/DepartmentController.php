<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(){
        return view('admin.department');
    }

    ### PUBLIC FUNCTIONS ###
    public function getDepartments() {

        try {
            //Get all departments in database
            $result = $this->getData();
            return response()->json(['data' => $result], 200);
        }
        catch(Exception $ex) { 
            return response()->json(['data' => $result], 422);
        }
    }

    public function searchDepartments($keyword) { 

        try {
            //Get specific departments in database based on $search value
            $result = $this->getData($keyword);
            return response()->json(['data' => $result], 200);
        }
        catch(Exception $ex) { 
            return response()->json(['data' => $result], 422);
        }

    }

    public function saveDepartment(DepartmentRequest $request) {

        try {
            //Create transaction and hold tables
            \DB::beginTransaction();

            //Check if Add or Update
            if(isset($request->$id)) { 
                //Update record in database
                Department::firstWhere('id', $request->$id)->update($request->all());
            }
            else {
                //Add record in database
                Department::create($request->all());
            } 

            //Commit changes in database
            \DB::commit();
            return response()->json(['data' => 'success'], 200);
        }        
        catch(Exception $ex) { 
            //Revert all changes in database
            \DB::rollBack();
            return response()->json(['err_msg' => $ex], 422);
        }
    }

    ### PRIVATE FUNCTIONS ###
    private function getData($keyword = null){
        return Department::query()
            ->when($keyword !== null, function($query) use ($keyword) {
                $query->where('dep_name', 'LIKE', '%' . $keyword . '%');
            })
            ->get();
    }
}

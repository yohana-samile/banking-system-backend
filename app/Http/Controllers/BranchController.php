<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Branch;

    class BranchController extends Controller {
        public function index(){
            $branches = Branch::all();
            return response()->json($branches);
        }

        public function store(Request $request){
            $store = Branch::create($request->all());
            return response()->json(["success" => "new branch created"], 201);
        }

        public function show(Request $request, $id){
            $branch = Branch::findOrFail($id);
            if (!empty($branch)) {
                return response()->json($branch);
            }
            else{
                return response()->json(["error" => "branch not found"]);
            }
        }

        public function update(Request $request, $id){
            $branch = Branch::findOrFail($id);
            $branch->update($request->all());
            return response()->json($branch, 200);
        }

        public function destroy(Request $request, $id){
            $branch = Branch::findOrFail($id);
            $branch->delete($branch);
            return response()->json($branch, 200);
        }
    }


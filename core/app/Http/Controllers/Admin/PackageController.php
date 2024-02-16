<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller{

    public function package(){
        $pageTitle = 'Manage Package';
        $packages = Package::latest()->paginate(getPaginate());
        return view('admin.package.index', compact('pageTitle' , 'packages'));
    }

    public function add(Request $request){

        $request->validate([
            'name'=> 'required|max:250|string|unique:packages,name',
            'price'=> 'required|gt:0|numeric',
            'validity'=> 'required|gt:0|integer',
            'status' => 'sometimes|in:on',
            'features' => 'required|max:60000',
        ]);

        $newPackage = new Package();
        $newPackage->name = $request->name;
        $newPackage->price = $request->price;
        $newPackage->validity = $request->validity;
        $newPackage->status = isset($request->status) ? 1 : 0;
        $newPackage->features = json_encode($request->features, true);
        $newPackage->save();

        $notify[] = ['success', 'Package Created Successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request){

        $request->validate([
            'name'=> 'required|max:250|string|unique:packages,name,'.$request->id,
            'id'=> 'required|exists:packages,id',
            'price'=> 'required|gt:0|numeric',
            'validity'=> 'required|gt:0|integer',
            'status' => 'sometimes|in:on',
            'features' => 'required|max:60000',
        ]);

        $findPackage = Package::find($request->id);
        $findPackage->name = $request->name;
        $findPackage->price = $request->price;
        $findPackage->validity = $request->validity;
        $findPackage->status = isset($request->status) ? 1 : 0;
        $findPackage->features = json_encode($request->features, true);
        $findPackage->save();

        $notify[] = ['success', 'Package Updated Successfully'];
        return back()->withNotify($notify);
    }

}

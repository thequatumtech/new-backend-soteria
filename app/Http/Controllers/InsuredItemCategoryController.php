<?php

namespace App\Http\Controllers;

use App\Models\InsuredItemCategory;
use Illuminate\Http\Request;

class InsuredItemCategoryController extends Controller
{
    public function index()
    {
        $categories = InsuredItemCategory::all();
        return view('admin.insured_item_category.list', ['categories' => $categories]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:insured_item_categories',
        ]);

        InsuredItemCategory::create($request->all());

        return redirect()->route('insured-items-categories.list')->with('success', __('messages.table_headers.insured_item_category_created'));
    }

    public function edit($id)
    {
        $categories = InsuredItemCategory::findOrFail($id);
        return response()->json([
            'categories' => $categories,
            'status' => 200
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'insured_item_category_id' => 'required|numeric|exists:insured_item_categories,id',
            'name' => 'required|string|max:50|unique:insured_item_categories,name,'.$request->insured_item_category_id,
        ]);

        $category = InsuredItemCategory::findOrFail($request->insured_item_category_id);
        $category->name = $request->name;
        $category->save();

        return redirect()->route('insured-items-categories.list')->with('success', __('messages.table_headers.insured_item_category_updated'));
    }

    public function destroy(Request $request)
    {
        $category = InsuredItemCategory::findOrFail($request->delete_insured_item_category_id);
//        VehicleCategory::where('insured_item_category_id', $vehicleBrand->id)->delete();
        $category->delete();

        return redirect()->route('insured-items-categories.list')->with('success', __('messages.table_headers.insured_item_category_deleted'));
    }

}

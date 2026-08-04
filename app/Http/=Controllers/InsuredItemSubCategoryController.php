<?php

namespace App\Http\Controllers;

use App\Models\InsuredItemCategory;
use App\Models\InsuredItemSubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InsuredItemSubCategoryController extends Controller
{
    public function index()
    {
        $all_categories = InsuredItemCategory::all();
        $all_sub_categories = InsuredItemSubCategory::all();
        return view('admin.insured_item_sub_category.list',compact('all_categories','all_sub_categories'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'insured_item_category_id' => [
                    'required',
                    'integer',
                ],
                'name' => [
                    'required',
                      'max:100',
                ]
            ]);
            $sub_category = new InsuredItemSubCategory();
            $sub_category->insured_item_category_id = $request->insured_item_category_id;
            $sub_category->name = $request->name;
            $sub_category->save();

            return back()->with('success', __('messages.insured_item_sub_categories.created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return back()->with('error', __('messages.insured_item_sub_categories.error'));
            // Handle errors here
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'insured_item_sub_category_id' => [
                    'required',
                    'integer',
                ],
                'insured_item_category_id' => [
                    'required',
                    'integer',
                ],
                'name' => [
                    'required',
                      'max:100',
                ]
            ]);
            $insured_item_sub_category = InsuredItemSubCategory::find($request->insured_item_sub_category_id);
            $insured_item_sub_category->insured_item_category_id = $request->insured_item_category_id;
            $insured_item_sub_category->name = $request->name;
            $insured_item_sub_category->save();

            return back()->with('success', __('messages.insured_item_sub_categories.updated'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return back()->with('error', __('messages.insured_item_sub_categories.error'));
            // Handle errors here
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'insured_item_sub_category_id' => [
                    'required',
                    'integer',
                ]
            ]);
            InsuredItemSubCategory::find($request->insured_item_sub_category_id)->delete();
            return back()->with('success', __('messages.insured_item_sub_categories.deleted'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return back()->with('error', __('messages.insured_item_sub_categories.delete_error'));
            // Handle errors here
        }
    }

}

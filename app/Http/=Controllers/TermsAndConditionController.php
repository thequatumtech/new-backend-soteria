<?php

namespace App\Http\Controllers;

use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TermsAndConditionController extends Controller
{
    public function index()
    {
        $termsAndCondition = TermsAndCondition::first();
        if(empty($termsAndCondition)){
            $termsAndCondition = new \stdClass();
            $termsAndCondition->id = '';
            $termsAndCondition->message = "";
        }
        return view('content.termsAndCondition.termsAndCondition', ['termsAndCondition' => $termsAndCondition]);
    }

    public function terms(Request $request)
    {
        $termsAndCondition = TermsAndCondition::first();
        if($termsAndCondition){
            if(!empty($termsAndCondition->message)){
                return view('content.termsAndCondition.terms',compact('termsAndCondition'));
            } else if(!empty($termsAndCondition->file)){
                $filePath = public_path('uploads/terms_and_conditions/' . $termsAndCondition->file);
                if (file_exists($filePath)) {
                    $fileContent = file_get_contents($filePath);
                    $mimeType = mime_content_type($filePath);

                    return response($fileContent, 200)
                        ->header('Content-Type', $mimeType);
                }
            }
        }
        abort(404);
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required_without:terms_file',
                'terms_file' => 'required_without:message|file|mimes:jpg,jpeg,png,gif,bmp,pdf'
            ], [
                'message.required_without' => 'Either Message or File is required.',
                'terms_file.required_without' => 'Either File or Message is required.',
                'terms_file.file' => 'The file must be a file.',
                'terms_file.mimes' => 'The file must be a JPG, JPEG, PNG, GIF, BMP, or PDF file.',
            ]);

            if(isset($request->id)){
                $terms = TermsAndCondition::findOrFail($request->id);
                $terms->message = $request->message;
                $terms->save();
                return back()->with('success',__('messages.terms_and_conditions.edit_success'));
            }else{
                $terms = new TermsAndCondition();
                if(!empty($request->message)){
                    $terms->message = $request->message;
                    $terms->save();
                } else if($request->hasFile('terms_file')){
                        $uploadedFile = $request->file('terms_file');
                        $filename = $uploadedFile->getClientOriginalName(); // Original filename
                        $newFilename = "terms-and-conditions." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                        $uploadedFile->move(public_path('uploads/terms_and_conditions/' ), $newFilename);

                        $terms->file = $newFilename; // Store the filename in the database
                        $terms->save();
                } else {
                    return back()->with('error',__('messages.terms_and_conditions.error'));
                }
                return back()->with('success',__('messages.terms_and_conditions.add_success'));
            }
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();
            return redirect()->route('pages.terms-and-conditions')->with('error', $errors);
            // Handle errors here
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'delete_terms_id' => 'required|numeric|exists:terms_and_conditions,id',
            ]);

            TermsAndCondition::where('id', $request->delete_terms_id)->delete();

            return redirect()->route('pages.terms-and-conditions')->with('success', __('messages.terms_and_conditions.delete_success'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();

            return redirect()->route('pages.terms-and-conditions')->with('error', $errors);
            // Handle errors here
        }
    }
}

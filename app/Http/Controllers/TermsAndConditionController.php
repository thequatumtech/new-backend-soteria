<?php

namespace App\Http\Controllers;

use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TermsAndConditionController extends Controller
{
    public function index()
    {
        $termsAndCondition = TermsAndCondition::get();
        if (empty($termsAndCondition)) {
            $termsAndCondition = new \stdClass();
            $termsAndCondition->id = '';
            $termsAndCondition->message = "";
        }
        return view('content.termsAndCondition.termsAndCondition', ['termsAndCondition' => $termsAndCondition]);
    }

    public function terms(Request $request)
    {
        $termsAndCondition = TermsAndCondition::first();
        if ($termsAndCondition) {
            if (!empty($termsAndCondition->message)) {
                return view('content.termsAndCondition.terms', compact('termsAndCondition'));
            } else if (!empty($termsAndCondition->file)) {
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
            // Validate input
            $request->validate([
                'message' => 'required_without:terms_file',
                'terms_file' => 'required_without:message|file|mimes:jpg,jpeg,png,gif,bmp,pdf'
            ], [
                'message.required_without' => 'Either Message or File is required.',
                'terms_file.required_without' => 'Either File or Message is required.',
                'terms_file.file' => 'The file must be a valid file.',
                'terms_file.mimes' => 'The file must be a JPG, JPEG, PNG, GIF, BMP, or PDF.',
            ]);

            if (isset($request->id)) {
                // Update existing Terms and Condition
                $terms = TermsAndCondition::findOrFail($request->id);
                $terms->message = $request->message;

                if ($request->hasFile('terms_file')) {
                    // Delete old file if exists
                    if ($terms->file && file_exists(public_path('uploads/terms_and_conditions/' . $terms->file))) {
                        unlink(public_path('uploads/terms_and_conditions/' . $terms->file));
                    }

                    // Save new file
                    $uploadedFile = $request->file('terms_file');
                    $newFilename = time() . '_' . $uploadedFile->getClientOriginalName(); // Use unique filename
                    $uploadedFile->move(public_path('uploads/terms_and_conditions'), $newFilename);
                    $terms->file = $newFilename;
                }

                $terms->save();
                return back()->with('success', __('messages.terms_and_conditions.edit_success'));
            } else {
                $terms = new TermsAndCondition();

                if (!empty($request->message)) {
                    $terms->message = $request->message;
                }

                if ($request->hasFile('terms_file')) {
                    $uploadedFile = $request->file('upload_file');
                    $newFilename = $uploadedFile->getClientOriginalName();
                    $uploadedFile->move(public_path('uploads/terms_and_conditions'), $newFilename);
                    $terms->file = $newFilename;
                }

                if (empty($terms->message) && empty($terms->file)) {
                    return back()->with('error', __('messages.terms_and_conditions.error'));
                }

                $terms->save();
                return back()->with('success', __('messages.terms_and_conditions.add_success'));
            }
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->first();
            return redirect()->route('pages.terms-and-conditions')->with('error', $errors);
        } catch (\Exception $e) {
            return back()->with('error', __('messages.general_error'));
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
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:terms_and_conditions,id',
    //         'message' => 'required_without:terms_file',
    //         'terms_file' => 'file|mimes:jpg,jpeg,png,gif,bmp,pdf'
    //     ]);

    //     $terms = TermsAndCondition::find($request->id);
    //     $terms->message = $request->message;

    //     if ($request->hasFile('terms_file')) {
    //         if ($terms->file && file_exists(public_path('uploads/terms_and_conditions/' . $terms->file))) {
    //             unlink(public_path('uploads/terms_and_conditions/' . $terms->file));
    //         }

    //         $file = $request->file('terms_file');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->move(public_path('uploads/terms_and_conditions'), $filename);

    //         $terms->file = $filename;
    //     }

    //     $terms->save();

    //     return back()->with('success', 'Terms updated successfully.');
    // }
     
public function update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:terms_and_conditions,id',
        'message' => 'required_without:terms_file',
        'terms_file' => 'file|mimes:jpg,jpeg,png,gif,bmp,pdf'
    ]);

    $terms = TermsAndCondition::find($request->id);

    $terms->message = $request->message;

    if ($request->hasFile('terms_file')) {

        // Delete old file if it exists
        if (
            $terms->file &&
            file_exists(
                public_path('uploads/terms_and_conditions/' . $terms->file)
            )
        ) {
            unlink(
                public_path('uploads/terms_and_conditions/' . $terms->file)
            );
        }

        // Get uploaded file
        $file = $request->file('terms_file');

        // Get original filename
        $originalName = $file->getClientOriginalName();

        // Replace all spaces/whitespace with underscore
        $originalName = preg_replace('/\s+/', '_', $originalName);

        // Create unique filename
        $filename = time() . '_' . $originalName;

        // Move file to upload directory
        $file->move(
            public_path('uploads/terms_and_conditions'),
            $filename
        );

        // Save filename in database
        $terms->file = $filename;
    }

    $terms->save();

    return back()->with('success', 'Terms updated successfully.');
}

}

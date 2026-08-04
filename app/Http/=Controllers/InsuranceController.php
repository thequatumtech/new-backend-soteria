<?php

namespace App\Http\Controllers;

use App\Models\InsuranceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;

class InsuranceController extends Controller
{
    function index(Request $request)
    {
        info($request->all());
        $insurancedata = InsuranceModel::all();
        info($insurancedata);
        return view('content.insurance.insurance', compact('insurancedata'));
    }
    function InsuranceCreate(Request $request)
    {
        info($request->all());
        $insurancedatas = [
            'full_name' => $request->full_name,
            'license_number' => $request->license_number,
            'address' => $request->address,
            'telephone_number' => $request->telephone_number,
            'post_address' => $request->post_address,
            'bussiness_line' => $request->bussiness_line,
            'tax_id' => $request->tax_id,
            'fax_number' => $request->fax_number,
            'stamp_company' => $request->stamp_company,
            'signature' => $request->signature,
            'letter_head' => $request->letter_head,
        ];

        $insurancedata =  InsuranceModel::create($insurancedatas);
        $insurancedata->clearMediaCollection('insurance-files');
        $insurancedata->addMediaFromRequest('insurance_stamp_company')->toMediaCollection('insurance_stamp_company');
        $insurancedata->addMediaFromRequest('signature')->toMediaCollection('signature');
        $insurancedata->addMediaFromRequest('letter_head')->toMediaCollection('letter_head');
        return redirect()->route('pages.insurance');
    }

    function InsuranceEdit($id)
    {
        $insurancedata = InsuranceModel::find($id);
        info($insurancedata);
        return response()->json([
            'insurance' => $insurancedata,
            'status' => 200
        ]);
    }
    function InsuranceUpdate(Request $request)
    {
        info($request->all());

        $insurance = InsuranceModel::find($request->insurance_id);

        if ($insurance) {
            $insurance->update([
                'full_name' => $request->full_name,
                'license_number' => $request->license_number,
                'address' => $request->address,
                'telephone_number' => $request->telephone_number,
                'post_address' => $request->post_address,
                'bussiness_line' => $request->bussiness_line,
                'tax_id' => $request->tax_id,
                'fax_number' => $request->fax_number,

            ]);

            // Add insurance_stamp_company media
            if ($request->hasFile('insurance_stamp_company')) {
                $insurance->clearMediaCollection('insurance_stamp_company');
                $insurance->addMediaFromRequest('insurance_stamp_company')->toMediaCollection('insurance_stamp_company');
            }

            // Add signature media
            if ($request->hasFile('signature')) {
                $insurance->clearMediaCollection('signature');
                $insurance->addMediaFromRequest('signature')->toMediaCollection('signature');
            }

            // Add letter_head media
            if ($request->hasFile('letter_head')) {
                $insurance->clearMediaCollection('letter_head');
                $insurance->addMediaFromRequest('letter_head')->toMediaCollection('letter_head');
            }

            return redirect()->route('pages.insurance')->with('success', 'Insurance Update successfully');
        }

        return response()->route('pages.insurance')->with('error', 'Insurance not found');
    }
    function destoryInsurance(Request $request)
    {

        $insurance_id = $request->input('delete_insurance_id');
        info($insurance_id);
        $insurance = InsuranceModel::find($insurance_id);
        $insurance->delete();

        return redirect()->back()->with('status', 'insurance deleted successfully');
    }
}

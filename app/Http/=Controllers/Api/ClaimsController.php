<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{Claim,ClaimMessage};
use App\Models\InsurancePlanModels\{LifePlan,LifePlanPolicyCover,LifePlanPricingSchedule};
use PDF;
use Illuminate\Http\Request;

class ClaimsController extends Controller
{
    public function storeClaims(Request $request)
    {
        try {
            $data = $request->validate([
                'policy_id' => 'required',
                'insurance_company_id' => 'required',
                'policy_type' => 'required',
                'policy_type_name' => 'required',
                'effective_date' => 'required',
                'expiry_date' => 'required',
                'claim_note' => 'required',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // file validation rules
            ]);
    
            $data['client_id'] = $request->user_id;
            $data['claim_no'] = rand(000000, 999999);
            $data['status']='Received';
            // Generate directory name based on policy type name
            $dir = strtolower(trim(str_replace(" ", "_", $data['policy_type_name'])));
            
            // Unique file name generator
            $generateUniqueFileName = function ($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };
    
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = $generateUniqueFileName($file);
                    $path = $file->move('uploads/claims/' . $dir . '/', $filename);
                    $filename='uploads/claims/' . $dir . '/'.$filename;
                    $attachments[] = $filename; // Store file path
                }
            }
    
            // Store attachments as JSON
            $data['attachments'] = json_encode($attachments);
    
            // Create the claim
            $claim = Claim::create($data);
    
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Claims added successfully',
                'data' => $claim,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    } 
    public function updateClaims(Request $request)
    {
        try {
            $claim = Claim::findOrFail($request->id);
    
            $data = $request->validate([
                'policy_id' => 'required',
                'insurance_company_id' => 'required',
                'policy_type' => 'required',
                'policy_type_name' => 'required',
                'effective_date' => 'required',
                'expiry_date' => 'required',
                'claim_note' => 'required',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // file validation rules
            ]);
    
            $data['client_id'] = $request->user_id;
            // Keep the existing claim_no or generate a new one if necessary
            $data['claim_no'] = $claim->claim_no ?: rand(000000, 999999);
            
            // Generate directory name based on policy type name
            $dir = strtolower(trim(str_replace(" ", "_", $data['policy_type_name'])));
            
            // Unique file name generator
            $generateUniqueFileName = function ($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };
    
            $attachments = json_decode($claim->attachments, true) ?: [];
    
            // Check if there are new attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = $generateUniqueFileName($file);
                    $path = $file->move('uploads/claims/' . $dir . '/', $filename);
                    $filename = 'uploads/claims/' . $dir . '/' . $filename;
                    $attachments[] = $filename; // Append new file path to existing attachments
                }
            }
    
            // Store updated attachments as JSON
            $data['attachments'] = json_encode($attachments);
    
            // Update the claim with new data
            $claim->update($data);
    
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Claims updated successfully',
                'data' => $claim,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
    public function removeClaimsDocument(Request $request, $claimId)
    {
        try {
            $claim = Claim::findOrFail($claimId);

            // Validate the request to ensure a valid document path or index is provided
            $data = $request->validate([
                'document_path' => 'required|string', // Full path or relative path of the document to be deleted
            ]);

            $documentPath = $data['document_path'];

            // Get the current attachments array from the claim
            $attachments = json_decode($claim->attachments, true) ?: [];

            // Check if the document exists in the attachments
            if (($key = array_search($documentPath, $attachments)) !== false) {
                // Remove the document from the attachments array
                unset($attachments[$key]);

                // Delete the file from the server
                if (file_exists(public_path($documentPath))) {
                    unlink(public_path($documentPath)); // Delete the file from the server
                }

                // Reindex the attachments array and update it in the database
                $claim->attachments = json_encode(array_values($attachments));
                $claim->save();

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Document removed successfully',
                    'data' => $claim,
                ]);
            } else {
                // Document not found in the claim's attachments
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Document not found in the claim',
                    'data' => []
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
    public function claimsList(Request $request)
    {
        try {
            $user_id=$request->user_id;
            // Optionally filter by user or company, or other conditions if needed
            $claims = Claim::getAllClaims($user_id);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Claims retrieved successfully',
                'data' => $claims,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
    public function sendClaimMessage(Request $request)
    {
        try {
             $data = $request->validate([
                'claim_id' => 'required',
                'message' => 'required',
            ]);
            $data['client_id']=$request->user_id;
            $data['is_message'] = '1';
            $data['sent_by'] = '1';
            $claims = ClaimMessage::create($data);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Claims Message Send successfully',
                'data' => $claims,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
    public function claimsChatsList(Request $request)
    {
        try {
            $data = ClaimMessage::where('claim_id',$request->claim_id)->get();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Message Claims retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
}

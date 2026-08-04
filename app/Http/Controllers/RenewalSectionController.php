<?php

namespace App\Http\Controllers;

use App\Models\PurchasePolicy;
use App\Models\ClientMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenewalClientMail;
use App\Mail\RenewalAgentMail;
use App\Models\AgentModel;
use Carbon\Carbon;
use App\Models\MailTemplate;
use App\Models\PolicyMailLog;

class RenewalSectionController extends Controller
{
    public function active_policies(Request $request)
    {
        $table_title = __('messages.renewal_section.active_policies');

        $start_date = now()->startOfDay();
        $end_date = now()->addDays(30)->endOfDay();

        $policies = PurchasePolicy::whereDate('expiry_date', '>=', $start_date)
            ->whereDate('expiry_date', '<=', $end_date)
            ->whereNull('cancelled_at')
            ->get();

        $is_active_policies = 1;
        $is_expired_policies = false;

        $search_route = route('renew_policy');

        return view('admin.renewal_section.index', compact(
            'table_title',
            'policies',
            'is_active_policies',
            'is_expired_policies',
            'search_route'
        ));
    }

    public function expired_policies(Request $request)
    {
        $table_title = __('messages.renewal_section.expired_policies');

        $start_date = now()->subDays(30)->startOfDay();
        $end_date = now()->endOfDay();

        $policies = PurchasePolicy::whereBetween('expiry_date', [$start_date, $end_date])
            ->where('renewed', 0)
            ->get();

        $is_active_policies = 1;
        $is_expired_policies = true;

        $search_route = route('search_expired_policies');

        return view('admin.renewal_section.index', compact(
            'table_title',
            'policies',
            'is_active_policies',
            'is_expired_policies',
            'search_route'
        ));
    }

    public function view(Request $request)
    {
        // Placeholder for view functionality
    }

    public function renew_policy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'sometimes|required|string',
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'national_id' => 'sometimes|required|string',
            'passport' => 'sometimes|required|string',
            'residence' => 'sometimes|required|string',
            'policy_number' => 'sometimes|required|string',
            'from_date' => 'sometimes|required|date',
            'to_date' => 'sometimes|required|date|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $query = PurchasePolicy::whereBetween('expiry_date', [
            now()->startOfDay(),
            now()->addDays(30)->endOfDay()
        ]);

        if ($request->has('mobile')) {
            $query->whereHas('client', fn($q) => $q->where('mobile_no', 'like', '%' . $request->mobile . '%'));
        }

        if ($request->has('first_name')) {
            $query->whereHas('client', fn($q) => $q->where('first_name', 'like', '%' . $request->first_name . '%'));
        }

        if ($request->has('last_name')) {
            $query->whereHas('client', fn($q) => $q->where('surname', 'like', '%' . $request->last_name . '%'));
        }

        if ($request->has('email')) {
            $query->whereHas('client', fn($q) => $q->where('email_id', 'like', '%' . $request->email . '%'));
        }

        if ($request->has('national_id')) {
            $query->whereHas('client', fn($q) => $q->where('national_id_number', 'like', '%' . $request->national_id . '%'));
        }

        if ($request->has('passport')) {
            $query->whereHas('client', fn($q) => $q->where('national_id_number', 'like', '%' . $request->passport . '%'));
        }

        if ($request->has('residence')) {
            $query->whereHas('client', fn($q) => $q->where('residence_id_number', 'like', '%' . $request->residence . '%'));
        }

        if ($request->has('policy_number')) {
            $query->where('policy_no', 'like', '%' . $request->policy_number . '%');
        }

        if ($request->has(['from_date', 'to_date'])) {
            $query->whereBetween('expiry_date', [
                max($request->from_date, now()->startOfDay()),
                min($request->to_date, now()->addDays(30)->endOfDay())
            ]);
        }

        $policies = $query->with(['client', 'insurance_company'])->get();

        return response()->json([
            'status' => 'success',
            'policies' => $policies
        ]);
    }

    public function purchased_policy(Request $request, $id)
    {
        $purchased_policy = PurchasePolicy::with('insurance_company')->where('client_id', $id)->get();
        if ($purchased_policy->isNotEmpty()) {
            return response()->json([
                'purchased_policy' => $purchased_policy,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'purchased_policy' => [],
                'status' => 404
            ]);
        }
    }

    public function renew_form($id)
    {
        $policy = PurchasePolicy::findOrFail($id);
        return view('admin.renewal_section.renew_form', compact('policy'));
    }

    public function renew_update(Request $request, $id)
    {
        $request->validate([
            'inception_date' => 'required|date',
            'expiry_date' => 'required|date|after:inception_date',
        ]);

        $policy = PurchasePolicy::findOrFail($id);
        $policy->inception_date = $request->inception_date;
        $policy->expiry_date = $request->expiry_date;
        $policy->renewed = 1;
        $policy->save();

        $redirectUrl = $request->input('redirect_to', route('active_policies'));
        return redirect($redirectUrl)->with('success', 'Policy renewed successfully!');
    }

    public function search_expired_policies(Request $request)
    {
        $start_date = now()->subDays(30)->startOfDay();
        $end_date = now()->endOfDay();

        $query = PurchasePolicy::whereBetween('expiry_date', [$start_date, $end_date])
            ->where('renewed', 0);

        // Apply filters
        if ($request->filled('mobile')) {
            $query->whereHas('client', fn($q) => $q->where('mobile_no', 'like', '%' . $request->mobile . '%'));
        }

        if ($request->filled('first_name')) {
            $query->whereHas('client', fn($q) => $q->where('first_name', 'like', '%' . $request->first_name . '%'));
        }

        if ($request->filled('last_name')) {
            $query->whereHas('client', fn($q) => $q->where('surname', 'like', '%' . $request->last_name . '%'));
        }

        if ($request->filled('email')) {
            $query->whereHas('client', fn($q) => $q->where('email_id', 'like', '%' . $request->email . '%'));
        }

        if ($request->filled('national_id')) {
            $query->whereHas('client', fn($q) => $q->where('national_id_number', 'like', '%' . $request->national_id . '%'));
        }

        if ($request->filled('passport')) {
            $query->whereHas('client', fn($q) => $q->where('national_id_number', 'like', '%' . $request->passport . '%'));
        }

        if ($request->filled('residence')) {
            $query->whereHas('client', fn($q) => $q->where('residence_id_number', 'like', '%' . $request->residence . '%'));
        }

        if ($request->filled('policy_number')) {
            $query->where('policy_no', 'like', '%' . $request->policy_number . '%');
        }

        if ($request->filled(['from_date', 'to_date'])) {
            $from = max($request->from_date, $start_date);
            $to = min($request->to_date, $end_date);
            $query->whereBetween('expiry_date', [$from, $to]);
        }

        $policies = $query->with(['client', 'insurance_company'])->get();

        return response()->json([
            'status' => 'success',
            'policies' => $policies
        ]);
    }

    public function notify_renewal(Request $request, $id = null)
    {
        $policy_id = $id ?: $request->policy_id;

        $validator = Validator::make(['policy_id' => $policy_id], [
            'policy_id' => 'required|exists:purchase_policy,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $policy  = PurchasePolicy::with(['client', 'insurance_company'])->findOrFail($policy_id);
            $client  = $policy->client;
            $agent   = null;

            if ($client && !empty($client->agent_id)) {
                $agent = AgentModel::where('agent_code', $client->agent_id)->first();
            }

            $daysLeft = (int) now()->diffInDays($policy->expiry_date, false);

            $replacements = [
                '[CLIENT_NAME]'           => $client->full_name ?? 'Valued Client',
                '[CLIENT_FIRST_NAME]'     => $client->first_name ?? '',
                '[CLIENT_SURNAME]'        => $client->surname ?? '',
                '[AGENT_NAME]'            => $agent ? ($agent->first_name ?? 'Agent') : 'Agent',
                '[PLAN_NAME]'             => $policy->plan_name ?? $policy->policy_no,
                '[POLICY_NO]'             => $policy->policy_no,
                '[EXPIRY_DATE_FORMATTED]' => Carbon::parse($policy->expiry_date)->toFormattedDateString(),
                '[EXPIRY_DATE_SHORT]'     => Carbon::parse($policy->expiry_date)->toDateString(),
                '[DAYS_LEFT]'             => abs($daysLeft),
                '[APP_NAME]'              => env('APP_NAME', 'Insurance System'),
                '[YEAR]'                  => date('Y'),
            ];

            $emailsSent  = [];
            $clientContent = null;
            $agentContent  = null;

            if ($client && !empty($client->email_id)) {
                $clientTemplate = MailTemplate::where('type', 'client')->first();
                if ($clientTemplate) {
                    $clientContent = str_replace(
                        array_keys($replacements),
                        array_values($replacements),
                        $clientTemplate->content
                    );
                    try {
                        Mail::html($clientContent, function ($message) use ($client) {
                            $message->to($client->email_id)
                                ->subject('Policy Renewal Reminder');
                        });
                        $emailsSent[] = 'client';
                    } catch (\Exception $e) {
                    }
                }
            }

            if ($agent && !empty($agent->agent_email)) {
                $agentTemplate = MailTemplate::where('type', 'agent')->first();
                if ($agentTemplate) {
                    $agentContent = str_replace(
                        array_keys($replacements),
                        array_values($replacements),
                        $agentTemplate->content
                    );
                    try {
                        Mail::html($agentContent, function ($message) use ($agent) {
                            $message->to($agent->agent_email)
                                ->subject('Client Policy Renewal Reminder');
                        });
                        $emailsSent[] = 'agent';
                    } catch (\Exception $e) {
                    }
                }
            }

            $log = new PolicyMailLog();
            $log->purchase_policy_id = $policy->id;
            $log->client_id          = $client->id;
            $log->client_mail        = $clientContent;
            $log->agent_mail         = $agentContent;
            $log->save();
            $messageText = Carbon::parse($policy->expiry_date)->isPast()
                ? __('messages.renewal_section.policy_has_expired', ['date' => $policy->expiry_date])
                : __('messages.renewal_section.policy_about_to_expire', ['date' => $policy->expiry_date]);

            $client_message          = new ClientMessage();
            $client_message->client_id = $client->id;
            $client_message->message   = $messageText;
            $client_message->save();

            $successMessage = count($emailsSent) > 0
                ? 'Notification sent successfully to ' . implode(' and ', $emailsSent) . '.'
                : 'Notification processed but no emails were sent.';

            if ($request->ajax() || $request->isMethod('post')) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return back()->with('success', $successMessage);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->isMethod('post')) {
                return response()->json(['status' => 'error', 'message' => 'Failed to send notification: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to send notification: ' . $e->getMessage());
        }
    }

    public function cancel_policy(Request $request, $id = null)
    {
        $policy_id = $id ?: $request->policy_id;

        $validator = Validator::make(['policy_id' => $policy_id], [
            'policy_id' => 'required|exists:purchase_policy,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $policy = PurchasePolicy::findOrFail($policy_id);
            $policy->cancelled_at = now();
            $policy->save();

            if ($request->ajax() || $request->isMethod('post')) {
                return response()->json(['status' => 'success', 'message' => 'Policy cancelled successfully.']);
            }
            return back()->with('success', 'Policy cancelled successfully.');
        } catch (\Exception $e) {
            logger()->error($e->getTraceAsString());
            if ($request->ajax() || $request->isMethod('post')) {
                return response()->json(['status' => 'error', 'message' => 'Failed to cancel policy: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to cancel policy: ' . $e->getMessage());
        }
    }
    public function previewMail(Request $request)
    {
        $policy = PurchasePolicy::findOrFail($request->policy_id);
        $client = $policy->client;
        $agent = $policy->agent;

        $daysLeft = now()->diffInDays($policy->expiry_date, false);

        if ($request->mail_type == 'client') {
            $mail = new RenewalClientMail($policy, $client, $daysLeft);
        } else {
            $mail = new RenewalAgentMail($policy, $client, $agent, $daysLeft);
        }

        return $mail->render();
    }
    public function getMailTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required|exists:purchase_policy,id',
            'mail_type' => 'required|in:client,agent',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $policy   = PurchasePolicy::with(['client', 'insurance_company'])->findOrFail($request->policy_id);
        $client   = $policy->client;
        $agent    = null;

        if ($client && !empty($client->agent_id)) {
            $agent = AgentModel::where('agent_code', $client->agent_id)->first();
        }

        $daysLeft  = (int) now()->diffInDays($policy->expiry_date, false);
        $expiryFmt = Carbon::parse($policy->expiry_date)->toFormattedDateString();
        $year      = date('Y');
        $appName   = env('APP_NAME', 'Insurance System');

        // $log = PolicyMailLog::where('purchase_policy_id', $request->policy_id)->latest()->first();

        // $column = $request->mail_type === 'client' ? 'client_mail' : 'agent_mail';

        // if ($log && !empty($log->$column)) {
        //     // Return previously saved/edited content
        //     return response()->json(['status' => 'success', 'content' => $log->$column]);
        // }

        $template = MailTemplate::where('type', $request->mail_type)->first();

        if (!$template) {
            return response()->json(['status' => 'error', 'message' => 'Template not found.'], 404);
        }

        $content = $template->content;

        $replacements = [
            '[CLIENT_NAME]'            => $client->full_name ?? 'Valued Client',
            '[CLIENT_FIRST_NAME]'      => $client->first_name ?? '',
            '[CLIENT_SURNAME]'         => $client->surname ?? '',
            '[AGENT_NAME]'             => $agent ? ($agent->first_name ?? 'Agent') : 'Agent',
            '[PLAN_NAME]'              => $policy->plan_name ?? $policy->policy_no,
            '[POLICY_NO]'              => $policy->policy_no,
            '[EXPIRY_DATE_FORMATTED]'  => Carbon::parse($policy->expiry_date)->toFormattedDateString(),
            '[EXPIRY_DATE_SHORT]'      => Carbon::parse($policy->expiry_date)->toDateString(),
            '[DAYS_LEFT]'              => abs($daysLeft),
            '[APP_NAME]'               => env('APP_NAME', 'Insurance System'),
            '[YEAR]'                   => date('Y'),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        return response()->json(['status' => 'success', 'content' => $content]);
    }

    public function sendMailWithLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required|exists:purchase_policy,id',
            'mail_type' => 'required|in:client,agent',
            'content'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $policy = PurchasePolicy::with(['client', 'insurance_company'])->findOrFail($request->policy_id);
        $client = $policy->client;
        $agent  = null;

        if ($client && !empty($client->agent_id)) {
            $agent = AgentModel::where('agent_code', $client->agent_id)->first();
        }

        $log = new PolicyMailLog();
        $log->purchase_policy_id = $policy->id;
        $log->client_id          = $client->id;
        $log->client_mail        = $request->mail_type === 'client' ? $request->content : null;
        $log->agent_mail         = $request->mail_type === 'agent'  ? $request->content : null;
        $log->save();

        try {
            if ($request->mail_type === 'client' && $client && !empty($client->email_id)) {
                Mail::html($request->content, function ($message) use ($client) {
                    $message->to($client->email_id)
                        ->subject('Policy Renewal Reminder');
                });
            } elseif ($request->mail_type === 'agent' && $agent && !empty($agent->agent_email)) {
                Mail::html($request->content, function ($message) use ($agent) {
                    $message->to($agent->agent_email)
                        ->subject('Client Policy Renewal Reminder');
                });
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Mail log saved but email failed: ' . $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Mail sent and saved successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Currency;
use Illuminate\Validation\Rule;
use App\Models\Country;



class CurrencyController extends Controller
{
    //list data
    public function index()
    {
        $currency = Currency::withTrashed()->with('country')->get();
        $countries = Country::whereNull('deleted_at')->get();

        return view('admin.currency.currency', compact('currency', 'countries'));
    }


    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => [
                    'required',
                    'string',
                    Rule::unique('currencies')->whereNull('deleted_at'),
                ],
                'abbreviation' => [
                    'required',
                    'string',
                    Rule::unique('currencies')->whereNull('deleted_at'),
                ],
            ]);

            Currency::create($request->all());
            return redirect()->route('currency.list')->with('success', __('messages.currency.currency_created'));
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return redirect()->route('currency.list')->with('error', __('messages.currency.error'));
        }
    }

    //update data
    public function update(Request $request, Currency $currency)
    {

        try {
            $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => [
                    'required',
                    'string',
                    Rule::unique('currencies')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
                'abbreviation' => [
                    'required',
                    'string',
                    Rule::unique('currencies')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);


            $currency = Currency::findOrFail($request->cd_id);
            $currency->update($request->all());

            return redirect()->route('currency.list')->with('success', __('messages.currency.edit_created'));
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return redirect()->route('currency.list')->with('error', __('messages.currency.error'));
        }
    }

    public function destroy(Request $request)
    {
        $currency = Currency::findOrFail($request->cd_id);
        $currency->delete();
        return redirect()->route('currency.list')->with('success', __('messages.currency.deleted'));
    }


    //store data with csv
    public function csv(Request $request)
    {
        try {

            // Validate the uploaded file
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt'
            ]);

            // Read the CSV file
            $file = $request->file('csv_file');
            $csvData = file_get_contents($file);

            // Parse CSV data
            $rows = explode("\n", $csvData);
            foreach ($rows as $k => $row) {
                if ($k > 0) {
                    $rowData = str_getcsv($row);
                    $name = $rowData[0];
                    $existingRow = Currency::where('name', $name)->whereNull('deleted_at')->first();;

                    if (!$existingRow) {
                        if (!empty($name) && !empty(trim($rowData[1]))) {
                            $currency = new Currency();
                            $currency->name = trim($name);
                            $currency->abbreviation = trim($rowData[1]);
                            $currency->save();
                        }
                    }
                }
            }

            // Redirect back with success message
            return back()->with('success',  __('messages.currency.csvsuccess'));
        } catch (\Exception $exception) {
            // Validation failed, handle the error
            // $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('currency.list')->with('error', __('messages.currency.error'));
            // Handle errors here
        }
    }
}

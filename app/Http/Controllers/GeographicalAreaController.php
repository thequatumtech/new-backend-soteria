<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\GeographicalArea;
use App\Models\Country;
use Illuminate\Validation\Rule;

class GeographicalAreaController extends Controller
{
    // List data
    public function index()
    {
        $geographical_area = GeographicalArea::withTrashed()->get();
        $countries = Country::orderBy('name', 'asc')->get();
        return view('admin.geographical_area.geographical_area', [
            'geographical_area' => $geographical_area,
            'countries'         => $countries,
        ]);
    }

    // Store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name'      => [
                    'required',
                    'string',
                    Rule::unique('geographical_areas')->whereNull('deleted_at'),
                ],
                'countries' => ['nullable', 'array'],
                'countries.*' => ['integer', 'exists:countries,id'],
            ]);

            GeographicalArea::create([
                'name'      => $request->name,
                'countries' => $request->countries ?? [],
            ]);

            return redirect()->route('geographical_area.list')
                ->with('success', __('messages.geographical_area.geographical_area_created'));
        } catch (ValidationException $e) {
            return redirect()->route('geographical_area.list')
                ->with('error', __('messages.geographical_area.error'));
        }
    }

    // Update data
    public function update(Request $request, GeographicalArea $geographical_area)
    {
        try {
            $request->validate([
                'name'      => [
                    'required',
                    'string',
                    Rule::unique('geographical_areas')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
                'countries' => ['nullable', 'array'],
                'countries.*' => ['integer', 'exists:countries,id'],
            ]);

            $geographical_area = GeographicalArea::findOrFail($request->cd_id);
            $geographical_area->update([
                'name'      => $request->name,
                'countries' => $request->countries ?? [],
            ]);

            return redirect()->route('geographical_area.list')
                ->with('success', __('messages.geographical_area.edit_created'));
        } catch (ValidationException $e) {
            return redirect()->route('geographical_area.list')
                ->with('error', __('messages.geographical_area.error'));
        }
    }

    // Soft delete
    public function destroy(Request $request)
    {
        $geographical_area = GeographicalArea::findOrFail($request->cd_id);
        $geographical_area->delete();
        return redirect()->route('geographical_area.list')
            ->with('success', __('messages.geographical_area.deleted'));
    }
}

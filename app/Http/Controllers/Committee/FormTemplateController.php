<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\FormTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormTemplateController extends Controller
{
    /**
     * List form templates for a competition.
     */
    public function index(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $templates = $competition->formTemplates()->latest()->get();

        return view('committee.form-templates.index', compact('competition', 'templates'));
    }

    /**
     * Show create form.
     */
    public function create(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        // Load existing templates for "reuse" dropdown
        $existingTemplates = FormTemplate::where('competition_id', '!=', $competition->id)
            ->whereHas('competition', fn ($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->get();

        return view('committee.form-templates.create', compact('competition', 'existingTemplates'));
    }

    /**
     * Store a new form template (atau clone dari template lain).
     */
    public function store(Request $request, Competition $competition): RedirectResponse
    {
        $this->authorizeCommittee($competition);

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'fields'        => ['required', 'json'],
            'clone_from'    => ['nullable', 'exists:form_templates,id'],
        ]);

        // Jika clone dari template lain
        if ($request->filled('clone_from')) {
            $source = FormTemplate::findOrFail($request->clone_from);
            $fields = $source->fields;
        } else {
            $fields = json_decode($validated['fields'], true);
        }

        $competition->formTemplates()->create([
            'name'   => $validated['name'],
            'fields' => $fields,
        ]);

        return redirect()
            ->route('committee.form-templates.index', $competition)
            ->with('success', 'Form template created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Competition $competition, FormTemplate $template): View
    {
        $this->authorizeCommittee($competition);

        return view('committee.form-templates.edit', compact('competition', 'template'));
    }

    /**
     * Update form template.
     */
    public function update(Request $request, Competition $competition, FormTemplate $template): RedirectResponse
    {
        $this->authorizeCommittee($competition);

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:150'],
            'fields' => ['required', 'json'],
        ]);

        $template->update([
            'name'   => $validated['name'],
            'fields' => json_decode($validated['fields'], true),
        ]);

        return redirect()
            ->route('committee.form-templates.index', $competition)
            ->with('success', 'Form template updated successfully.');
    }

    /**
     * Delete form template.
     */
    public function destroy(Competition $competition, FormTemplate $template): RedirectResponse
    {
        $this->authorizeCommittee($competition);

        $template->delete();

        return redirect()
            ->route('committee.form-templates.index', $competition)
            ->with('success', 'Form template deleted.');
    }

    /**
     * API: Get template fields as JSON (untuk dynamic form builder).
     */
    public function getFields(FormTemplate $template)
    {
        return response()->json($template->fields);
    }

    /**
     * Ensure only the competition creator (committee) can manage templates.
     */
    private function authorizeCommittee(Competition $competition): void
    {
        abort_unless($competition->user_id === auth()->id(), 403);
    }
}

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
    public function index(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $templates = $competition->formTemplates()->latest()->get();

        return view('committee.form-templates.index', compact('competition', 'templates'));
    }

    public function create(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $existingTemplates = FormTemplate::with('competition')
            ->whereHas('competition', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('committee.form-templates.create', compact('competition', 'existingTemplates'));
    }

    public function store(Request $request, Competition $competition): RedirectResponse
    {
        $this->authorizeCommittee($competition);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'fields' => [$request->filled('clone_from') ? 'nullable' : 'required', 'json'],
            'clone_from' => ['nullable', 'exists:form_templates,id'],
        ]);

        if ($request->filled('clone_from')) {
            $source = FormTemplate::with('competition')
                ->where('id', $request->clone_from)
                ->whereHas('competition', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->firstOrFail();

            $fields = $source->fields;
        } else {
            $fields = json_decode($validated['fields'], true);
        }

        $competition->formTemplates()->create([
            'name' => $validated['name'],
            'fields' => $fields,
        ]);

        return redirect()
            ->route('committee.form-templates.index', $competition)
            ->with('success', 'Form template created successfully.');
    }

    public function edit(Competition $competition, FormTemplate $template): View
    {
        $this->authorizeCommittee($competition);
        $this->authorizeTemplate($competition, $template);

        return view('committee.form-templates.edit', compact('competition', 'template'));
    }

    public function update(Request $request, Competition $competition, FormTemplate $template): RedirectResponse
    {
        $this->authorizeCommittee($competition);
        $this->authorizeTemplate($competition, $template);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'fields' => ['required', 'json'],
        ]);

        $template->update([
            'name' => $validated['name'],
            'fields' => json_decode($validated['fields'], true),
        ]);

        return redirect()
            ->route('committee.form-templates.index', $competition)
            ->with('success', 'Form template updated successfully.');
    }

    public function destroy(Competition $competition, FormTemplate $template): RedirectResponse
    {
        $this->authorizeCommittee($competition);
        $this->authorizeTemplate($competition, $template);

        $template->delete();

        return redirect()
            ->route('committee.form-templates.index', $competition)
            ->with('success', 'Form template deleted.');
    }

    public function getFields(FormTemplate $template)
    {
        abort_unless($template->competition?->user_id === auth()->id(), 403);

        return response()->json($template->fields);
    }

    private function authorizeCommittee(Competition $competition): void
    {
        abort_unless($competition->user_id === auth()->id(), 403);
    }

    private function authorizeTemplate(Competition $competition, FormTemplate $template): void
    {
        abort_unless($template->competition_id === $competition->id, 404);
    }
}
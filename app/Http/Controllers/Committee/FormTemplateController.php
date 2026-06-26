<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Committee\Concerns\CommitteeAuthorization;
use App\Models\Competition;
use App\Models\FormTemplate;
use App\Services\Template\TemplateQualityAnalyzer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormTemplateController extends Controller
{
    use CommitteeAuthorization;

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
            if (!is_array($fields)) {
                $fields = [];
            }
        }

        if (!$request->filled('clone_from')) {
            $analyzer = app(TemplateQualityAnalyzer::class);
            $warnings = $analyzer->analyze($fields, $competition);

            if (!$analyzer->isTemplateSafe($warnings)) {
                $errors = array_map(fn($w) => $w->message, array_filter($warnings, fn($w) => $w->severity === 'error'));
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['fields' => $errors]);
            }

            $nonErrors = array_filter($warnings, fn($w) => $w->severity !== 'error');
            if (count($nonErrors) > 0) {
                session()->flash('template_warnings', array_map(fn($w) => $w->toArray(), $nonErrors));
            }
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

        $fields = json_decode($validated['fields'], true);
        if (!is_array($fields)) {
            $fields = [];
        }

        $analyzer = app(TemplateQualityAnalyzer::class);
        $warnings = $analyzer->analyze($fields, $competition);

        if (!$analyzer->isTemplateSafe($warnings)) {
            $errors = array_map(fn($w) => $w->message, array_filter($warnings, fn($w) => $w->severity === 'error'));
            return redirect()->back()
                ->withInput()
                ->withErrors(['fields' => $errors]);
        }

        $nonErrors = array_filter($warnings, fn($w) => $w->severity !== 'error');
        if (count($nonErrors) > 0) {
            session()->flash('template_warnings', array_map(fn($w) => $w->toArray(), $nonErrors));
        }

        $template->update([
            'name' => $validated['name'],
            'fields' => $fields,
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

    public function preview(Request $request, Competition $competition): \Illuminate\Http\JsonResponse
    {
        $this->authorizeCommittee($competition);

        $fields = json_decode($request->input('fields'), true);
        if (!is_array($fields)) {
            $fields = [];
        }

        $html = view('committee.form-templates.partials.preview-fields', compact('fields'))->render();

        $analyzer = app(TemplateQualityAnalyzer::class);
        $warnings = $analyzer->analyze($fields, $competition);

        return response()->json([
            'html' => $html,
            'warnings' => array_map(fn($w) => $w->toArray(), $warnings),
            'summary' => $analyzer->getSummary($warnings),
            'is_safe' => $analyzer->isTemplateSafe($warnings),
        ]);
    }

    public function getFields(FormTemplate $template)
    {
        abort_unless($template->competition?->user_id === auth()->id(), 403);

        return response()->json($template->fields);
    }

    private function authorizeTemplate(Competition $competition, FormTemplate $template): void
    {
        abort_unless($template->competition_id === $competition->id, 404);
    }
}
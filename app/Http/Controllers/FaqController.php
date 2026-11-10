<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\HtmlCleaner;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::query()
            ->when($request->filled('q'), fn ($query) => $query->where('question', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', (int) $request->input('status')))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.form', ['faq' => new Faq(['status' => 1, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));

        return redirect()->route('faqs.index')->with('success', 'Question added.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));

        return redirect()->route('faqs.index')->with('success', 'Question updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faqs.index')->with('success', 'Question deleted.');
    }

    public function status(Faq $faq)
    {
        $faq->update(['status' => $faq->status ? 0 : 1]);

        return back()->with('success', $faq->status ? 'Question activated.' : 'Question deactivated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'status' => ['required', 'in:0,1'],
        ]);
        $data['answer'] = HtmlCleaner::clean($data['answer']);
        if ($data['answer'] === '') {
            throw ValidationException::withMessages(['answer' => 'Add an answer.']);
        }

        return $data;
    }
}

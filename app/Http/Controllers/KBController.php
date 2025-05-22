<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\KnowledgeBase;
use App\Services\CohereService;
use App\Services\OpenAIService;
use Dotenv\Parser\Parser;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Http\Request;
use App\Services\KnowledgeRetrievalService;

class KBController extends Controller
{




    protected $kbRetrieval;

   
protected $cohere;

public function __construct(CohereService $cohere, KnowledgeRetrievalService $kbRetrieval)
{
    $this->cohere = $cohere;
    $this->kbRetrieval = $kbRetrieval;
}
    public function create()
    {
        $categories = Categories::all();
        return view('admin.docs_crud.upload_docs', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kb_title' => 'required|string',
            'document' => 'required|mimes:pdf,docx,txt|max:10240',
            'category' => 'required|exists:categories,categoryID',
        ]);

        $file = $request->file('document');

        $extension = $file->getClientOriginalExtension();

        if ($extension === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

        } else {
            $text = file_get_contents($file->getPathname());
        }

        $maxLength = 2000; 
$cleanedText = substr($text, 0, $maxLength);

$embedding = $this->cohere->generateEmbedding($cleanedText);


        if (empty($embedding)) {
            return back()->with('error', 'Failed to generate embedding. Please check if the document is too large or malformed.');
        }


        try {
            KnowledgeBase::create([
                'kb_title' => $request->kb_title,
                'content' => $text,
                'embedding' => json_encode($embedding),
                'source' => $file->getClientOriginalName(),
                'categoryID' => $request->category,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to store knowledge base entry: ' . $e->getMessage());
            return back()->with('error', 'Failed to store the document.');
        }

        return redirect()->route('admin.knowledge_base')->with('success', 'Document uploaded successfully.');


    }

    public function view($id)
    {
        $doc = KnowledgeBase::findOrFail($id);
        return view('admin.docs_crud.view_docs', compact('doc'));
    }

    public function search(Request $request)
    {
        $search = $request->input('query');  // name your input 'query' or 'search' consistently
        $category = $request->input('category');
    
        $documents = KnowledgeBase::with('category')
            ->when($search, function ($query, $search) {
                $query->where('kb_title', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                $query->where('categoryID', $category);
            })
            ->latest()
            ->paginate(10);
    
        $categories = Categories::all();
    
        return view('admin.knowledge_base', compact('categories', 'documents', 'category', 'search'));
    }
    public function destroy(String $id)
    {
        $kb = KnowledgeBase::findOrFail($id);
        $kb->delete();
        return redirect()->route('admin.knowledge_base');
    }
    

}

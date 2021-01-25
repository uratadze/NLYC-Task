<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Analyzers\TagAnalyzer;

class TagController extends Controller
{
    /**
     * Create a new analyzer instance.
     *
     * @param App\Analyzer\TagAnalyzer
     * @return void
     */
    public function __construct(TagAnalyzer $tagAnalyzer)
    {
        $this->tagAnalyzer = $tagAnalyzer;
    }

    /**
     * @param Illuminate\Http\Request
     * @return json
     */
    public function tags(Request $request)
    {
        return $this->tagAnalyzer->tags($request);
    }

    /**
     * @param Illuminate\Http\Request $request
     * @param integer $id
     * @return json
     */
    public function tagArticle(Request $request, $id)
    {
        return $this->tagAnalyzer->tags($request, $id);
    }

}

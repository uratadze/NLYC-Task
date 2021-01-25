<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Analyzers\ArticleAnalyzer;

class ArticleController extends Controller
{

    /**
     * Create a new analyzer instance.
     *
     * @param App\Analyzer\ArticleAnalyzer
     * @return void
     */
    public function __construct(ArticleAnalyzer $articleAnalyzer)
    {
        $this->articleAnalyzer = $articleAnalyzer;
    }

    /**
     * @param Illuminate\Http\Request
     * @return json
     */
    public function articles(Request $request)
    {
        return $this->articleAnalyzer->articles($request);
    }

    /**
     * @param Illuminate\Http\Request $request
     * @param integer $id
     * @return json
     */
    public function articleComments(Request $request, $id)
    {
        return $this->articleAnalyzer->articles($request, $id);
    }

}

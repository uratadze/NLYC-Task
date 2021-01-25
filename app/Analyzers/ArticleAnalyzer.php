<?php

namespace App\Analyzers;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleAnalyzer extends Article
{
    /**
     * @return object
     */
    public function paginate($items, $perPage, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page);
    }

    /**
     * Check action.
     * 
     * @param Illuminate\Http\Request $request
     * @param integer $articleId
     * 
     * @return \Illuminate\Http\Response
     */
    public function articles($request, $articleId=null)
    {
        return $articleId ? $this->getArticle($articleId, $request) : $this->getArticles($this, $request);
    }

    /**
     * Check request params.
     * 
     * @param query $articles
     * @param Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function getArticles($article, $request)
    {
        $error = $this->checkRequestParams($request);
        return $error? response()->json(['code'=>400], 400) : $this->articlesData($article->limit($request->limit?:10), $request);
    }

    /**
     * get articles data in json format.
     * 
     * @param query $articles
     * @param Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function articlesData($article, $request)
    {
        $article->increment('view_count');
        $article = $article->with('tags')
                            ->withCount('comments as comment_count')
                            ->get();
        $article = $this->orderByArticles($article, $request);
        $preparedArticleData = $request->paginate ? $this->paginate($article, $request->paginate, $request->page?:1) : $article;
        $response = array_values($preparedArticleData->makeHidden(['comments','content'])->toArray());
        return response()->json(['code'=>200, 'data'=>$response], 200);
    }

    /**
     * order by from request param.
     * 
     * @param query $articles
     * @param Illuminate\Http\Request $request
     * 
     * @return array/object
     */
    public function orderByArticles($articles, $request)
    {
        $sortRequest = $request->sort; 
        return $this->checkOrderBy($request) ? $articles->sortBy($sortRequest) : $articles->sortByDesc($sortRequest);
    }

    /**
     * Get comment data from article by article id.
     * 
     * @param integer $id
     * @param Illuminate\Http\Request $request
     * 
     * @return json
     */
    public function getArticle($id, $request)
    {
        $article = $this->find($id);
        if(empty($article))
        {
            return response()->json(['code'=>404,'data'=>'there is no data'],404);
        }
        $article->increment('view_count');
        $sort = $request->sort ?:'created_at';
        $article = $this->checkOrderBy($request) ? $article->comments->sortBy($sort) : $article->comments->sortByDesc($sort);
        $response = $this->articleComentRequest($request) ? response()->json(['code'=>400],400) : array_values($article->toArray());

        return $response;
    }

    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkOrderBy($request)
    {
        return $request->order == 'asc';
    }

    /**
     * Check article comment request.
     * 
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function articleComentRequest($request)
    {
        $sort = $request->sort ? $request->sort != 'created_at' : false;
        $order = $request->order ? $this->checkOrderRequest($request->order) : false;
        return $sort || $order;
    }
    
    /**
     * Check request params.
     * 
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkRequestParams($request)
    {
        $sort = $request->sort?$this->checkSortRequest($request->sort):false;
        $order = $request->order?$this->checkOrderRequest($request->order):false;
        $limit = $request->limit?$this->checkLimitRequest($request->limit):false;
        $paginate = $request->paginate?$this->checkPaginateRequest($request->paginate):false;
        $page = $request->page?$this->checkpageRequest($request->page):false;
        return $sort||$order||$limit||$paginate||$page;
    }

    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkSortRequest($sort)
    {
        $commands = ['comment_count', 'view_count', 'created_at'];
        return !in_array($sort, $commands);
    }

    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkOrderRequest($order)
    {
        $commands = ['desc', 'asc'];
        return !in_array($order, $commands);
    }

    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkLimitRequest($limit)
    {
        return !is_numeric($limit);
    }
    
    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkPaginateRequest($paginate)
    {
        return !is_numeric($paginate);
    }

    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkpageRequest($page)
    {
        return !is_numeric($page);
    }
}
<?php

namespace App\Analyzers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Analyzers\ArticleAnalyzer;

class TagAnalyzer extends Tag
{

    /**
     * @return object
     */
    public function paginate($items, $perPage, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path'=>url($this->request->fullUrl())]);
    }

    /**
     * Check action.
     * 
     * @param Illuminate\Http\Request $request
     * @param integer $tagId
     * 
     * @return \Illuminate\Http\Response
     */
    public function tags($request, $tagId=null)
    {
        $this->request = $request;
        $this->tagId = $tagId;
        return $tagId ? $this->getTagArticle($tagId) : $this->checkRequestParams($request);
    }

    /**
     * get tags data in json format.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function getTags()
    {
        $tags = $this->withCount('articles as article_count')->get();
        $sort = $this->request->sort ?:'created_at'; 
        $tags = $this->checkOrderBy() ? $tags->sortBy($sort) : $tags->sortByDesc($sort);
        $response = array_values($tags->toArray());
        return response()->json(['code'=>200, 'data'=>$response], 200);
    }

    /**
     * @return bool
     */
    public function checkOrderBy()
    {
        return $this->request->order == 'asc';
    }

    /**
     * get article data from tags by tag id. 
     * 
     * @param integer
     * @return @return \Illuminate\Http\Response
     */
    public function getTagArticle($id)
    {
        $tag = Article::whereHas('tags', function ($query) use($id) {
            $query->where('tags.id', $id);
        });
        $response = (new ArticleAnalyzer)->getArticles($tag, $this->request);
        return $tag->get()->isEmpty() ? response()->json(['code'=>404, 'data'=>'there is no data'], 404) : $response;
    }

    /**
     * Check param and get tags data in json format.
     * 
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function checkRequestParams($request)
    {
        $sort = $request->sort?$this->checkSortRequest($request->sort):false;
        $order = $request->order?$this->checkOrderRequest($request->order):false;
        $error = $sort||$order;
        return $error ? response()->json(['code'=>'400'], 400) : $this->getTags();
    }

    /**
     * @param Illuminate\Http\Request
     * @return bool
     */
    public function checkSortRequest($sort)
    {
        $commands = ['article_count', 'created_at'];
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

}
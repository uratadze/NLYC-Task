<?php

namespace App\Repository\Eloquent;

use App\Repository\ArticleRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * paginate prepate data.
     * 
     * @param collection $items
     * @param integer $perPage
     * @param integer $page
     * 
     * @return object
     */
    public function paginate($items, $perPage, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page);
    }

    /**
     * Get articles in json format.
     * 
     * @param Model $article
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function getArticles($article, $request): JsonResponse
    {
        $article = $article->limit($request->limit?:10);
        $article->increment('view_count');
        $article = $article->with('tags')
                            ->withCount('comments as comment_count')
                            ->get();
        $article = $this->checkOrderBy($request) ? $article->sortBy($request->sort) : $article->sortByDesc($request->sort);
        $preparedArticleData = $request->paginate ? $this->paginate($article, $request->paginate, $request->page?:1) : $article;
        $response = array_values($preparedArticleData->makeHidden(['comments','content'])->toArray());
        return response()->json(['code'=>200, 'data'=>$response], 200);  
    }

    /**
     * @param Illuminate\Http\Request $request
     * @return bool
     */
    public function checkOrderBy($request)
    {
        return $request->order == 'asc';
    }

    /**
     * Get comment data from article by article id.
     * 
     * @param Model $article
     * @param Illuminate\Http\Request $request
     * 
     * @return JsonResponse
     */
    public function getArticle($article, $request): JsonResponse
    {
        $article->increment('view_count');
        $sort = $request->sort ?:'created_at';
        $article = $this->checkOrderBy($request) ? $article->comments->sortBy($sort) : $article->comments->sortByDesc($sort);
        $response = array_values($article->toArray());
        return response()->json(['code'=>200, 'data'=>$response], 200);
    }
}
<?php   

namespace App\Repository\Eloquent;   

use App\Repository\TagRepositoryInterface; 
use Illuminate\Database\Eloquent\Model;  
use Illuminate\Http\JsonResponse;
use App\Models\Tag;

use App\Models\Article;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repository\Eloquent\ArticleRepositoryInterface; 

class TagRepository extends ArticleRepository implements TagRepositoryInterface 
{     
    /**      
     * @var Model      
     */     
     protected $tagModel;       

    /**      
     * TagRepository constructor.      
     *      
     * @param App\Models\Tag $model      
     */     
    public function __construct(Tag $tagModel)     
    {         
        $this->tagModel = $tagModel;
    }

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
     * get tags data in json format.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getTags($request): JsonResponse
    {
        $tags = $this->tagModel->withCount('articles as article_count')->get();
        $sort = $request->sort ?:'created_at'; 
        $tags = $request->order == 'asc' ? $tags->sortBy($sort) : $tags->sortByDesc($sort);
        $response = array_values($tags->toArray());
        return response()->json(['code'=>200, 'data'=>$response], 200);
    }

    /**
     * get article data from tags by tag id. 
     * 
     * @param Request $request
     * @param integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTagArticle($request, $id): JsonResponse
    {
        $tag = Article::whereHas('tags', function ($query) use($id) {
            $query->where('tags.id', $id); 
        });
        $response = $this->getArticles($tag, $request);
        return $tag->get()->isEmpty() ? response()->json(['code'=>404, 'data' => 'Not Found!'], 404) : $response;
    }
 
}
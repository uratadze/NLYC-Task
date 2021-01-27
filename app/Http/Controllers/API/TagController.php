<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Requests\ArticleRequest;
use App\Analyzers\TagAnalyzer;

use App\Repository\TagRepositoryInterface; 

class TagController extends Controller
{
    /**
     * @var Model
     */
    private $tagRepository;

    /**
     * Create a new repository instance.
     *
     * @param App\Repository\TagRepositoryInterface
     * @return void
     */
    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param App\Http\Requests\TagRequest $request
     * @return JsonResponse
     */
    public function tags(TagRequest $request)
    {
        return $this->tagRepository->getTags($request);
    }

    /**
     * @param App\Http\Requests\ArticleRequest $request
     * @param integer $id
     * @return JsonResponse
     */
    public function tagArticle(ArticleRequest $request, $id)
    {
        return $this->tagRepository->getTagArticle($request, $id);
    }

}

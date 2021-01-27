<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ArticleCommentRequest;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Repository\ArticleRepositoryInterface; 


class ArticleController extends Controller
{
    /**
     * @var Model
     */
    private $articleRepository;

    /**
     * Create a new repository instance.
     *
     * @param App\Repository\ArticleRepositoryInterface
     * @return void
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param App\Http\Requests\ArticleRequest $request
     * @return JsonResponse
     */
    public function articles(ArticleRequest $request)
    {
        return $this->articleRepository->getArticles(new Article, $request);
    }

    /**
     * @param App\Http\Requests\ArticleCommentRequest $request
     * @param App\Models\Article $id
     * @return JsonResponse
     */
    public function articleComments(ArticleCommentRequest $request, article $id)
    {
        return $this->articleRepository->getArticle($id, $request);
    }

}

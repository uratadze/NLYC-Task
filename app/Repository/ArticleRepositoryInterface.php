<?php
namespace App\Repository;

use App\Model\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
* Interface ArticleRepositoryInterface
* @package App\Repositories
*/
interface ArticleRepositoryInterface
{
   /**
    * @param Model $article
    * @param Request $request
    * 
    * @return JsonResponse
    */
   public function getArticles(Model $article, Request $request): JsonResponse;

   /**
    * @param Model $article
    * @param Request $request
    * 
    * @return JsonResponse
    */
   public function getArticle(Model $article, Request $request): JsonResponse;
}
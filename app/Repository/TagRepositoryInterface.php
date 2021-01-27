<?php   

namespace App\Repository;

use Illuminate\Http\JsonResponse;

/**
* Interface TagRepositoryInterface
* @package App\Repositories
*/
interface TagRepositoryInterface
{
   /**
    * @param Request $attributes
    * @return JsonResponse
    */
   public function getTags(Request $request): JsonResponse;

   /**
    * @param Request $attributes
    * @return JsonResponse
    */
   public function getTagArticle(Request $request, integer $id): JsonResponse;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'article_comment';

    /**
     * @var array
     */
    protected $hidden = ['pivot'];
}

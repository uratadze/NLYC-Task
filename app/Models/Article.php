<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'articles';

    /**
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * @var bool
     */
    public $timestamps =false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function comments()
    {
        return $this->belongsToMany(Comment::class, ArticleComment::class, 'article_id','comment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, ArticleTag::class, 'article_id','tag_id');
    }

}

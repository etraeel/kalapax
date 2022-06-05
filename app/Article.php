<?php

namespace App;

use App\Http\Controllers\Article\ArticleController;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    use Sluggable;
    protected $fillable = [
        'title','text', 'writer', 'password','key_words','reading_time','image','description',
    ];

//    public $timestamps = false;

    public function likes(){
        return $this->hasMany(ArticleLike::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

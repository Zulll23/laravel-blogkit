<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    
        // Order by latest posts by default
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the model's Description. If one has not been set we return a truncated part of the body.
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function description(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new Attribute(
            get: fn ($value) => empty($value)
                ? substr($this->body, 0, 255)
                : $value
        );
    }

    /**
     * Get the model's Featured Image. If one has not been set we return a default image.
     * 
     * Default Image by Picjumbo
     * @see https://picjumbo.com/tremendous-mountain-peak-krivan-in-high-tatras-slovakia/
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function featuredImage(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new Attribute(
            get: fn ($value) => empty($value)
                ? asset('storage/default.jpg')
                : $value
        );
    }

    /**
     * Get the author associated with the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get all of the comments for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}

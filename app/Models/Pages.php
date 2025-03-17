<?php

namespace App\Models;

use App\Models\Sections;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->with('sections')->with('sub_pages')->with('parent_page');
        });
    }

    protected $fillable = ['slug', 'name', 'parent_id', 'sort_id'];

    protected $casts = [
        'name' => 'json',
    ];

    public function sections()
    {
        return $this->hasMany(Sections::class);
    }

    public function sub_pages()
    {
        return $this->hasMany(Pages::class, 'parent_id');
    }

    public function parent_page()
    {
        return $this->hasOne(Pages::class);
    }

}


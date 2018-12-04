<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;

    protected $table = 'categories';

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class PostFilter
{
    public function filter(){
        return[
            'comment',
            'rate',
                AllowedFilter::callback('item', function (Builder $query, $value) {
                    $query->where('comment','like',"%{$value}%")
                        ->orWhere('rate','like',"%{$value}%")
                        ->orWhereHas('post',function (Builder $query)use($value)
                        {
                            $query->whereHas('worker',function (Builder $query)use($value){
                                $query->where('name','like',"%{$value}%");
                            });
                        });
                })
        ];
    }
}

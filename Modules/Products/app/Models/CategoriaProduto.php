<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CategoriaProduto extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'categorias_produtos';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'created_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = time();
            }
        });
    }
}

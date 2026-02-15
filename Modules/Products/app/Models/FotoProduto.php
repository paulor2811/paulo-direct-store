<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FotoProduto extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fotos_produtos';
    public $timestamps = false;

    protected $fillable = [
        'produto_id',
        'caminho_imagem',
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

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}

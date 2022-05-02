<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Trade
 *
 * @property int $id
 * @property float $import
 * @property float|null $saldo
 * @property int $platform_id
 * @property string $iscrizione
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Platform $platform
 * @method static \Illuminate\Database\Eloquent\Builder|Trade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereImport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereIscrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Trade extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getIscrizioneFormattataAttribute()
    {
        return Carbon::make($this->iscrizione)->format('d-m-Y');
    }

    public function getImportoFormattatoAttribute()
    {
        return '€ '.number_format($this->import,0,'.', '.');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function scopeAperture($query)
    {
        return $query->whereHas('platform', function($p){
            $p->where('name', 'like', '%Aperture%');
        });
    }

    public function scopeNonaperture($query)
    {
        return $query->whereDoesntHave('platform', function($p){
            $p->where('name', 'like', '%Aperture%');
        });
    }
}

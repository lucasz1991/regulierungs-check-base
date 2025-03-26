<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ShelfRental;


class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Die Felder, die massenweise zuweisbar sind.
     *
     * @var array
     */
    protected $fillable = [
        'shelf_rental_id',
        'invoice_identifier',
    ];

    /**
     * Beziehung: Eine Rechnung gehört zu einer Buchung.
     */
    public function shelfRental()
    {
        return $this->belongsTo(ShelfRental::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function scopeSelectResults($query){
        return $query->select(
            'id',
            'application_id',
            'branch',
            'iterations_total',
            'iterations_failed',
            'requests_total',
            'requests_failed',
            'tests_total',
            'tests_failed',
            'assertions_total',
            'assertions_failed',
            'started',
            'completed',
            'responseAverage',
            'responseMin',
            'responseMax',
            'duration',
            'parsed_results',
            'created_at'
        );
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    function date()
    {
        return $this->created_at->format('d-m-Y');
    }
}

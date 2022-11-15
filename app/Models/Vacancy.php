<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const DATE = 'date';
    public const RESPONSES = 'responses';

    protected $fillable = [
        'title',
        'description',
    ];

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public static function getAll(
        ?string $creationDate,
        ?string $creationWeek,
        ?string $creationMonth,
        ?string $sort,
        ?string $sortDir,
    ) {
        return self::query()
            ->when($creationDate, fn ($q) => $q->whereDate('vacancies.created_at', $creationDate)) //2022-11-12
            ->when($creationWeek, fn ($q) => $q->whereRaw('WEEK(vacancies.created_at) = ?', $creationWeek)) //9
            ->when($creationMonth, function ($q) use ($creationMonth) { //2022-11
                $date = Carbon::createFromDate($creationMonth);
                return $q
                    ->whereYear('vacancies.created_at', $date->year)
                    ->whereMonth('vacancies.created_at', $date->month);
            })
            ->when($sort == self::DATE, fn ($q) => $q->orderBy('vacancies.created_at', $sortDir ?? 'asc'))
            ->when($sort == self::RESPONSES, function ($q) use ($sortDir) {
                return $q->select('vacancies.*')
                    ->leftJoin('responses as r', 'r.vacancy_id', 'vacancies.id')
                    ->groupBy('vacancies.id')
                    ->orderByRaw($sortDir ?? 'asc' ? "count(r.id)" : "count(r.id) desc");
            })
            ->get();
    }
}

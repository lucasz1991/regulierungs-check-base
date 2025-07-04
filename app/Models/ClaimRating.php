<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Customer\ClaimRatingController;
use Carbon\Carbon;
use App\Models\RatingTag;
use App\Jobs\ClaimRatingAIEval;


class ClaimRating extends Model
{
    use HasFactory, SoftDeletes;
    // Possible statuses for the ClaimRating model:
    // - pending: The claim rating is awaiting review.
    // - rated: The claim rating is awaiting review.
    // - approved: The claim rating has been approved.
    // - rejected: The claim rating has been rejected.
    // - published: The claim rating has been published.
    
    protected $fillable = [
        'user_id',
        'insurance_subtype_id',
        'insurance_type_id',
        'insurance_id',
        'rating_questionnaire_versions_id',
        'answers',
        'status',
        'attachments',
        'rating_score',
        'tag_ids',
        'moderator_comment',
        'is_public',
        'verification_hash',
    ];

    protected $casts = [
        'answers' => 'array',
        'attachments' => 'array',
        'is_public' => 'boolean',
        'tag_ids' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($claimRating) {
            ClaimRatingController::evaluateScore($claimRating);
        });
        static::updated(function ($claimRating) {
            ClaimRatingController::evaluateScore($claimRating);
        });
    }

    public function reanalyze()
    {
        $this->is_public = false;
        $this->status = 'pending';
        $this->saveQuietly();
        ClaimRatingAIEval::dispatch($this);
    }

    public function comment()
    {
        return $this->attachments['scorings']['ai_overall_comment'] ?? '';
    }

    public function score()
    {
        return $this->rating_score ?? '';
    }

    public function ratingDuration()
    {
        $start = isset($this->answers['selectedDates']['started_at'])
            ? Carbon::parse($this->answers['selectedDates']['started_at'])
            : null;
    
        if (!$start) {
            return null; // oder 0 oder "unbekannt", je nach Bedarf
        }
    
        $end = isset($this->answers['is_closed']) && $this->answers['is_closed']
            ? Carbon::parse($this->answers['selectedDates']['ended_at'] ?? now())
            : now();
    
        return $start->diffInDays($end);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function insuranceType()
    {
        return $this->belongsTo(InsuranceType::class);
    }
    
    public function insuranceSubtype()
    {
        return $this->belongsTo(InsuranceSubtype::class);
    }

    public function questionnaireVersion()
    {
        return $this->belongsTo(RatingQuestionnaireVersion::class, 'rating_questionnaire_versions_id');
    }



    public function tags()
    {
        if (!$this->tag_ids || !is_array($this->tag_ids)) {
            return collect();
        }
    
        return RatingTag::whereIn('id', $this->tag_ids)->get();
    }
}

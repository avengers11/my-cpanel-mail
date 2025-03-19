<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonReviewTask extends Model
{
    use HasFactory;

    public function review(){
        return $this->belongsTo(AmazonReview::class, "amazon_review_id");
    }
}

<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Mymovielist\Movie;
use Mymovielist\User;
use Mymovielist\Review;

class ReviewController extends Controller
{

    public function review($mid, $rid)
    {
        $review = new Review($rid);

        if ($review->getMovie()->mid != $mid) {
            abort(404);
        }

        if(!$review->exist())
            abort(404);

        $movie      = new Movie($mid);
        $movieInfo  = $movie->getMovieInfo();
        $user       = $review->getAuthor();
        $user       = new User($user->login);
        $userInfo   = $user->getUserInfo();
        $reviewInfo = $review->getReviewInfo();

        return view(
            'review',
            [
                'movieinfo'  => $movieInfo,
                'userinfo'   => $userInfo,
                'reviewinfo' => $reviewInfo
            ]
        );
    }
}

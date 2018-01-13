<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
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

        if (!$review->exist()) {
            abort(404);
        }

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

    public function newReview($mid)
    {
        $movie     = new Movie($mid);
        $movieInfo = $movie->getMovieInfo();
        $user      = new User(Auth::user()->login);
        $userInfo  = $user->getUserInfo();

        if ($user->revived($movie)) {
            return redirect(route('movie', [$mid]))->with('error', 'You already wrote review');
        }

        return view(
            'newreview',
            [
                'movieinfo' => $movieInfo,
                'userinfo'  => $userInfo,
            ]
        );
    }

    public function createReview($mid)
    {
        $text = Input::get('text');
        if ($text != "") {
            if (Review::create(['text' => $text], $mid, Auth::user()->login)) {
                return redirect(route('movie', [$mid]))->with('message', 'Added');
            }
        }

        return redirect(route('movie', [$mid]))->with('message', 'Something went wrong');

    }

    public function deleteReview($mid, $rid)
    {
        $review = new Review($rid);
        if ($review->exist()) {
            $review->delete();
            return redirect()->route('movie',[$mid])->with('message', 'Deleted!');
        }

        return redirect()->back()->with('error', 'Something went wrong!');

    }
}

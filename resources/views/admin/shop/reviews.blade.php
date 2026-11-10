@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Reviews</h1>
@foreach($reviews as $review)
<p>{{ $review->review_title }} · {{ $review->rating }}/5 · {{ (int)$review->status === 1 ? 'Published' : 'Hidden' }}
<form class="d-inline" method="post" action="{{ route('reviews.status', $review->review_id) }}">@csrf<button class="btn btn-sm btn-outline-dark">Toggle</button></form></p>
@endforeach
{{ $reviews->links() }}
</div></main>@endsection

@if($reviews->count() > 0)
    <ol class="comment-list">
        @foreach($reviews as $review)
            <li class="comment even thread-even depth-1 comment" id="comment-{{ $review->id }}">
                <div class="comment-body">
                    <div class="comment-author vcard">
                        <img src="{{ $review->user && $review->user->profile_image 
                            ? asset('uploads/profile/' . $review->user->profile_image) 
                            : asset('https://cdn-icons-png.flaticon.com/512/847/847969.png') }}" 
                            alt="{{ $review->uname }}" class="avatar">
                        <cite class="fn">{{ $review->uname }}</cite> 
                    </div>
                    <div class="comment-content dz-page-text">
                        <p>{{ $review->comment }}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ol>
@else
    <p>No reviews yet.</p>
@endif

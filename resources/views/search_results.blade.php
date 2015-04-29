@extends ('layout')

@section ('title')
    Results
@stop

@section ('content')
@include ('navbar')

<div class="container" style="padding-top:15px">
    <div class="row" style="padding-bottom:15px">
        <a class="pull-left" href="{{ $urlIfDealsToggled }}">{{ $linkTitleIfDealsToggled }}</a>
        <img class="pull-right" src="{{ asset('Powered_By_Yelp_Red.png') }}">
    </div>
    @if ($businesses)
    @foreach($businesses as $business)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a href="/business/{{ $business->id }}">{{ $business->name }}</a>
                <div class="pull-right">
                    <p style="display:inline"><a href="{{ $business->url }}" target="_blank">{{ $business->review_count }}&nbsp;Reviews</a></p>
                </div>
            </h3>
        </div>
        <div class="panel-body">
            <img src="{{ $business->image_url }}" class="pull-left" style="padding-right:10px">
            <div class="pull-right">
                <img src="{{ $business->rating_img_url_large }}" class="pull-right">
                <br><br>
                <a href="{{ $business->url }}" target="_blank" class="pull-right">View Reviews on Yelp</a>
            </div>
            @foreach($business->location->display_address as $address_line)
                <p>{{ $address_line }}</p>
            @endforeach
            @for($i=0; $i < 4- count($business->location->display_address); $i++)
                <br>
            @endfor
            <br>
            <p class="pull-left">Phone: {{ $business->display_phone or 'No Phone Number Provided' }}</p>
            <br>
            <br>
            @if(property_exists($business, 'deals'))
                <p class="pull-left"><strong>Deals: Yes</strong></p>
            @else
                <p class="pull-left"><strong>Deals: No</strong></p>
            @endif
            <br>
            <br>
            <p class="pull-left" style="display:inline">Categories:&nbsp;</p>
            @foreach($business->categories as $category)
                <a href="/results?location={{ urlencode($business->location->city) }}&category_filter={{ $category[1] }}" target="_blank"><button class="btn btn-info btn-xs">{{ $category[0] }}</button></a>
            @endforeach
            @if (count($business->categories) == 0)
                <p class="pull-left" style="display:inline">None</p>
            @endif
            <a href="/business/{{ $business->id }}"><button class="btn btn-info pull-right">More Info</button></a>
        </div>
    </div>
    @endforeach
    @else
    <p class="text-danger">No Results Found</p>
    @endif
</div>

@stop
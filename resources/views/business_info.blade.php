@extends ('layout')

@section ('title')
    @if ($business)
        {{ $business->name }}
    @else
        Business Not Found
    @endif
@stop

@section ('content')
@include ('navbar')
@if ($business)
<div class="container" style="padding-top:15px">
    @if (count($errors) > 0)
        <div class = "alert alert-danger alert-dismissable">
            <a class="panel-close close" data-dismiss="alert">×</a> 
            @foreach ($errors->all() as $error)
                <div class="flash">{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @if ($success_message)
        <div class = "alert alert-success alert-dismissable">
            <a class="panel-close close" data-dismiss="alert">×</a> 
            <div class="flash">{{ $success_message }}</div>
        </div> 
    @endif
    @if ($error_message)
        <div class = "alert alert-danger alert-dismissable">
            <a class="panel-close close" data-dismiss="alert">×</a> 
            <div class="flash">{{ $error_message }}</div>
        </div>
    @endif
    <div class="row" style="padding-bottom:15px">
        <img class="pull-right" src="{{ asset('Powered_By_Yelp_Red.png') }}">
    </div>
    <div class="panel panel-default" >
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
            <p class="pull-left" style="display:inline">Categories:&nbsp;</p>
            @foreach($business->categories as $category)
                <a href="/results?location={{ urlencode($business->location->city) }}&category_filter={{ $category[1] }}" target="_blank"><button class="btn btn-info btn-xs">{{ $category[0] }}</button></a>
            @endforeach
            @if (count($business->categories) == 0)
                <p class="pull-left" style="display:inline">None</p>
            @endif
            {{-- @if ($currentUser) --}}
            {{--    <a href="/business/{{ $business->id }}"><button class="btn btn-info pull-right">More Info</button></a> --}}
            {{-- @else --}}
            {{--    <button class="btn btn-info pull-right" disabled="disabled">More Info</button> --}}
            {{--    <br><br> --}}
            {{--    <p class="pull-right"><small><a href="/login">Login</a> or <a href="/">Sign Up</a> to Access</small></p> --}}
            {{-- @endif --}}
        </div>
    </div>
</div>

<ul class="nav nav-tabs nav-justified">
    @if(property_exists($business, 'deals') or property_exists($business, 'gift_cards'))
    <li role="presentation" class="active"><a href="#deals" role="tab" data-toggle="tab"><i class="fa fa-money fa-lg"></i>&nbsp;&nbsp;Deals and Gift Cards</a></li>
    <li role="presentation"><a href="#menu" role="tab" data-toggle="tab"><i class="fa fa-cutlery fa-lg"></i>&nbsp;&nbsp;Menu</a></li>
    @else
    <li role="presentation" class="active"><a href="#menu" role="tab" data-toggle="tab"><i class="fa fa-cutlery fa-lg"></i>&nbsp;&nbsp;Menu</a></li>
    @endif
    <li role="presentation"><a href="#uber" role="tab" data-toggle="tab"><i class="fa fa-car fa-lg"></i>&nbsp;&nbsp;Ride Sharing</a></li>
    <li role="presentation"><a href="#photo" role="tab" data-toggle="tab"><i class="fa fa-camera-retro fa-lg"></i>&nbsp;&nbsp;Photos</a></li>
</ul>
<div class="tab-content">
    @if (property_exists($business, 'deals') or property_exists($business, 'gift_cards'))
    <div role="tabpanel" class="tab-pane fade in active" id="deals">
        <div class="container">
            @if(property_exists($business, 'deals'))
                <h2>Deals <img src="{{ asset('yelp_logo_50x25.png') }}"></h2>
                @foreach($business->deals as $deal)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a href="{{ $deal->url }}" target="_blank">{{ $deal->title }}</a>
                                @if(property_exists($deal, 'is_popular'))
                                <span class="pull-right text-success"><i class="fa fa-star"></i> Popular</span>
                                @endif
                            </h3>
                        </div>
                        <div class="panel-body">
                            @if(property_exists($deal, 'time_end'))
                                <p class="pull-left"><strong>Ends: {{ date('F jS Y h:i:s A (T)', $deal->time_end) }}</strong></p>
                            <br>
                            <br>
                            @endif
                            <p class="pull-left"><strong>What You Get</strong></p>
                            <br><br>
                            <?php echo "$deal->what_you_get" ?>
                            <br>
                            <br>
                            <p class="pull-left"><strong>Options</strong></p>
                            <br>
                            <br>
                            @foreach ($deal->options as $option)
                                <p class="pull-left" style="display:inline"><a href="{{ $option->purchase_url }}" target="_blank">{{ $option->title }}</a>: Costs {{ $option->formatted_price }} (originally {{ $option->formatted_original_price }})</p>
                                @if($option->is_quantity_limited)
                                    <p class="pull-left" style="display:inline"><em> - Limited Number Available</em></p>
                                @else
                                    <p class="pull-left" style="display:inline"></p>
                                @endif
                            <br>
                            <br>
                            @endforeach
                            <p class="pull-left"><a href="{{ $deal->url }}" target="_blank">More Info</a></p>
                            <br><br>
                            <p class="pull-left"><em><small>{{ $deal->important_restrictions }}</small></em></p>
                            <br><br>
                            <button class="btn btn-default pull-left" data-toggle="collapse" data-target="#restrictions">Other Restrictions</button>
                            <br><br>
                            <div class="collapse" id="restrictions">
                                <p class="pull-left"><em><small><?php echo "$deal->additional_restrictions" ?></small></em></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            @if(property_exists($business, 'gift_certificates'))
                <h2>Gift Cards <img src="{{ asset('yelp_logo_50x25.png') }}"></h2>
                @foreach($business->gift_certificates as $gift)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="pull-left" style="display:inline"><strong>Prices:</strong>&nbsp;</p>
                            @foreach ($gift->options as $option)
                                <p class="pull-left" style="display:inline">{{ $option->formatted_price }}&nbsp;</p>
                            @endforeach
                            <br><br>
                            <p class="pull-left"><a href="{{ $gift->url }}" target="_blank">More Info</a></p>
                            <br><br>
                            <p class="pull-left">Unused Balance: {{ $gift->unused_balances }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="menu" style="padding-top:15px">
        <div class="container" style="padding-top:15px">
             <script id="-locu-widget" type="text/javascript" src="{{ $menu_url }}"></script>
        </div>
    </div>
    @else
    <div role="tabpanel" class="tab-pane fade in active" id="menu">
        <div class="container" style="padding-top:15px">
            <script id="-locu-widget" type="text/javascript" src="{{ $menu_url }}"></script>
        </div>
    </div>
    @endif
    <div role="tabpanel" class="tab-pane fade" id="uber">
        <div class="container" style="padding-top:15px">
            @if($uber)
                <div class="row" style="padding-bottom:15px">
                    <img class="pull-right" src="{{ asset('UBER_API_RIDE BY UBER Badges_1x GREY_22px.png') }}">
                </div>
                @foreach($uber->products as $vehicle)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{ ucwords($vehicle->display_name) }}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <p>
                                {{ '$' . number_format($vehicle->price_details->base,0) }}
                                <small>Base Fare</small> <i class="fa fa-plus-circle"></i> {{ '$' . number_format($vehicle->price_details->cost_per_distance, 2) }}<small>/{{ $vehicle->price_details->distance_unit }}</small> <i class="fa fa-plus-circle"></i> {{ '$' . number_format($vehicle->price_details->cost_per_minute, 2) }}<small>/minute</small>
                                @foreach ($vehicle->price_details->service_fees as $fee)
                                    <i class="fa fa-plus-circle"></i> {{ '$' . number_format($fee->fee) }}<small> {{ $fee->name }}</small>
                                @endforeach
                            </p>
                            <p><small>Min Fee: {{ '$' . number_format($vehicle->price_details->minimum, 2) }}<br>Cancellation Fee: {{ '$' . number_format($vehicle->price_details->cancellation_fee, 2) }}<br>Capacity: {{ $vehicle->capacity  }}</small></p>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Uber rates not available here</p>
            @endif
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="photo">
        <div class="container">
            @if ($currentUser)
            <form class="form-inline" style="padding-top:15px" method="post" action="/username-suggestion/{{ $business->id }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="username"><strong>Restaurant's Instagram Username:&nbsp;</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Suggest username" name="username" id="username">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Suggest</button>
                        </span>
                    </div>
                </div>
            </form>
            <br><br>
            @else
            <form class="form-inline" style="padding-top:15px">
                <div class="form-group">
                    <label for="username_dud"><strong>Restaurant's Instagram Username:&nbsp;</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Suggest username" id="username_dud">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" disabled="disabled">Suggest</button>
                        </span>
                    </div>
                </div>
            </form>
            <br><br>
            <p class=""><small><a href="/login">Login</a> or <a href="/">Sign Up</a> to Access</small></p>
            <br>
            @endif
            @if ($instagram_user)
            <div id="user_carousel" class="carousel slide" data-ride="carousel" data-interval="false" data-wrap="false">
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="http://www.techspot.com/images2/downloads/topdownload/Instagram.png" class="center-block">
                        <div class="carousel-caption">
                            <h3>Photos By Restaurant Owners</h3>
                        </div>
                    </div>
                    @foreach ($instagram_user->data as $data)
                        @if ($data->type == 'image')
                            <div class="item">
                            <img src="{{ $data->images->standard_resolution->url }}" class="center-block">
                            <div class="carousel-caption">
                                <h3><a target="_blank">{{ $data->link }}</a></h3>
                                <p>{{ $data->caption->text }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                <a class="left carousel-control" href="#user_carousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </a>
                <a class="right carousel-control" href="#user_carousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </a>
            </div>
            <br>
            @endif
            @if ($instagram_location)
            <div id="location_carousel" class="carousel slide" data-ride="carousel" data-interval="false" data-wrap="false">
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="http://www.techspot.com/images2/downloads/topdownload/Instagram.png" class="center-block">
                        <div class="carousel-caption">
                            <h3>Photos Taken at this Location</h3>
                        </div>
                    </div>
                    @foreach ($instagram_location as $location)
                        @foreach ($location->data as $data)
                            @if ($data->type == 'image')
                            <div class="item">
                                <img src="{{ $data->images->standard_resolution->url }}" class="center-block">
                                <div class="carousel-caption">
                                    <h3><a target="_blank">{{ $data->link }}</a></h3>
                                    @if ($data->caption)
                                        <p>{{ $data->caption->text }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <a class="left carousel-control" href="#location_carousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </a>
                <a class="right carousel-control" href="#location_carousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@else
    <p class="text-danger">No Business Found</p>
@endif
@stop
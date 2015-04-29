<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header page-scroll">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
      </button>
        <a class="navbar-brand" href="#">There and Back</a>
    </div>
    <div class="navbar-collapse collapse">
        @if ($currentUser)
            @include ('navbar-userdropdown')
         @else
            @include ('navbar-nouserdropdown')
        @endif
        
        {{-- @if ($navbarAllowSearch) --}}
            @include ('navbar-search')
        {{-- @endif --}}
    </div>
</nav>
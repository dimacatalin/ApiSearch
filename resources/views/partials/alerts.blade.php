@if (Session::has('alert-success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {!! Session::get('alert-success') !!}
    </div>
@endif
@if(Session::has('alert-error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {!! Session::get('alert-error') !!}
    </div>
@endif
@if(Session::has('file-alert-error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {!! Session::get('file-alert-error') !!}
    </div>
@endif
@if(Session::has('alert-warning'))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {!! Session::get('alert-warning') !!}
    </div>
@endif

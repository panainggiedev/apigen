@extends('api.layout')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="card-header">{{ __('API') }}</div>
                <div class="card-body">
                    @forelse($api as $apikey) 
                    <form action="{{ route('api.create') }}" method="GET">
                        @csrf <!-- {{ csrf_field() }} -->
                      <div class="form-group">
                        <label>Current API <p></p></label>
                        
                        <input type="text" class="form-control" id="currentAPI" name="currentAPI" value ="{{ $apikey-> api_key}}" placeholder="{{ $apikey-> api_key}}" readonly>
                        <input type="text" class="form-control" id="idAPI" name="idAPI" value ="{{ $apikey-> id}}" hidden>
                        <br>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                        @empty
                        
                        <form action="{{ route('api.create') }}" method="GET">
                        @csrf <!-- {{ csrf_field() }} -->
                      <div class="form-group">
                        <label>Current API <p></p></label>
                        
                        <input type="text" class="form-control" id="currentAPI" name="currentAPI" value ="Empty Database" readonly>
                        <input type="text" class="form-control" id="idAPI" name="idAPI" value ="0" hidden>
                        <br>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                        @endforelse
                   
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 style="text-align:center;">API Key History</h3></div>
                <div class="card-body">
                    <div class="table-responsive">
    
                    <table id="myTable">
                        
                        <thead>
                            <tr>
                            <td>API KEY</td>
                            <td>Created By:</td>
                            <td>Updated At:</td>
                        </tr>
                        </thead>
                        <tbody>
                             @foreach ($historylist->sortBy('id') as $historylist)
                        <tr>
                            <td>{{$historylist-> api_key}}</td>
                            <td>{{$historylist-> created_by}}</td>
                            <td>{{$historylist-> updated_at}}</td>
                        </tr> 
                        @endforeach
                        </tbody>
                        
                       
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
    
@endsection
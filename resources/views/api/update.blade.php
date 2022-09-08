@extends('api.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('API') }}</div>
                <div class="card-body">

                    @if($errors)

                        @foreach($errors->all() as $errors)
                        <div class="alert alert-danger">
                        <p>{{ $errors }}</p>
                            </div>
                        @endforeach
                    @endif
                    <form action="{{ route('api.store') }}" method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                      <div class="form-group">

                        <label>Insert new API</label>

                        <input type="text" class="form-control" name="newAPI" id="newAPI" placeholder="Insert New API KEY" >
                        <input type="text" class="form-control" name="loguser" id="loguser" value="{{ Auth::user()->name }}" hidden>

                        <input type="text" class="form-control" name="idCurr" id="idCurr" value=<?php echo $id = $_GET['idAPI']; ?> hidden>
                        <br>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
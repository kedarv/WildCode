@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      @foreach($challenges as $challenge)
        <div class="col-md-4">
          <div class="well">
            <h4 style="margin-top:0px;">{{$challenge->title}} <small>{{$challenge->test()}}</small></h4>
            <hr/>
            <span class="label label-default">Strings</span><br/>
            {{$challenge->description}}
            <hr/>
            <a class="btn btn-primary" href="{{action('HomeController@challenge', ['id' => $challenge->id])}}">Go &raquo;</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@stop
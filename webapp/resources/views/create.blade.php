@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
    <div id="message"></div>
      <div class="well">
        <form id="createForm">
        <input type="hidden" value="{{csrf_token()}}" name="_token">
          <div class="form-group">
            <label for="title">Challenge Title</label>
            <input type="text" name="title" class="form-control" id="title" placeholder="Challenge Title">
          </div>
          <div class="form-group">
            <label for="description">Challenge Description</label>
            <input type="text" name="description" class="form-control" id="description" placeholder="Challenge Description">
          </div>
          <div class="form-group">
            <label for="instructions">Challenge Instructions</label>
            <textarea class="form-control" rows="2" name="instructions"></textarea>
          </div>
          <div class="form-group">
            <select class="form-control" name="difficulty">
              <option value="1">Easy</option>
              <option value="2">Moderate</option>
              <option value="3">Difficult</option>
            </select>
          </div>
          <div class="form-group">
            <label for="prototype">Function Signature</label>
            <input type="text" name="prototype" class="form-control" id="prototype" placeholder="concat(String text, String text2)">
          </div>
          <div class="form-group">
            <label for="return_type">Function Return Type</label>
            <input type="text" name="return_type" class="form-control" id="return_type" placeholder="String">
          </div>
          <hr/>
          <h4>Test Cases</h4>
          <div class="form-group" id="fields">
            <div class="row">
                <div id="container1">
                  <div class="col-xs-5">
                    <input autocomplete="off" class="form-control" id="field1" name="test[]" type="text" placeholder="Input">
                  </div>
                  <div class="col-xs-5">
                  <input autocomplete="off" class="form-control" id="field2" name="test[]" type="text" placeholder="Expected Output">
                  </div>
                  <div class="col-xs-1 col-xs-offset-1">
                  <button id="b1" class="btn add-more" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                  </div>
              </div>
            </div>
        </div>
          <button type="submit" class="btn btn-default">Submit</button>
        </form> 
      </div>
    </div>
  </div>
</div>
@stop
@section('bottom_js')
<script>
$(document).ready(function(){
    var next = 2;
    var counter = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#container" + counter;
        next = next + 2;
        counter++;
        var newIn = '<div id="container' + counter + '"><div class="col-xs-5"> <input autocomplete="off" class="form-control" id="field' + (next-1) + '" name="test[]" type="text" placeholder="Input"></div><div class="col-xs-5"> <input autocomplete="off" class="form-control" id="field' + (next) + '" name="test[]" type="text" placeholder="Expected Output"></div><div class="col-xs-1 col-xs-offset-1"><button class="remove btn btn-danger" type="button" id="' + next + '"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div>';
        $(addto).after($(newIn)); 
          $('.remove').click(function(e){
              e.preventDefault();
              var fieldNum = this.id;
              var firstFieldID = "#field" + fieldNum;
              var secondFieldID = "#field" + (fieldNum-1);
              $(firstFieldID).remove();
              $(secondFieldID).remove();
              $(this).remove();
          });
    });
    


    $('#createForm').submit(function(event) {
        $("#message").fadeOut().removeClass("alert alert-success");
        $.ajax({
            type: 'POST',
            url: '{{action('HomeController@submitCreate')}}',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if(data['message'] == 'success') {
                     $("#message").fadeIn().addClass("alert alert-success").html("Success");
                }
            }
        });
        event.preventDefault();

}); 
});
</script>

@stop
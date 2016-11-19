@extends('layouts.app')

@section('content')
<style>
   #editor {
        height: 500px;
    }
  </style>

<div class="container">
    <div class="row">
    <div class="col-md-3">
        <div class="well">
            problem description
        </div>
    </div>
        <div class="col-md-9">
<pre id="editor">
public class Practice {
    
}
</pre>
        <hr/>
        <button class="btn btn-primary" id="submit">Submit &raquo;</button>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body" id="body-content">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('bottom_js')
    <script src="js/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setHighlightActiveLine(false);
    editor.setShowPrintMargin(false);
    document.getElementById('editor').style.fontSize='14px';
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/java");
    $(function() {
        $("#submit").click(function(){ 
            $.ajax({
                type: 'POST',
                url: '{{action('HomeController@submit')}}',
                data: {"_token": "{{ csrf_token() }}", 'code' : editor.getValue()},
                dataType: 'json',
                success: function(data) {
                    if(data['message'] == 'success') {
                        $("#body-content").html(data['output']);
                         $('#myModal').modal({
  keyboard: false
})
                    }
                }
            });
            event.preventDefault();
        });
    });
</script>
@stop
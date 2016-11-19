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
                         $("#message").fadeIn().addClass("alert alert-success").html("Successfully added interview slots.") ;
                    }
                }
            });
            event.preventDefault();
        });
    });
</script>
@stop
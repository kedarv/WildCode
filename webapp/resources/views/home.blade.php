@extends('layouts.app')

@section('content')
<style>
   body {
        overflow: hidden;
    }
   #editor {
        height: 500px;
        margin: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
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
</script>
@stop
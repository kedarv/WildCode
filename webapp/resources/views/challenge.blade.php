@extends('layouts.app')

@section('content')
<style>
   #editor {
        height: 500px;
    }
    .readonly-highlight{
    background-color: red;
    opacity: 0.2;
    position: absolute;
}
  </style>

<div class="container">
    <div class="row">
    <div class="col-md-3">
        <div class="well">
            <h3 style="margin-top:0px;">{{$challenge->title}}</h3>
            <hr/>
            {{$challenge->instructions}}
        </div>
    </div>
        <div class="col-md-9">
        <pre id="editor">{{$challenge_data->code}}</pre>
        <span id="saved"></span>
        <hr/>
        <button class="btn btn-primary" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Compiling">Submit &raquo;</button>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalTitle">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalTitle"></h4>
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
    <script src="https://ace.c9.io/build/src/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var prototype = "public static String {{$challenge->prototype}}".length+2;
    var editor     = ace.edit("editor")
        , session  = editor.getSession()
        , Range    = require("ace/range").Range
        , range    = new Range(0, 0, 0, prototype)
        , markerId = session.addMarker(range, "readonly-highlight");
    var timeout;
    editor.setHighlightActiveLine(false);
    editor.setShowPrintMargin(false);
    document.getElementById('editor').style.fontSize='14px';
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/java");
    editor.getSession().on('change', function() {
        clearTimeout(timeout);
        console.log("changed");
        timeout = setTimeout(function() {
            saveToDB();
        }, 1000);
    });
    function saveToDB() {
    $("#saved").html('Saving...');
    $.ajax({
        type: 'POST',
        url: '{{action('HomeController@commitCode')}}',
        data: { "_token": "{{ csrf_token() }}", "challenge_id": {{$challenge->id}}, "code": editor.getValue()},
        dataType: 'json',
        success: function(data) {
            if(data['message'] == 'success') {
                 $("#saved").html('<abbr title="' + data['updated_at'] +'">Saved.</abbr>');
            }
        }
    });
}
 // session.setMode("ace/mode/javascript");
    editor.keyBinding.addKeyboardHandler({
        handleKeyboard : function(data, hash, keyString, keyCode, event) {
            if (hash === -1 || (keyCode <= 40 && keyCode >= 37)) return false;
            
            if (intersects(range)) {
                return {command:"null", passEvent:false};
            }
        }
    });
    
    before(editor, 'onPaste', preventReadonly);
    before(editor, 'onCut',   preventReadonly);
    
    range.start  = session.doc.createAnchor(range.start);
    range.end    = session.doc.createAnchor(range.end);
    range.end.$insertRight = true;
    
    function before(obj, method, wrapper) {
        var orig = obj[method];
        obj[method] = function() {
            var args = Array.prototype.slice.call(arguments);
            return wrapper.call(this, function(){
                return orig.apply(obj, args);
            }, args);
        }
        
        return obj[method];
    }
    
    function intersects(range) {
        return editor.getSelectionRange().intersects(range);
    }
    
    function preventReadonly(next, args) {
        if (intersects(range)) return;
        next();
    }

    $(function() {
        $("#submit").click(function(){ 
          var $this = $(this);
          $this.button('loading');
            $.ajax({
                type: 'POST',
                url: '{{action('HomeController@submit')}}',
                data: {"_token": "{{ csrf_token() }}", 'challenge' : '{{$challenge->id}}', 'code' : editor.getValue()},
                dataType: 'json',
                success: function(data) {
                  $this.button('reset');
                  if(data['message'] == 'fail') {
                    $("#body-content").html(data['output']);
                    $("#myModalTitle").html("Failed");
                  } else if (data['message'] == 'compilefail') {
                    $("#body-content").html("<pre>" + data['output'].substring(1) + "</pre>");
                    $("#myModalTitle").html("Compile failed");
                  } else if(data['message'] == 'success') {
                    $("#myModalTitle").html("Success");
                    $("#body-content").html("Successfully passed.");
                  }
                  $('#myModal').modal({
                    keyboard: false
                  })
                }
            });
            event.preventDefault();
        });
    });
</script>
@stop
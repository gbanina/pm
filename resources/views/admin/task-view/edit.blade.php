
@extends('base')

@section('content')

       <div class="">
            <div class="clearfix"></div>
            {!! Form::model($taskType, array('url' => TMBS::url('admin/task-view/' . 1), 'method' => 'PUT', 'class' => 'form-horizontal exit-alert form-label-left')) !!}
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="row">
              <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                      Edit <strong>
                        {!! Form::text('view_name', $taskType->name, array('id' => 'view_name','class' => 'form-control editable-title')) !!}
                      </strong>
                      <div class="header-buttons">
                        <a id="published_link" @if($taskType->status == 'IN_PROGRESS') style="display:none;" @endif
                          onClick="unpublish()" class="btn btn-dark btn-sm" type="button">Unpublish</a>
                        <a id="unpublished_link" @if($taskType->status == 'PUBLISHED') style="display:none;" @endif
                        onClick="publish()" class="btn btn-success btn-sm" type="button">Publish</a>

                        <input type="hidden" id="publish_hidden" name="publish_hidden" value="{{$taskType->status}}">
                      </div>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ WebComponents::backUrl() }}" class="btn btn-default" type="button">Cancel</a>
                        <a onClick="saveForm()" class="btn btn-primary btn-sm" type="button">Save</a>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content usable-fields">
                      <script>
                        var row_count = 0;
                        var formObj = [];
                      </script>
                    @foreach($fields as $rowId => $row)
                      <div id="row_{{$rowId}}" class="view view-first">
                        <div class="mask">
                            <div class="tools tools-bottom">
                                <a onclick="one_column_row({{$rowId}})"><i class="fa fa-reorder"></i></a>
                                <a onclick="two_column_row({{$rowId}})"><i class="fa fa-th-large"></i></a>
                                <a onclick="three_column_row({{$rowId}})"><i class="fa fa-th"></i></a>
                                <a onclick="delete_row({{$rowId}})"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div id="cols_{{$rowId}}">

                          @foreach($row as $colId => $col)
                            <div id="col_{{$colId}}_{{$rowId}}" class="col-md-{{(12 / count($row))}} col-sm-12 col-xs-12 form-group">
                              <ul id="drop_zone_{{$colId}}_{{$rowId}}" class="drag-n-drop-ul row_{{$rowId}}_list">
                                @foreach($col as $field)
                                    <li id="list-element-{{$field['field']->id}}">
                                      <div class="form-group">
                                        @if($field['field']->type != 'COMMENTS' && $field['field']->type != 'WORK')
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">{{$field['field']->label}}</label>
                                          <div class="col-md-8 col-sm-8 col-xs-12 possibly-hide">
                                            @component('component.additional-field', ['field' => $field['field'],
                                                                                      'id' => $field['field']->id,
                                                                                      'global_css' => '',
                                                                                      'users' => $users,
                                                                                      'usersO' => $usersO,
                                                                                      'status' => $status,
                                                                                      'priorities' => $priorities,
                                                                                      'types' => $types])
                                            @endcomponent
                                            <div class="checkbox">
                                              <label>
                                                <input type="checkbox" value="" class="additional-field-value-container" name="{{$field['field']->id}}"> Required?
                                              </label>
                                            </div>
                                          </div>
                                        @else
                                          <label class="control-label col-md-4 col-sm-4 col-xs-12 possibly-hide-label">{{$field['field']->label}}</label>
                                          <div class="col-md-12 col-sm-12 col-xs-12 possibly-hide">
                                            @component('component.additional-field', ['field' => $field['field'],
                                                'id' => $field['field']->id,
                                                'global_css' => '',
                                                'users' => $users,
                                                'usersO' => $usersO,
                                                'status' => $status,
                                                'priorities' => $priorities,
                                                'comments' => array(),
                                                'work' => array(),
                                                'types' => $types])
                                            @endcomponent
                                          </div>
                                        @endif
                                      </div>
                                    </li>
                                @endforeach
                              </ul>
                              <script>
                                  $( document ).ready(function() {
                                      init_drop_zone('{{$colId}}_{{$rowId}}');
                                  });
                              </script>
                            </div>
                          @endforeach
                        </div>
                      </div>
                      <script>
                        row_count = row_count + 1;
                        formObj[{{$rowId}}] = {{ count($row) }};
                      </script>
                    @endforeach

                  <div id="row_add">
                    <div id="row_new" class="col-md-12 col-sm-12 col-xs-12">
                      <ul id="add_zone" class="drag-n-drop-ul">
                      <li></li>
                      </ul>
                    </div>
                  </div>

                      </div>
                    </div>
                </div>
              <div id="possible_container" class="col-md-2 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Available fields</h2>
                    <div class="clearfix"></div>
                  </div>
                    <div class="x_content">

                    <ul id="possible" class="drag-n-drop-ul possible-list">
                    @foreach($taskFields as $field)
                                    <li id="list-element-{{$field->id}}">
                                      <div class="form-group">
                                        @if($field->type != 'COMMENTS' && $field->type != 'WORK')
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">{{$field->label}}</label>
                                          <div class="col-md-8 col-sm-8 col-xs-12 possibly-hide">
                                            @component('component.additional-field', ['field' => $field,
                                                                                      'id' => $field->id,
                                                                                      'global_css' => '',
                                                                                      'users' => $users,
                                                                                      'usersO' => $usersO,
                                                                                      'status' => $status,
                                                                                      'priorities' => $priorities,
                                                                                      'types' => $types])
                                            @endcomponent
                                            <div class="checkbox">
                                              <label>
                                                <input type="checkbox" value="" class="additional-field-value-container" name="{{$field->id}}"> Required?
                                              </label>
                                            </div>
                                          </div>
                                        @else
                                          <label class="control-label col-md-4 col-sm-4 col-xs-12 possibly-hide-label">{{$field->label}}</label>
                                          <div class="col-md-12 col-sm-12 col-xs-12 possibly-hide">
                                            @component('component.additional-field', ['field' => $field,
                                                'id' => $field->id,
                                                'global_css' => '',
                                                'users' => $users,
                                                'usersO' => $usersO,
                                                'status' => $status,
                                                'priorities' => $priorities,
                                                'comments' => array(),
                                                'work' => array(),
                                                'types' => $types])
                                            @endcomponent
                                           <div class="checkbox" style="display:none;">
                                              <label>
                                                <input type="checkbox" value="" class="additional-field-value-container" name="{{$field->id}}"> Required?
                                              </label>
                                            </div>
                                          </div>
                                        @endif
                                      </div>
                                    </li>
                    @endforeach
                    </ul>
                    </div>
                  </div>
                  </div>
            </div>
            {!! Form::close() !!}
            </div>
        </div>

@endsection

@section('js_include')
<script>

function unpublish()
{
      $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: "{{ TMBS::url('admin/task-view-unpublish/'. $viewId)}}",
            data: {data: {{$taskType->id}} },
            success: function( msg ) {
              if(msg == ''){
                $('#unpublished_link').css('display', 'inline-block');
                $('#published_link').css('display', 'none');
                $('#publish_hidden').val('IN_PROGRESS');
              }else {
                  new PNotify({
                    title: 'Error unpublishing view!',
                    text: 'This view is used on : ' + msg,
                    type: 'error',
                    styling: 'bootstrap3'
                });
              }
            }
        });
}
function publish()
{
    $('#unpublished_link').css('display', 'none');
    $('#published_link').css('display', 'inline-block');
    $('#publish_hidden').val('PUBLISHED');
}

function saveForm()
{
  var result = [];
    for ( var row = 1 ; row < formObj.length; row++ ){
      for( var col = 1; col <= formObj[row]; col++){
          $("#drop_zone_" + col + "_" + row ).children('li').each(function( index ) {
              var valueContainer = $(this).find('.additional-field-value-container');
              var id = valueContainer.attr( "name" );
              var required = valueContainer.is(':checked');
              result.push({ 'row' : row, 'col' : col, 'index' : index, 'id' : id, 'required' : required });
          });
      }
    }

if(Object.keys(result).length != 0){
    $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: "{{ TMBS::url('admin/task-view/'. $viewId)}}",
            data: {data: result, view_name: $("#view_name").val(), published: $("#publish_hidden").val()},
            success: function( msg ) {
              new PNotify({
                  title: 'Success',
                  text: 'Your view has been saved successfully!',
                  type: 'alert-success',
                  styling: 'bootstrap3'
              });
            }

        });
  }// if 0
  else{
    alert('You can not save empty form!')
  }
}

$(window).scroll(function() {
  var $container = $("#possible_container");
  var window_offset = $container.offset().top - $(window).scrollTop();
  $("#possible_container").css({ "top":($(window).scrollTop() + 0) +"px"});
});
var doubleClick = true;
 $( '#switchery_label' ).on( 'click', function() {
      if(doubleClick){
        if($("#form_published").val() == "off"){
          $("#form_published").val('on');
        }else{
          $("#form_published").val('off');
        }
        doubleClick = false;
      }else{
        doubleClick = true
      }
    });


</script>
  <script src="{{ URL::to('js/moment.min.js') }}"></script>
  <script src="{{ URL::to('js/daterangepicker.js') }}"></script>
  <script src="{{ URL::to('js/drag/Sortable.min.js')}}"></script>
  <script src="{{ URL::to('js/drag/app.js')}}"></script>
  <script src="{{ URL::to('js/switchery.min.js')}}"></script>


  <script>
    Sortable.create(document.getElementById('possible'), {
    group: "words",
    animation: 150,
    onAdd: function (evt){ console.log('onAdd.possible:', evt); },
    onUpdate: function (evt){ console.log('onUpdate.possible:', evt.item); },
    onRemove: function (evt){ console.log('onRemove.possible:', evt.item); },
    onStart:function(evt){ console.log('onStart.possible:', evt.item);},
    onEnd: function(evt){ console.log('onEnd.possible:', evt.item);}
  });
  </script>
@endsection

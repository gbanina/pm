
@extends('base')

@section('content')
<div class="col-md-6 col-sm-6 col-xs-12">
       <div class="">
            <div class="clearfix"></div>
            <div>
            <div class="row">
                <div class="x_panel">
                {!! Form::model($status, array('url' => TMBS::url('admin/status/' . $status->id), 'method' => 'PUT', 'class' => 'form-horizontal exit-alert form-label-left')) !!}
                  <div class="x_title">
                    <h2>Edit Status </h2>
                   <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ WebComponents::backUrl() }}" class="btn btn-default" type="button">Cancel</a>
                        {!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status-name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::text('status-name', $status->name, array('required' => 'required', 'class' => 'form-control col-md-7 col-xs-12')) !!}
                        </div>
                      </div>

                    </div>
                    {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

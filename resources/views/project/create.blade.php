
@extends('base')

@section('content')
            <div class="row">
              <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create New Project <small>Projects</small></h2>
                    <div class="clearfix"></div>
                  </div>

       <div class="">
                      {!! Form::open(array('url' => 'project', 'class' => 'form-horizontal form-label-left')) !!}
            <div class="clearfix"></div>


            <div class="row">
                <div class="x_panel">
                  <div class="x_content">
                    <br>


                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Project Name</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          {!! Form::text('project_name', '', array('required' => 'required', 'class' => 'form-control ','placeholder'=>'Project Name')) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Project Manager</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                        {{ Form::select('project_manager', $users, null, array('class' => 'form-control')) }}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Type</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          {{ Form::select('project_type', $projectTypes, null, array('class' => 'form-control', 'required')) }}
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Default Responsible</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            {{ Form::select('default_responsible', $users, null, array('class' => 'form-control', 'required')) }}
                        </div>
                      </div>
                  </div>
                </div>




            </div>


            <!-- end design1 -->



                  @if($errors->count() != 0):
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <strong>Holy guacamole!</strong> Best check yo self, you're not looking too good.
                                {!! var_dump($errors->count()) !!}
                            </div>
                        </div>
                  @endif
                  <div class="x_content">
                    <br>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <a href="{{ URL::to('project') }}" class="btn btn-primary" type="button">Cancel</a>
                          {!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
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

@section('js_include')
    <script src="{{ URL::to('js/moment.min.js') }}"></script>
    <script src="{{ URL::to('js/daterangepicker.js') }}"></script>
@endsection

@extends('backLayout.app')
@section('title')
Upload Contacts
@stop

@section('content')

        <div class="panel panel-default">
        <div class="panel-heading">Create user</div>

        <div class="panel-body">

        @if (count($errors) > 0)
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="alert alert-danger">
                            <strong>Upsss !</strong> There is an error...<br /><br />
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

{{ Form::open(array('url' => route('contact.store'), 'class' => 'form-horizontal','files' => true)) }}
    <ul>
        <div class="form-group col-md-6 {{ $errors->has('document') ? 'has-error' : ''}}">
             {!! Form::label('document', 'File', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::file('document', null, ['class' => 'form-control']) !!}
                {!! $errors->first('document', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-3">
                {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
            </div>
            <a href="{{route('contact.index')}}" class="btn btn-default">Return to all contacts</a>
        </div>
       

    </ul>
  
{{ Form::close() }}

  </div>
    </div>


@stop

@section('scripts')

@endsection
@extends('backLayout.app')
@section('title')
    Contacts
@stop

@section('content')
<div class="panel panel-default">
        <div class="panel-heading">Contacts</div>

        <div class="panel-body">

@if (Sentinel::getUser()->hasAccess(['service.create']))
<a href="{{route('service.create')}}" class="btn btn-success">New Service</a>
@endif
<table class="table table-bordered table-striped table-hover" id="tblUsers">
    <thead>
        <tr>

            <th>Select All <input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
            <th>ID</th>
            <th>Names</th>
            <th>Hits</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contacts as $contact)
            <tr>
                <td>{{ Form::checkbox('sel', $contact->id, null, ['class' => ''])}}</td>
                <td>{{$contact->id}}</td>
                <td>{{$contact->names}}</td>
                <td>{{$contact->hits}}</td>
                <td>{{$contact->created_at}}</td>
                <td>
                    @if (Sentinel::getUser()->hasAccess(['user.show']))
                    <a href="{{route('contact.show', $contact->id)}}" class="btn btn-success btn-xs">View</a>
                    @endif
                    @if (Sentinel::getUser()->hasAccess(['user.edit']))
                    <a href="{{route('contact.edit', $contact->id)}}" class="btn btn-success btn-xs">edit</a>
                    @endif


                    @if (Sentinel::getUser()->hasAccess(['contact.destroy']))
                    {!! Form::open(['method'=>'DELETE', 'route' => ['contact.destroy', $contact->id], 'style' => 'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs','id'=>'delete-confirm']) !!}
                    {!! Form::close() !!}
                    @endif

                    
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if (Sentinel::getUser()->hasAccess(['contact.destroy']))
<button id="delete_all" class='btn btn-danger btn-xs'>Delete Selected</button>
@endif
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        table = $('#tblUsers').DataTable({
            'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
            }],
          'order': [1, 'asc']
            });
    });
      // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
  $("input#delete-confirm").on("click", function(){
        return confirm("Are you sure to delete this service");
    });
  // start Delete All function
  $("#delete_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Delete Selected?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('UserController@ajax_all')}}",
                    data: {all_id:value,action:'delete'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
   });


   
   function get_Selected_id() {
    var searchIDs = $("input[name=sel]:checked").map(function(){
      return $(this).val();
    }).get();
    return searchIDs;
   }
</script>
@endsection

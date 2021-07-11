@extends('layouts.app')

@section('content')

<style>
  .push-top {
    margin-top: 50px;
  }
</style>

<div class="push-top">

<div id="js-message">
    
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
  
  <table class="table" id="js-marks">
    <thead>
        <tr class="table-warning">
          <td>SlNo</td>
          <td>Name</td>
          @foreach($subjects as $subject)
            <td>{{ $subject->name }}</td>
          @endforeach
          <td>Term</td>
          <td>Total Marks</td>
          <td>Created On</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->student }}</td>
                @foreach($item->marks as $key => $mark)
                    <td>{{ $mark }}</td>
                @endforeach
                <td>{{ $item->term }}</td>
                <td>{{ array_sum($item->marks) }}</td>
                <td>{{ $item->created_on }}</td>
                <td>
                    <a href='{{url("marks/edit", ['stdid'=>$item->student_id,'termid'=>$item->term_id])}}' class="btn btn-sm btn-info">Edit</a>
                    <a href='#' id="js-delete" data-stdid="{{$item->student_id}}" data-termid="{{$item->term_id}}" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
  </table>
<div>
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

<script>

$(document).on('click', '#js-delete', function(e){

    e.preventDefault();
    console.log('************************************************');//return;
    var stddId = $(this).attr('data-stdid');
    var termId = $(this).attr('data-termid');

    swal({
        title: "Are you sure do you want to delete ?",
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: !0
    }).then(function (e) {

        if (e.value === true) {
            $.ajax({
                url: '{{url("/marks/delete")}}',
                type: "POST",
                data : {stddId : stddId, termId: termId},
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                success: function(result) {
                    $("#js-message").attr("class", "");
                    if(result.status) {

                        $('#js-message').addClass("alert alert-success");
                        $('#js-message').html(result.message);

                    } else {

                        $('#js-message').addClass("alert alert-danger");
                        $('#js-message').html(result.message);
                    }
                    window.location.href = "{{ route('marklist')}}";
                    
                    $('#js-message').append('<button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.style.display=\'none\';">×</button>');

                },
                error : function(error) {
                    $('#js-message').addClass("alert alert-danger");
                    $('#js-message').html(result.message);
                    $('#js-message').append('<button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.style.display=\'none\';">×</button>');
                }
            });

        } else {
            e.dismiss;
        }

    }, function (dismiss) {
        return false;
    })
});

</script>
@endpush
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
  
  <table class="table" id="js-students">
    <thead>
        <tr class="table-warning">
          <td>SlNo</td>
          <td>Name</td>
          <td>Age</td>
          <td>Gender</td>
          <td>Reporting Teacher</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>
<div>
@endsection

@push('script')

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        $('#js-students').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                "url": '{{url("students-data")}}',
                "type": "GET",
            },
            columns: [
                {
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'name'},
                {data: 'age'},
                {data: 'gender',
                    render: function (data, type, row) {
                        if(data == 'M') {
                            return 'Male';
                        } else {
                            return 'Female';
                        }
                    }
                },
                {data: 'reporting_teacher'},
                {data: '',
                    render: function (data, type, row) {
                        return '<a href="edit?id=' + row.id + '">EDIT</a>&nbsp;&nbsp<a href="#" id="js-delete" data-id="'+ row.id +'">DELETE</a>'
                    }
                },
            ]
        });
    });

    $(document).on('click', '#js-delete', function(e){

        e.preventDefault();
        console.log($(this).attr('data-id'));
        var recordId = $(this).attr('data-id');

      swal({
            title: "Are you sure do you want to delete all of the data related to the student ?",
            text: "Please ensure and then confirm!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {

            if (e.value === true) {
                $.ajax({
            url: '{{url("/delete")}}',
            type: "POST",
            data : {id : recordId},
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
                // window.location.href = "dashboard.jsp";
                $('#js-students').DataTable().ajax.reload();

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
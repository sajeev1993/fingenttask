@extends('layouts.app')

@section('content')

<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }

    .disable{
        pointer-events:none;
        background:grey;
    }
    
</style>

<div class="card push-top">
  <div class="card-header">
    Edit Mark
  </div>

  <div class="card-body">

    <div id="js-message">
    
    </div>

      <form id="edit-marks">
          <div class="form-group">
              @csrf
              <label for="student">Student</label>
              <select class="form-control disable" name="student" required>
                <option value="">--Select--</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @if($markDetails[0]->student_id == $student->id) ? selected : '' @endif> {{ $student->name }} </option>
                @endforeach
              </select>
          </div>
          <div class="form-group">
              <label for="term">Term</label>
              <select class="form-control disable" name="term" id="js-term" required>
                <option value="">--Select--</option>
                @foreach($terms as $term)
                    <option value="{{ $term->id }}" @if($markDetails[0]->term_id == $term->id) ? selected : '' @endif > {{ $term->name }} </option>
                @endforeach
              </select>
          </div>
            *Only marks can be edited
          <div class="form-group" id="js-subject-marks">

            @foreach($markDetails as $markDetail)
                <label for="name">{{ $markDetail->name }}</label><input type="number" placeholder="Enter marks" class="form-control" value="{{ $markDetail->mark }}" name="subjectmark[{{ $markDetail->subject_id }}]" required/>
            @endforeach
          </div>
          
          <button type="submit" class="btn btn-block btn-danger">Update</button>
      </form>
  </div>
</div>
@endsection

@push('script')

<script>

    $(document).ready(function(){

        $("#edit-marks").validate({
            rules: {
                student: {
                    required: true
                },
                term: {
                    required: true
                }
            },
            messages: {
                student: {
                    required: "specify name"
                },
                term: {
                    required: "specify age"
                }
            },
            errorClass: "help-inline text-danger",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('has-error');
                $(element).parents('.form-group').addClass('has-success');
            },
            submitHandler: function(form,e) {
                e.preventDefault();
                console.log('Form submitted');
                var formData = new FormData($(form)[0]);
                console.log(formData);

                // return;
                $.ajax({
                    url: '{{url("marks/update")}}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    type:"POST",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function(result) {
                        $("#js-message").attr("class", "");
                        if(result.status) {

                            $('#js-message').addClass("alert alert-success");
                            $('#js-message').html(result.message);
                            $("#edit-marks")[0].reset();

                            window.location.href = "{{ route('marklist')}}";

                        } else {

                            $('#js-message').addClass("alert alert-danger");
                            $('#js-message').html(result.message);
                        }
                        // window.location.href = "dashboard.jsp";
                        $('#js-message').append('<button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.style.display=\'none\';">×</button>');
                    },
                    error : function(error) {
                        $('#js-message').addClass("alert alert-danger");
                        $('#js-message').html(result.message);
                        $('#js-message').append('<button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.style.display=\'none\';">×</button>');
                    }
                });
                
                return false;
            }
        });
    });

    // $(document).on('change', '#js-term', function(e){
    //     e.preventDefault();
    //     var termId = $(this).val();
    //     var html = '';
    //     $('#js-subject-marks').html('');

    //     $.ajax({
    //         url: '{{url("marks/term-subjects")}}',
    //         type: "GET",
    //         data : {termid : termId},
    //         success: function(result) {
    //             $("#js-message").attr("class", "");
    //             if(result.status) {

    //                 // $('#js-message').addClass("alert alert-success");
    //                 // $('#js-message').html(result.message);

    //                 // console.log(result.data.length,'//////////////////////');

    //                 $.each(result.data, function(key,val) {             
    //                     console.log(val.subject_name);
    //                     html += '<label for="name">' + val.subject_name + '</label><input type="number" placeholder="Enter marks" class="form-control" name="subjectmark[' + val.subject_id + ']" required/>'         
    //                 }); 

    //                 $('#js-subject-marks').append(html);

    //             } else {

    //                 $('#js-message').addClass("alert alert-danger");
    //                 $('#js-message').html(result.message);
    //             }
    //                     // window.location.href = "dashboard.jsp";
    //             $('#js-message').append('<button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.style.display=\'none\';">×</button>');
    //         },
    //         error : function(error) {
                
    //         }
    //     });

    // });

</script>

@endpush
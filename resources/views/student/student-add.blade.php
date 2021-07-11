@extends('layouts.app')

@section('content')

<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }
    
</style>

<div class="card push-top">
  <div class="card-header">
    Add Student
  </div>

  <div class="card-body">

    <div id="js-message">
    
    </div>

      <form id="add-student">
          <div class="form-group">
              @csrf
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" required/>
          </div>
          <div class="form-group">
              <label for="age">Age</label>
              <input type="number" class="form-control" name="age" required/>
          </div>
          <div class="form-group">
            <label for="gender">Gender</label>
            <input type="radio" name="gender" value="M">Male
            <input type="radio" name="gender" value="F">Female
          </div>
          <div class="form-group">
              <label for="reporting_teacher">Reporting Teacher</label>
              <select class="form-control" name="reporting_teacher" required>
                <option value="">--Select--</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"> {{ $teacher->name }} </option>
                @endforeach
              </select>
          </div>
          <button type="submit" class="btn btn-block btn-danger">Save</button>
      </form>
  </div>
</div>
@endsection

@push('script')

<script>

    $(document).ready(function(){

        $("#add-student").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
                age: {
                    required: true,
                    number: true,
                    min: 0
                },
                gender: {
                    required: true
                },
                reporting_teacher: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "specify name",
                    maxlength: "maximum length exceeded"
                },
                age: {
                    required: "specify age",
                    number: "enter a number",
                    min: "enter a valid number"
                },
                gender: {
                    required: "specify gender"
                },
                reporting_teacher: {
                    required: "select reporting teacher"
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
                    url: '{{url("save")}}',
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
                            $("#add-student")[0].reset()

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

</script>

@endpush
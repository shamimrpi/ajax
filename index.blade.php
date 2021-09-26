@extends('layouts.admin')
@section('title', 'DataTable')
@section('admin-content')
<main>
   <div class="container ">
    <div class="heading-title p-2 my-2">
        <span class="my-3 heading "><i class="fas fa-home"></i> <a class="" href="{{route('admin.index')}}">Home</a> >Customer Create</span>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-user-plus"></i>
            customer form
        </div>
        <div class="card-body table-card-body p-3 mytable-body">
            
            <form id="customer_form" action="{{route('customer.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="method_type" value="post">
                <div class="row">
                     <div class="col-md-6">
                         <div class="row">
                            <div class="col-md-4">
                                <label> Name </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                            <div class="col-md-7">
                                {{-- @error('name') is-invalid @enderror --}}
                                 <input type="text" name="name"  value="{{old('name')}}" class="form-control my-form-control "  id="name">
                                 
                                 <strong><span class="text-danger" id="nameError"></span></strong>
                               
                                 
                             </div> 
                             <div class="col-md-4">
                                <label>Email </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                             <div class="col-md-7">
                                <input type="email" name="email" value="{{old('email')}}" id="email" class="form-control my-form-control  @error('email') is-invalid @enderror" >
                                <strong><span class="text-danger" id="emailError"></span></strong>
                             </div> 
                             <div class="col-md-4">
                                <label> Phone </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                             <div class="col-md-7">
                                <input type="text" name="phone" value="{{old('phone')}}" id="phone" class="form-control my-form-control  @error('phone') is-invalid @enderror" >
                                <strong><span class="text-danger" id="phoneError"></span></strong>
                             </div> 
                             <div class="col-md-4">
                                <label>Address </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                             <div class="col-md-7">
                               <textarea name="address" rows="2" id="address" class="form-control @error('phone') is-invalid @enderror"></textarea>
                               <strong><span class="text-danger" id="addressError"></span> </strong>
                             </div> 
                             <div class="col-md-4">
                                <label>Area </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                             <div class="col-md-7">
                                
                                <select class="js-example-basic-multiple form-control my-form-control  @error('area_id') is-invalid @enderror" id="area_id" data-live-search="true" name="area_id">
                                    <option  data-tokens="ketchup mustard">Select Area</option>
                                    <option value="1">Inside Dhaka</option>
                                    <option value="2">Outside Dhaka</option>
                                    
                                </select>
                                <strong><span class="text-danger" id="areaidError"></span></strong>
                             </div> 
                         </div>
                     </div>
                     <div class="col-md-6">
                        <div class="row right-row">
                           <div class="col-md-4">
                               <label>Username </label>
                           </div>
                           <div class="col-md-1 text-right">
                                <span class="clone">:</span>
                            </div>
                            <div class="col-md-7">
                            <input type="text" name="username" id="username" value="{{old('username')}}" autocomplete="off" class="form-control my-form-control  @error('username') is-invalid @enderror" >
                            <strong><span class="text-danger" id="usernameError"></span></strong>
                            </div> 
                            <div class="col-md-4">
                                <label>Password </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                             <div class="col-md-7">
                                <input type="password" id="password" name="password" value="{{old('password')}}"  class="form-control my-form-control  @error('password') is-invalid @enderror" autocomplete="off">
                                <strong><span class="text-danger" id="passwordError"></span></strong>
                             </div>
                             <div class="col-md-4">
                                <label>Profile Image </label>
                            </div>
                            <div class="col-md-1 text-right">
                                 <span class="clone">:</span>
                             </div>
                             <div class="col-md-5">
                                <input type="file" class="form-control my-form-control  @error('image') is-invalid @enderror" id="image" name="profile_picture" onchange="readURL(this);">
                                <strong><span class="text-danger" id="imageError"></span></strong>
                            </div>
                            <div class="col-md-2 ps-0">
                                <img class="form-controlo img-thumbnail w-100" src="#" id="previewImage" style="height:80px; background: #3f4a49;">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" id="submit-btn" class="btn btn-primary btn-sm mt-2 float-right submit-btn" value="Submit">Submit</button>
                            </div>
                        </div>
                    </div>  
                </div>
            </form>
        </div>
   </div>
        <div class="row">
            <div class="col-12">
               <div class="card"> 
                <div class="card-header">
                    <div class="table-head"><i class="fas fa-table me-1"></i>Customer List</div>
                </div>
                <div class="card-body table-card-body p-3">
                    <table id="datatablesSimple">
                        <thead class="text-center bg-light">
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Customer ID</th>
                                <th>Status</th>
                                <th>Username</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="customer_body">
                            @foreach ($customers as $key=> $customer)
                            <tr  class="text-center">
                                <td>{{$key+1}}</td>
                                <td>{{$customer->name}}</td>
                                <td>{{$customer->email}}</td>
                                <td>{{$customer->code}}</td>
                                <td>@if($customer->status == 'a') Active @else Inactive @endif</td>
                                <td>{{$customer->username}}</td>
                                <td>{{$customer->phone}}</td>
                                <td class="text-center">
                                   
                                    <a href="{{route('customer.edit',$customer->id)}}" class="btn-edit edit-btn"><i class="fas fa-pencil-alt"></i></a>
                                    <button type="submit" class="btn btn-delete" onclick="deleteUser({{ $customer->id }})"><i class="far fa-trash-alt"></i></button>
                                        <form id="delete-form-{{ $customer->id }}" action="{{ route('customer.destroy', $customer) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                </td>
                            </tr>  
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</main>        
@endsection
@push('admin-js')
<script src="{{ asset('admin/js/ckeditor.js') }}"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.log( error );
        } );
</script>
<script> 
function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload=function(e) {
                $('#previewImage')
                    .attr('src', e.target.result)
                    .width(100);
                   
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    document.getElementById("previewImage").src="/noimage.png";
    
</script> 
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    $(document).on('submit', '#customer_form', function(e){
        e.preventDefault();
        // var name = $('#name').val();
        // var email = $('#email').val();
        // var phone = $('#phone').val();
        // var address = $('#address').val();
        // var area_id = $('#area_id').val();
        // var username = $('#username').val();
        // var password = $('#password').val();
        // var profile_picture =  $('#profile_picture').val();
        // let formData = $(this).serialize();
        let formData = new FormData(this);
       
        let url = $(this).attr('action');
        let method_type = $('#method_type').val();
        console.log(formData);
        $.ajax({
            url,
            type: method_type,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success:function(res){
                if(res.success){
                    $('#customer_body').html(res.data.data_table);
                    $('#customer_form').trigger('reset');
                }
            },
            error: function (data) {
                $('#nameError').text(data.responseJSON.errors.name);
                if(data.responseJSON.errors.name){
                    $('#name').addClass('is-invalid');
                }
                $('#emailError').text(data.responseJSON.errors.email);
                if(data.responseJSON.errors.email){
                    $('#email').addClass('is-invalid');
                }
                $('#phoneError').text(data.responseJSON.errors.phone);
                if(data.responseJSON.errors.phone){
                    $('#phone').addClass('is-invalid');
                }
                $('#addressError').text(data.responseJSON.errors.address);
                if(data.responseJSON.errors.address){
                    $('#address').addClass('is-invalid');
                }
                $('#areaidError').text(data.responseJSON.errors.area_id);
                if(data.responseJSON.errors.area_id){
                    $('#area_id').addClass('is-invalid');
                }
                $('#usernameError').text(data.responseJSON.errors.username);
                if(data.responseJSON.errors.username){
                    $('#username').addClass('is-invalid');
                }
                $('#passwordError').text(data.responseJSON.errors.password);
                if(data.responseJSON.errors.password){
                    $('#password').addClass('is-invalid');
                }
                $('#imageError').text(data.responseJSON.errors.profile_picture);
                if(data.responseJSON.errors.profile_picture){
                    $('#profile_picture').addClass('is-invalid');
                }
                
            }
            
        })
    });

    $(document).on('click', '.edit-btn', function(e){
        e.preventDefault();

        let url = $(this).attr('href');
        $.ajax({
            method: 'get',
            url,
            success: function(res){
                if(res.success){
                    let customer = res.customer;
                    $('#name').val(customer.name);
                    $('#email').val(customer.email);
                    $('#phone').val(customer.phone);
                    $('#address').val(customer.address);
                    $('#username').val(customer.username);
                    $("#area_id").val(customer.area_id).change();
                    $('#previewImage').attr('src', customer.profile_picture);
                    $('#customer_form').attr('action', res.url);
                }
            }

        });
    })
   
            setTimeout(function(){
                $("div.alert").fadeOut();
                }, 3000 ); // 5 secs




</script>
<script src="{{ asset('admin/js/sweetalert2.all.js') }}"></script>
<script>
    function deleteUser(id) {
            swal({
                title: 'Are you sure?',
                text: "You want to Delete this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-' + id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
</script>
@endpush
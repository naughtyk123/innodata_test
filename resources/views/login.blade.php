@include('layouts.header')
    <style>
       .images{
            height: 259px;
            overflow-y:scroll;
       }
    </style>

    <style>
       .maindiv{

        display: flex;
        justify-content: center;
        }
    </style>
    <body class="antialiased">
        <!-- check model -->
        <div class="modal fade" id="checkmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image Check</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div class="row images">
                        
                    </div>
             
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>
        <!-- end -->


        @include('layouts.navbar')

        <div class="container mt-3 maindiv">
    
            <div class="col-md-6 ">
               
                <div class="card">
                    <div class="card-body">
                        <h1>Admin Login</h1>
                      <label>Email</label>
                      <input type="text" id="email" class="form-control">
                      <label>Password</label>
                      <input type="password" id="password" class="form-control">
                      <input type="button" onclick="login()" value="Login" class="btn mt-2 btn-success form-control">


                      
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
function login(id)
        {
            $.ajax({
            type:'POST',
            url:'/login',
            data: {
                "_token": "{{ csrf_token() }}",
                "email":$('#email').val(),
                "password":$('#password').val()

            },
            success:function(data) {

              if(data.status=="true"){
               location.reload();
              }else{

                $('#approve'+id).html('<button type="button" class="btn btn-success" onclick="show_images('+id+')" data-toggle="modal" data-target="#checkmodel">Check Images</button>')

              }

            }
         
        });

        }

</script>
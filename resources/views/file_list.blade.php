@include('layouts.header')
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
       
        <div class="container mt-3">

        <div class="row">
        <div class="col-md-6">
        </div>
    </div>
         <input type="hidden" id="path" value="{{storage_path('app/admin_images/')}}">
            <div class="col-md-12">
                <div class="" id="testdiv" style="width:100%">
                </div>
                <div class="card">
                    <div class="card-body">

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>User</th>
                                    <th>Check</th>
                                    <th>Action</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $loc)
                                <tr>
                                    <td>{{$loc->id}}</td>
                                    <td>{{$loc->location_name}}</td>
                                    <td>{{$loc->category_name->cat_name}}</td>
                                    <td>{{$loc->user_name_get->name}}</td>
                                    @if($loc->extract!=1)
                                    <td id="extract{{$loc->id}}" ><button type="button" class="btn btn-primary" onclick="action({{$loc->id}})">Extract</button></td>
                                    @else
                                    <td id="extract{{$loc->id}}" ><button type="button" class="btn btn-danger" onclick="remove_file({{$loc->id}})">Remove File</button></td>
                                    @endif
                                    @if($loc->status!=1)
                                    <td id="approve{{$loc->id}}"><button type="button" class="btn btn-success" onclick="show_images({{$loc->id}})" data-toggle="modal" data-target="#checkmodel">Check Images</button></td>
                                    @else
                                    <td id="approve{{$loc->id}}"><button type="button" class="btn btn-success" >Approved</button></td>

                                    @endif
                                </tr>

                                

                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                        {{ $locations->links('pagination::bootstrap-4') }}
                        </div>
                   
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>

    function action(id){

        $.ajax({
            type:'GET',
            url:'/get_images',
            data: {
                "id":id,
            },
            success:function(data) {

              if(data.status=="true"){

                $('#extract'+id).html('<button type="button" class="btn btn-danger" onclick="remove_file('+id+')" data-toggle="modal" data-target="#checkmodel">Remove File</button>')

              }

            }
         
        });

    }
    
    function remove_file(id){

        $.ajax({
            type:'GET',
            url:'/remove_file',
            data: {
                "id":id,
            },
            success:function(data) {

              if(data.status=="true"){

                $('#extract'+id).html('<button type="button" class="btn btn-primary" onclick="action('+id+')" >Extract</button>');

              }

            }
         
        });

    }

function show_images(id){
$.ajax({
            type:'GET',
            url:'/show_images',
            data: {
                "id":id,
            },
            success:function(data) {

              if(data.status=="true"){
                $('.images').html(data.result);
                $('.modal-footer').html('<button type="button" class="btn btn-danger" onclick="approve('+id+')" >Approve</button>')

              }

            }
         
        });
  
}
function approve(id)
        {
            $.ajax({
            type:'GET',
            url:'/approve',
            data: {
                "id":id,
            },
            success:function(data) {

              if(data.status=="true"){
                $('.images').html(data.result);
                // $('#checkmodel').hide();
                $('#checkmodel').modal('toggle');
                $('#approve'+id).html('<button type="button" class="btn btn-success"  data-target="#checkmodel">Approved</button>')

              }else{

                $('#approve'+id).html('<button type="button" class="btn btn-success" onclick="show_images('+id+')" data-toggle="modal" data-target="#checkmodel">Check Images</button>')

              }

            }
         
        });

        }

</script>
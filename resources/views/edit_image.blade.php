@include('layouts.header')
    <style>
       .images{
            height: 259px;
            overflow-y:scroll;
       }
       .sfx-canvas{
        z-index: 5;
        position: absolute;
        width: unset!important;
        height: 600px!important;
        top: unset!important;
        left: unset!important;
       }
       .imagecard{
           cursor: pointer;
           transition: transform .2s;

       }
       .imagecard:hover{
        transform: scale(0.8);
        -webkit-box-shadow: 1px 5px 15px 5px #A8A3FF; 
        box-shadow: 1px 5px 15px 5px #A8A3FF;
       
           
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
                                    <th>Select Image</th>
                                    <!-- <th>Action</th> -->


                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $loc)
                                <tr>
                                    <td>{{$loc->id}}</td>
                                    <td>{{$loc->location_name}}</td>
                                    <td>{{$loc->category_name->cat_name}}</td>
                                    <td>{{$loc->user_name_get->name}}</td>
                                    <td id="approve{{$loc->id}}"><button type="button" class="btn btn-success" onclick="show_images({{$loc->id}})" data-toggle="modal" data-target="#checkmodel">Select Images to Draw </button></td>
                                    <!-- <td ><a type="button" class="btn btn-success" href="{{url('imagefiles',$loc->id)}}" data-toggle="modal" data-target="#checkmodel">Edit</a></td> -->

                                </tr> 
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                        {{ $locations->links('pagination::bootstrap-4') }}
                        </div>

                        <div id="drawr-container" style="width:100%;height:600px;">
                        <canvas  class="demo-canvas drawr-test1" style="back"></canvas>
                        </div>
                        <input type="file" id="file-picker" style="display:none;">
                   
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


        $("#drawr-container .demo-canvas").drawr({
		"enable_tranparency" : true
	});

	$(".demo-canvas").drawr("start");
	
	//add custom save button.
	var buttoncollection = $("#drawr-container .demo-canvas").drawr("button", {
		"icon":"mdi mdi-folder-open mdi-24px"
	}).on("touchstart mousedown",function(){
		
		$("#file-picker").click();
	});
	var buttoncollection = $("#drawr-container .demo-canvas").drawr("button", {
		"icon":"mdi mdi-content-save mdi-24px"
	}).on("touchstart mousedown",function(){
		var imagedata = $("#drawr-container .demo-canvas").drawr("export","image/png");
		var element = document.createElement('a');
		element.setAttribute('href', imagedata);
		element.setAttribute('download', "test.png");
		element.style.display = 'none';
		document.body.appendChild(element);
		element.click();
		document.body.removeChild(element);
	});
	$("#file-picker")[0].onchange = function(){
		var file = $("#file-picker")[0].files[0];
		if (!file.type.startsWith('image/')){ return }
		var reader = new FileReader();
		reader.onload = function(e) { 
			$("#drawr-container .demo-canvas").drawr("load",e.target.result);
		};
		reader.readAsDataURL(file);
	};
    function edit(id)
    {
        
        var path=$('#image_card'+id).attr('src');
        $("#drawr-container .demo-canvas").drawr("load",'');
        $("#drawr-container .demo-canvas").drawr("load",path);
    }

</script>
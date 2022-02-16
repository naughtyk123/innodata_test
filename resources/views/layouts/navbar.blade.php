<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          
          <li class="nav-item">
            <a class="nav-link  @if(Request::is('file-list')) active @endif" href="{{url('file-list')}}">File List</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(Request::is('edit_images')) active @endif" href="{{url('edit_images')}}">Approved File List</a>
          </li>
          @if(Session::has('user_id'))
          <li class="nav-item">
            <a class="nav-link" href="{{url('logout')}}">Log Out</a>
          </li>
          @endif
        </ul>
    
      </div>
    </div>
  </nav>
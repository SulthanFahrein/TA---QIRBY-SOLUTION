<nav class="main-header navbar navbar-expand navbar-white navbar-light " style="background-color: #00A5A7">
  <link rel="stylesheet" href="{{ asset('assets/css/navbar.css')}}" />

  <ul class="navbar-nav ">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"
          style="color: white"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">


    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt" style="color: white"></i>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <button style="background-color: white; color: black; border-radius: 20px; border: none">
          <i class="nav-icon fa fa-user" style="padding-right: 7px;" aria-hidden="true"></i>
          {{ auth()->user()->name }}
        </button>

      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-divider"></div>
        <div>
          <a href="/admin" class="dropdown-item sidebar-item nav-link" style="color: black">
            <div class="dropdown-divider"></div>
            <i class="fas fa-cog"></i>
            <span style="padding-left: 10px; color: black">Settings</span>
          </a>
        </div>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal" style="color: black">
          <div class="dropdown-divider"></div>
          <i class="fas fa-sign-out-alt"></i>
          <span style="padding-left: 10px; color: black">Logout</span>
        </a>
      </div>
      <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to logout?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <a href="/" class="btn btn-primary">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </li>
  </ul>
</nav>
</nav>
<script>
  $(document).ready(function () {
    $('#logoutModal').on('hidden.bs.modal', function (e) {
      $(this).find('form')[0].reset(); // Reset form inside the modal
    });
  });
</script>
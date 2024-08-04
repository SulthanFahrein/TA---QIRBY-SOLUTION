<style>
  .sidebar-item.active {
    background-color: #00A5A7 !important;
    border-radius: 10px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    function activateItem() {
      const sidebarItems = document.querySelectorAll('.sidebar-item');
      const currentActiveItemKey = localStorage.getItem('activeSidebarItem');

      sidebarItems.forEach(item => {
        item.classList.remove('active');
        if (item.dataset.key === currentActiveItemKey) {
          item.classList.add('active');
        }

        item.addEventListener('click', function () {
          localStorage.setItem('activeSidebarItem', item.dataset.key);
          sidebarItems.forEach(i => i.classList.remove('active'));
          item.classList.add('active');
        });
      });
    }

    activateItem();
  });

</script>


<div class="sidebar">
  <!-- Sidebar user (optional) -->
  <div class=" mt-3 pb-3 mb-3 d-flex ">
    <div class="image">
      <img src="{{asset('template/dist/img/logo.png')}}" class="elevation-3" alt="User Image"
        style="width: 220px; height: 100px">
    </div>
  </div>
  <br>

  <nav class="mt-2 position-fixed">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <div>
          <a href="/home" class="sidebar-item nav-link" data-key="dashboard">
            <i class="fa fa-home nav-icon" aria-hidden="true"></i>
            <p>Dashboard</p>
          </a>
        </div>
      </li>

      <li class="nav-item">
        <div>
          <a href="/property" class="sidebar-item nav-link" data-key="property">
            <i class="nav-icon fas fa-edit" aria-hidden="true"></i>
            <p>Property Management</p>
          </a>
        </div>
      </li>

      <li class="nav-item">
        <div>
          <a href="/schedule" class="sidebar-item nav-link" data-key="schedule">
            <i class="nav-icon far fa-calendar-alt" aria-hidden="true"></i>
            <p>Schedule</p>
          </a>
        </div>
      </li>

      <li class="nav-item">
        <div>
          <a href="/data-user" class="sidebar-item nav-link" data-key="data-user">
            <i class="nav-icon fa fa-user-circle" aria-hidden="true"></i>
            <p>Data User</p>
          </a>
        </div>
      </li>
    </ul>
  </nav>
</div>
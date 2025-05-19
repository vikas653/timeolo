<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="index.html" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <!-- <img src="{{ asset('assets/images/logo/logo-dark.png') }}" alt="logo image" class="" /> -->
        <!-- <span class="badge bg-brand-color-2 rounded-pill ms-2 theme-version">v1.0</span> -->
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>
        @if(auth()->user()->role_id == 1)
        <li class="pc-item">
          <a href="{{ route('dashboard') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-gauge"></i>
            </span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('invoice') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-receipt"></i> 
            </span>
            <span class="pc-mtext">Manage Invoice</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('bill') }}" class="pc-link">
            <span class="pc-micon">
              {{-- <i class="ph-duotone ph-wallet"></i> --}}
              <i class="ph-duotone ph-currency-dollar"></i>
 
            </span>
            <span class="pc-mtext">Manage Bill</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{route('project_asign_report')}}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-flow-arrow"></i>

 
            </span>
            <span class="pc-mtext">Manage Project Asign</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('admin') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-shield-check"></i>
            </span>
            <span class="pc-mtext">Manage Admin</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('clients') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-identification-card"></i>
            </span>
            <span class="pc-mtext">Manage Clients</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('project') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-briefcase"></i>

            </span>
            <span class="pc-mtext">Manage Projects</span>
          </a>
        </li>
        {{-- <li class="pc-item">
          <a href="{{ route('asign_project') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-user-list"></i> 

            </span>
            <span class="pc-mtext">Manage Assign Projects</span>
          </a>
        </li> --}}

        <li class="pc-item">
          <a href="{{ route('employment') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-users-three"></i> <!-- Users Icon for Employment Management -->
            </span>
            <span class="pc-mtext">Manage Employments</span>
          </a>
        </li>
        
        <li class="pc-item">
          <a href="{{ route('system_access') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-lock-key"></i> <!-- Lock and Key Icon for System Access -->
            </span>
            <span class="pc-mtext">Manage System Access</span>
          </a>
        </li>
        
        <li class="pc-item">
          <a href="{{ route('terms') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-file-text"></i> <!-- Document Icon for Terms Management -->
            </span>
            <span class="pc-mtext">Manage Terms</span>
          </a>
        </li>
        
        <li class="pc-item">
          <a href="{{ route('users') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-user-circle"></i>
            </span>
            <span class="pc-mtext">Manage Users</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('expenses_status') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-storefront"></i>
            </span>
            <span class="pc-mtext">Manage Expenses Status</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('timesheet') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-clock"></i>
            </span>
            <span class="pc-mtext">Manage Timesheet</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('timesheet_status') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-calendar-check"></i>

            </span>
            <span class="pc-mtext">Manage Timesheet Status</span>
          </a>
        </li>
        {{-- <li class="pc-item">
          <a href="{{ route('invoice') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-clock"></i>
            </span>
            <span class="pc-mtext">Manage Invoice</span>
          </a>
        </li> --}}
        @else
        <li class="pc-item">
          <a href="{{ route('clients') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-identification-card"></i>
            </span>
            <span class="pc-mtext">Manage Clients</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('timesheet') }}" class="pc-link">
            <span class="pc-micon">
            <i class="ph-duotone ph-clock"></i>
            </span>
            <span class="pc-mtext">Manage Timesheet</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('user_project') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-briefcase"></i>

            </span>
            <span class="pc-mtext">Manage Projects</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('user_edit', ['id' => auth()->id()]) }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-user-circle"></i>
            </span>
            <span class="pc-mtext">Manage User</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('vendor_edit')}}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-storefront"></i>
            </span>
            <span class="pc-mtext">Manage Vendor</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('expenses') }}" class="pc-link">
            <span class="pc-micon">
              <i class="ph-duotone ph-storefront"></i>
            </span>
            <span class="pc-mtext">Manage Expenses</span>
          </a>
        </li>
        @endif
      </ul>
      
    </div>
    <div class="card pc-user-card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <img src="{{ url('public/assets/images/user/avatar-1.jpg')}}" alt="user-image" class="user-avtar wid-45 rounded-circle" />
          </div>
          <div class="flex-grow-1 ms-3 me-2">
            <h6 class="mb-0">
              @if(auth()->user()->name)
              {{ auth()->user()->name }}
              @else
              {{ 'Not Login' }}
              @endif
            </h6>
            <small>
              {{ auth()->user()->role_id == 1 ? 'Admin' : 'User' }}
            </small>
          </div>
          <div class="dropdown">
            <a
              href="#"
              class="btn btn-icon btn-link-secondary avtar arrow-none dropdown-toggle"
              data-bs-toggle="dropdown"
              aria-expanded="false"
              data-bs-offset="0,20"
            >
              <i class="ph-duotone ph-windows-logo"></i>
            </a>
            <div class="dropdown-menu">
              <ul>
                <!-- <li
                  ><a class="pc-user-links">
                    <i class="ph-duotone ph-user"></i>
                    <span>My Account</span>
                  </a></li
                >
                <li
                  ><a class="pc-user-links">
                    <i class="ph-duotone ph-gear"></i>
                    <span>Settings</span>
                  </a></li
                >
                <li
                  ><a class="pc-user-links">
                    <i class="ph-duotone ph-lock-key"></i>
                    <span>Lock Screen</span>
                  </a></li
                > -->
                <li
                  ><a href="{{ route('logout') }}" class="pc-user-links">
                    <i class="ph-duotone ph-power"></i>
                    <span>Logout</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>
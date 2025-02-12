@php($user = auth()->user())
@php($role = $user->role)
@php(list($outpages,$inpages) = getCustomPages('both'))
      <div class="topbar">
          <!-- LOGO -->
          <div class="brand">
              <a class='logo' href="{{ route('dashboard') }}">
                  <span>
                      <img src="{{ asset(setting('software_logo',1)) }}" alt="logo-small" class="logo-sm">
                  </span>
                  <span>
                      <!-- <h4 class="logo-lg">{{ setting('software_name',1) ?? 'Instant Fence Price' }}</h4> -->
                  </span>
              </a>
          </div>
          <nav class="navbar-custom">
              <ul class="list-unstyled topbar-nav float-end mb-0">
                  <li class="dropdown">
                      <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#" role="button"
                          aria-haspopup="false" aria-expanded="false">
                          <div class="d-flex align-items-center">
                              <div class="user-name">
                                  <small class="d-none d-lg-block font-11">{{$role == 0 ? 'User' : 'Admin'}}</small>
                                  <span class="d-none d-lg-block fw-semibold font-12">{{$user->name}} <i
                                          class="mdi mdi-chevron-down"></i></span>
                              </div>
                          </div>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <a class="dropdown-item" href="{{ route('profile') }}"><i class="ti ti-user font-16 me-1 align-text-bottom"></i>
                              Profile</a>
                          <div class="dropdown-divider mb-0"></div>
                          <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="dripicons-exit text-muted mr-2"></i> Logout</a>
                      </div>
                  </li>
                  <!--end topbar-profile-->
                  <li class="menu-item">
                      <!-- Mobile menu toggle-->
                      <a class="navbar-toggle nav-link" id="mobileToggle" onclick="toggleMenu()" onclick="toggleMenu()">
                          <div class="lines">
                              <span></span>
                              <span></span>
                              <span></span>
                          </div>
                      </a><!-- End mobile menu toggle-->
                  </li>
                  <!--end menu item-->
              </ul>
              <!--end topbar-nav-->

              <div class="navbar-custom-menu">
                  <div id="navigation">
                      <!-- Navigation Menu-->
                      <ul class="navigation-menu">
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('dashboard') }}" id="navbarDashboards">
                                  <span>Dashboards</span>
                              </a>
                          </li>
                          @if($role == 0)
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('profile') }}" id="navbarDashboards">
                                  <span>Profile Setting</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('category.list') }}" id="navbarDashboards">
                                  <span>Fence Types</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('ft_available.list') }}" id="navbarDashboards">
                                  <span>Height Available</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('fence.list') }}" id="navbarDashboards">
                                  <span>Fence Styles/Pricing</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('estimator.index') }}" id="navbarDashboards">
                                  <span>Estimator Survey</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('estimate.index') }}" id="navbarDashboards">
                                  <span>Estimates</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarUI_Kit" data-bs-toggle="dropdown"
                                  aria-haspopup="true" aria-expanded="false">
                                  <span><i class="ti ti-planet menu-icon"></i>More Menu Items</span>
                              </a>
                              <ul class="dropdown-menu animate slideIn" aria-labelledby="navbarUI_Kit">
                                  @foreach($outpages as $page)
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item " href="{{ route('custom-pages.visit', $page->id) }}" id="navbarDashboards">
                                          <span>{{ $page->name }}</span>
                                      </a>
                                  </li>
                                  @endforeach
                              </ul>
                          </li>
                          @else
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('user.list') }}" id="navbarDashboards">
                                  <span>Users</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('user.pending') }}" id="navbarDashboards">
                                  <span>Pending Approvals</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('extra-page.term') }}" id="navbarDashboards">
                                  <span>Terms of Service</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('fence-ft.list') }}" id="navbarDashboards">
                                  <span>Fence Ft. Report</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('custom-pages.list') }}" id="navbarDashboards">
                                  <span>Custom Pages</span>
                              </a>
                          </li>
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('product.list') }}" id="navbarDashboards">
                                  <span>Products</span>
                              </a>
                          </li>
                          @endif
                          <li class="nav-item dropdown parent-menu-item">
                              <a class="nav-link" href="{{ route('setting.index') }}" id="navbarDashboards">
                                  <span>Settings</span>
                              </a>
                          </li>
                          @if (session('super_admin') && !empty(session('super_admin')) && $role == 0)
                          <a class="btn btn-primary btn-sm " data-title="Back to Super Admin" href="{{ route('backadmin') }}">Back to
                              Admin</a>
                      @endif
                      </ul>
                  </div>
              </div>
          </nav>
      </div>

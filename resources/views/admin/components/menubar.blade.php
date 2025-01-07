<style>
    .top-menu-list{
        /* list-style:none; */
    }
    .top-menu-item{
        list-style:none;
        padding:10px;
    }
    .top-menu-link p, .top-menu-link i{
         font-size:13px;
    }
</style>
<div>
    <ul class="d-flex top-menu-list" style="">
        @php
        $inpages = \App\Models\CustomPage::where('is_active', 1)->where('is_dd_item',1)->get();
        $outpages = \App\Models\CustomPage::where('is_active', 1)->where('is_dd_item',0)->get();

        @endphp
        <li class="top-menu-item text-center text-center">
            <a href="{{ route('dashboard') }}" class="top-menu-link ">
                <i data-feather="home" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p>Dashboard</p>
            </a>
        </li>
        @if (auth()->user()->role == 0)
        <li class="top-menu-item text-center">
            <a href="{{ route('profile') }}" class="top-menu-link">
                <i data-feather="user" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p>Profile Setting</p>
            </a>
        </li>
        @endif
        @if (auth()->user()->role == 1)
        <li class="top-menu-item text-center">
            <a href="{{ route('user.list') }}" class="top-menu-link">
                <i data-feather="users" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Users </p>
            </a>
        </li>

        <li class="top-menu-item text-center">
            <a href="{{ route('user.pending') }}" class="top-menu-link">
                <i data-feather="user-x" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Pending Approvals </p>
            </a>
        </li>

        <li class="top-menu-item text-center">
            <a href="{{ route('extra-page.term') }}" class="top-menu-link">
                <i data-feather="pen-tool" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Terms of Service </p>
            </a>
        </li>

        @endif
        @if (auth()->user()->role == 0 )
        <li class="top-menu-item text-center">
            <a href="{{ route('category.list') }}" class="top-menu-link">
                <i data-feather="layers" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Fence Types </p>
            </a>
        </li>

        <li class="top-menu-item text-center">
            <a href="{{ route('ft_available.list') }}" class="top-menu-link">
                <i data-feather="check-circle" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Height Available </p>
            </a>
        </li>

        <li class="top-menu-item text-center">
            <a href="{{ route('fence.list') }}" class="top-menu-link">
                <i data-feather="dollar-sign" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Fence Styles/Pricing </p>
            </a>
        </li>

        @endif
        @if (auth()->user()->role == 1)
        <li class="top-menu-item text-center">
            <a href="{{ route('fence-ft.list') }}" class="top-menu-link">
                <i data-feather="file-text" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p>Fence Ft. Report</p>
            </a>
        </li>

        <li class="top-menu-item text-center">
            <a href="{{ route('custom-pages.list') }}" class="top-menu-link">
                <i data-feather="pen-tool" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Custom Pages </p>
            </a>
        </li>


        @endif




        @if(auth()->user()->role == 0)

        <li class="top-menu-item text-center">
            <a href="{{ route('estimator.index') }}" class="top-menu-link">
                <i data-feather="file-text" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Estimator Survey </p>
            </a>
        </li>

        <li class="top-menu-item text-center">
            <a href="{{ route('estimate.index') }}" class="top-menu-link">
                <i data-feather="percent" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Estimates </p>
            </a>
        </li>

        @endif

        <li class="top-menu-item text-center">
            <a href="{{ route('setting.index') }}" class="top-menu-link">
                <i data-feather="settings" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Settings </p>
            </a>
        </li>





        @if(auth()->user()->role == 0)


        <!-- <li class="has-submenu top-menu-item text-center">
        <a href="#" class="top-menu-link">
            <i data-feather="settings" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <p> More Pages </p>
        </a>
        <ul class="submenu childnav">
            @foreach($inpages as $page1)
                    <li class="top-menu-item text-center">
                        <a href="{{ route('custom-pages.visit', $page1->id) }}" class="top-menu-link">
                            <p> {{ $page1->name }} </p>
                        </a>
                    </li>
            @endforeach
        </ul>
    </li> -->

        @endif
        @if(auth()->user()->role == 1)
        <li class="top-menu-item text-center">
            <a href="{{ route('product.list') }}" class="top-menu-link">
                <i data-feather="pen-tool" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <p> Products </p>
            </a>
        </li>
        @endif


    </ul>
</div>

<li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarUI_Kit" data-bs-toggle="dropdown"
                                  aria-haspopup="true" aria-expanded="false">
                                  <span><i class="ti ti-planet menu-icon"></i>UI Kit</span>
                              </a>
                              <ul class="dropdown-menu animate slideIn" aria-labelledby="navbarUI_Kit">
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          UI Elements
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-alerts'>Alerts</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-avatar'>Avatar</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-buttons'>Buttons</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-badges'>Badges</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-cards'>Cards</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/ui-carousels'>Carousels</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/ui-dropdowns'>Dropdowns</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-grids'>Grids</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-images'>Images</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-list'>List</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-modals'>Modals</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-navs'>Navs</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-navbar'>Navbar</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/ui-paginations'>Paginations</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/ui-popover-tooltips'>Popover & Tooltips</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-progress'>Progress</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-spinners'>Spinners</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-tabs-accordions'>Tabs &
                                                  Accordions</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/ui-typography'>Typography</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/ui-videos'>Videos</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <!--end nav-item-->
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Advanced UI
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-animation'>Animation</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/advanced-clipboard'>Clip
                                                  Board</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-dragula'>Dragula</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/advanced-files'>File
                                                  Manager</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-highlight'>Highlight</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-rangeslider'>Range Slider</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-ratings'>Ratings</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-ribbons'>Ribbons</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-sweetalerts'>Sweet Alerts</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/advanced-toasts'>Toasts</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Forms
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/forms-elements'>Basic
                                                  Elements</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/forms-advanced'>Advance
                                                  Elements</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/forms-validation'>Validation</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/forms-wizard'>Wizard</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/forms-editors'>Editors</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/forms-uploads'>File
                                                  Upload</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/forms-img-crop'>Image
                                                  Crop</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Charts
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/charts-apex'>Apex</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/charts-justgage'>JustGage</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/charts-chartjs'>Chartjs</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/charts-toast-ui'>Toast</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Tables
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/tables-basic'>Basic</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/tables-datatable'>Datatables</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/tables-editable'>Editable</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Icons
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/icons-materialdesign'>Material Design</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/icons-fontawesome'>Font
                                                  awesome</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/icons-tabler'>Tabler</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/icons-feather'>Feather</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Maps
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/maps-google'>Google
                                                  Maps</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/maps-leaflet'>Leaflet
                                                  Maps</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item' href='/metrica/default/maps-vector'>Vector
                                                  Maps</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <li class="dropdown-submenu dropend">
                                      <a class="dropdown-item  dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                          Email Templates
                                      </a>
                                      <ul class="dropdown-menu animate slideIn">
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/email-templates-alert'>Alert Email</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/email-templates-basic'>Basic Action Email</a>
                                          </li>
                                          <!--end /li-->
                                          <li>
                                              <a class='dropdown-item'
                                                  href='/metrica/default/email-templates-billing'>Billing Email</a>
                                          </li>
                                          <!--end /li-->
                                      </ul>
                                  </li>
                                  <!--end nav-item-->
                              </ul>
                              <!--end submenu-->
                          </li>

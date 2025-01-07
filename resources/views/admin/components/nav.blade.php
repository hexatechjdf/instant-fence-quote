
     @php
        $inpages = \App\Models\CustomPage::where('is_active', 1)->where('is_dd_item',1)->get();
        $outpages = \App\Models\CustomPage::where('is_active', 1)->where('is_dd_item',0)->get();

    @endphp
    <li class="leftbar-menu-item">
        <a href="{{ route('dashboard') }}" class="menu-link">
            <i data-feather="home" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @if (auth()->user()->role == 0)
    <li class="leftbar-menu-item">
        <a href="{{ route('profile') }}" class="menu-link">
            <i data-feather="user" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <span>Profile Setting</span>
        </a>
    </li>
    @endif
    @if (auth()->user()->role == 1)
        <li class="leftbar-menu-item">
            <a href="{{ route('user.list') }}" class="menu-link">
                <i data-feather="users" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Users </span>
            </a>
        </li>
        
         <li class="leftbar-menu-item">
            <a href="{{ route('user.pending') }}" class="menu-link">
                <i data-feather="user-x" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Pending Approvals </span>
            </a>
        </li>
        
        <li class="leftbar-menu-item">
            <a href="{{ route('extra-page.term') }}" class="menu-link">
                <i data-feather="pen-tool" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Terms of Service </span>
            </a>
        </li>
        
    @endif
    @if (auth()->user()->role == 0 )
        <li class="leftbar-menu-item">
            <a href="{{ route('category.list') }}" class="menu-link">
                <i data-feather="layers" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Fence Types </span>
            </a>
        </li>

        <li class="leftbar-menu-item">
            <a href="{{ route('ft_available.list') }}" class="menu-link">
                <i data-feather="check-circle" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Height Available </span>
            </a>
        </li>

        <li class="leftbar-menu-item">
            <a href="{{ route('fence.list') }}" class="menu-link">
                <i data-feather="dollar-sign" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Fence Styles/Pricing </span>
            </a>
        </li>
        
    @endif
    @if (auth()->user()->role == 1)
        <li class="leftbar-menu-item">
            <a href="{{ route('fence-ft.list') }}" class="menu-link">
                <i data-feather="file-text" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span>Fence Ft. Report</span>
            </a>
        </li>
        
           <li class="leftbar-menu-item">
        <a href="{{ route('custom-pages.list') }}" class="menu-link">
            <i data-feather="pen-tool" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <span> Custom Pages </span>
        </a>
    </li>
    
   
    @endif
    
   
    

     @if(auth()->user()->role == 0)
    
         <li class="leftbar-menu-item">
            <a href="{{ route('estimator.index') }}" class="menu-link">
                <i data-feather="file-text" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Estimator Survey </span>
            </a>
        </li>
        
         <li class="leftbar-menu-item">
            <a href="{{ route('estimate.index') }}" class="menu-link">
                <i data-feather="percent" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
                <span> Estimates </span>
            </a>
        </li>

    @endif

    <li class="leftbar-menu-item">
        <a href="{{ route('setting.index') }}" class="menu-link">
            <i data-feather="settings" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <span> Settings </span>
        </a>
    </li>

    
   
        
        
    @if(auth()->user()->role == 0)
    
     @foreach($outpages as $page)
        <li class="leftbar-menu-item">
            <a href="{{ route('custom-pages.visit', $page->id) }}" class="menu-link">
                  <i data-feather="file" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>   
                <span> {{ $page->name }} </span>
            </a>
        </li>
     @endforeach
       
    <!-- <li class="has-submenu leftbar-menu-item">
        <a href="#" class="menu-link">
            <i data-feather="settings" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <span> More Pages </span>
        </a>
        <ul class="submenu childnav">
            @foreach($inpages as $page1)
                    <li class="leftbar-menu-item">
                        <a href="{{ route('custom-pages.visit', $page1->id) }}" class="menu-link">
                            <span> {{ $page1->name }} </span>
                        </a>
                    </li>
            @endforeach
        </ul>
    </li> -->

    @endif
     @if(auth()->user()->role == 1)
    <li class="leftbar-menu-item">
        <a href="{{ route('product.list') }}" class="menu-link">
            <i data-feather="pen-tool" class="align-self-center vertical-menu-icon icon-dual-vertical"></i>
            <span> Products </span>
        </a>
    </li>
    @endif


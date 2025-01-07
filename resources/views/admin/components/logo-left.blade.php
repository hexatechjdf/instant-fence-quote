 <!-- LOGO -->
 <a href="{{ route('dashboard') }}" class="logo">
     <span>
         <img src="{{ asset(setting('software_logo',1)) }}" alt="logo-small" class="logo-sm" style="height:50px; width:50px;border-radius:100%">
     </span>
     <span>
         {{-- <img src="{{ asset(setting('software_logo',1 }}" alt="logo-large" class="logo-lg logo-light">
         <img src="{{ asset(setting('software_logo',1)) }}" alt="logo-large" class="logo-lg logo-dark"> --}}
         <h4 class="logo-lg">{{ setting('software_name',1) ?? 'Instant Fence Price' }}</h4>
     </span>
 </a>

            <!-- LOGO -->
            <div class="brand">
                <a class='logo' href='/metrica/default/'>
                    <span>
                        <img src="assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
                    </span>
                    <span>
                        <img src="assets/images/logo.png" alt="logo-large" class="logo-lg logo-light">
                        <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
                    </span>
                </a>
            </div>
 <!--end logo-->

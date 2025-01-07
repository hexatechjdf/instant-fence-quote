 <!-- LOGO -->
            <div class="brand">
                <a class='logo' href='{{ route('dashboard') }}'>
                    <span>
                        <img src="{{ asset(setting('software_logo',1)) }}" alt="logo-small" class="logo-sm">
                    </span>
                    <span>
                    <h4 class="logo-lg">{{ setting('software_name',1) ?? 'Instant Fence Price' }}</h4>
                    </span>
                </a>
            </div>
 <!--end logo-->

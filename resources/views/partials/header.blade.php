<!-- Header Wrapper -->
<div class="header-wrapper shadow-sm bg-white">
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand w-100 justify-content-between">

                <!-- Logo & Sistem -->
                <div class="d-flex align-items-center">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none text-dark fw-bold fs-6">
                        <span class="d-none d-md-inline text-primary">LAPORAN</span>
                        <span id="userName" class="ms-1 text-secondary limit-text">
                            {{ Str::after(Auth::user()->name ?? 'Guest', ' - ') }}
                        </span>
                    </a>
                </div>

                <!-- User Dropdown -->
                @auth
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#"
                            id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets/images/logo/PosIND_Main Color.png') }}" alt="User Avatar"
                                class="rounded-circle border border-2 border-primary" width="40" height="40"
                                loading="lazy">
                            <div class="ps-2 d-none d-sm-block text-end">
                                <p id="userName" class="mb-0 fw-bold small limit-text">
                                    {{ Str::after(Auth::user()->name, ' - ') }}
                                </p>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const elements = document.querySelectorAll(".limit-text");
                                        elements.forEach(el => {
                                            const text = el.textContent.trim();
                                            if (text.length > 15) {
                                                el.textContent = text.substring(0, 15) + " ...";
                                            }
                                        });
                                    });
                                </script>
                                <p class="mb-0 text-muted small text-start">Keluar?</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                        <i class="bx bx-log-out-circle me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                @endauth

            </nav>
        </div>
    </header>
</div>
<!-- End Header -->

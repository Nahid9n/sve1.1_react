<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title') - {{ config('app.name') }}</title>
    <!-- CSS files -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" /> --}}
    <link href="{{ asset('backEnd/assets/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/demo.min.css') }}" rel="stylesheet" />

    {{-- tabler icon --}}
    <link href="{{ asset('backEnd/assets/libs/tabler-icon/tabler-icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}" rel="stylesheet" />

    {{-- toastr --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/') }}backEnd/assets/libs/toastr/toastr.min.css"> --}}

    <link href="{{ asset('backEnd/assets/css/custom.css') }}" rel="stylesheet" />
    <style>
        /* Sidebar container */
        /* Sidebar container */
        .sidebar {
            background: rgba(15, 23, 42, 0.85);
            /* semi-transparent dark base */
            backdrop-filter: blur(12px);
            /* glassmorphism blur effect */
            -webkit-backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0f7ff;
            /* soft neon text */
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.15);
            /* subtle neon glow */
        }

        /* Sidebar links */
        .sidebar a {
            color: #e0f7ff;
            position: relative;
            /* padding: 8px 12px; */
            /* display: block; */
            transition: all 0.3s ease;
        }

        /* Hover effect with neon glow */
        .sidebar a:hover {
            color: #0ff;
            /* neon cyan */
            background: rgba(0, 255, 255, 0.1);
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 255, 255, 0.6);
        }

        /* Active menu highlight with soft neon underline */
        .sidebar .active {
            color: #0ff;
            font-weight: 600;
        }

        /* .sidebar .active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background: #0ff;
            border-radius: 2px;
            box-shadow: 0 0 6px #0ff;
        } */

        /* Optional: soft divider lines */
        .sidebar hr {
            border-color: rgba(0, 255, 255, 0.1);
        }

        /* Scrollbar styling for premium look */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }


        @media (min-width: 992px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm {
                max-width: 100%;
            }
        }

        @media (min-width: 1200px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl {
                max-width: 100%;
            }
        }

        @media (min-width: 1400px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                max-width: 100%;
            }
        }

        .custom-card-footer {
            padding: 5px;
        }

        .custom-card-footer p {
            font-size: 12px;
        }

        .custom-card-footer .page-item {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-card-footer .page-link {
            font-size: 12px;
        }

        .page-item.active .page-link {
            padding: 2px;
        }

        .custom-card-footer .page-link svg {
            height: 18px;
            width: 18px;
        }

        .order-paginate .custom-card-footer {
            border-top: 0;
        }
    </style>

    @stack('css')
</head>

<body>

    @if (isset($planExpiredData['website_status']) && $planExpiredData['website_status'] == 2)
        {{-- TERMINATED: FULL BLOCK MODE --}}
        <div
            style="
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100%;
    padding: 20px;
    background: #f8f8f8;
">
            <div
                style="position:absolute; top: 35%; left: 35%; background: #fff4f4;border: 1px solid #ffcccc;padding: 45px; max-width: 1000px;border-radius: 16px;text-align: center;box-shadow: 0 6px 18px rgba(0,0,0,0.12);">
                <h2 style="color:#8b0000; font-size: 36px; margin-bottom: 15px;">
                    ⛔ Website Terminated
                </h2>
                <p style="font-size: 18px; color:#444;">
                    This website has been <strong>terminated</strong>. Immediate action is required.
                </p>

                <p style="margin-top: 25px; color:#555; font-size: 18px;">
                    Need help? Contact: <strong>{{ $planExpiredData['support_phone'] }}</strong>
                </p>
            </div>
        </div>
    @else
        {{-- NORMAL LAYOUT START --}}
        <div class="page">
            @include('backEnd.admin.includes.sidebar')
            @include('backEnd.admin.includes.header')

            <div class="page-wrapper position-relative" id="contentWrapper">

                {{-- EXPIRED / WARNING BLOCK --}}
                @if (isset($planExpiredData['force_block']) && $planExpiredData['force_block'] == true)
                    <div
                        style="background: #fff4f4;border: 1px solid #ffcccc;padding: 45px; max-width: 1000px;margin:auto;border-radius: 16px;text-align: center;box-shadow: 0 6px 18px rgba(0,0,0,0.12);transform: scale(1.05);">

                        @if ($planExpiredData['website_status'] == 0)
                            <h2 style="color:#d00000; font-size: 36px; margin-bottom: 15px;">
                                ⚠️ Website Inactive
                            </h2>
                            <p style="font-size: 18px; color:#444;">
                                Your website is currently <strong>inactive</strong>. Please contact support.
                            </p>
                        @else
                            <h2 style="color:#d00000; font-size: 36px; margin-bottom: 15px;">
                                ⚠️ Subscription Expired
                            </h2>

                            <p style="font-size: 20px; color:#444;">
                                Your subscription expired on <strong>{{ $planExpiredData['expire_date'] }}</strong>.
                            </p>

                            @if ($planExpiredData['invoice_no'])
                                <a class="bg-success" target="_blank"
                                    href="https://clients.prodevsltd.xyz/invoice/{{ $planExpiredData['invoice_no'] }}"
                                    style="display: inline-block; padding: 14px 30px; color: #fff;text-decoration: none;border-radius: 10px;font-weight: 700;font-size: 18px;margin-top: 10px;">
                                    🔑 Pay Now
                                </a>
                            @endif
                        @endif

                        <p style="margin-top: 25px; color:#555; font-size: 18px;">
                            Need help? Contact: <strong>{{ $planExpiredData['support_phone'] }}</strong>
                        </p>
                    </div>
                @else
                    {{-- Normal page content --}}
                    @yield('content')
                @endif

                @include('backEnd.admin.includes.footer')
            </div>
        </div>
        {{-- NORMAL LAYOUT END --}}
    @endif





    <a id="back-to-top-btn"></a>

    <!-- Libs JS -->
    <script src="{{ asset('backEnd/assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Tabler Core -->
    <script src="{{ asset('backEnd/assets/js/tabler.min.js') }}"></script>
    <script src="{{ asset('backEnd/assets/js/demo.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" ></script> --}}
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>
    {{-- <script src="{{ asset('/') }}backEnd/assets/libs/toastr/toastr.min.js"></script> --}}
    <script src="{{ asset('backEnd/assets/libs/sweet-alert/sweetalert2@11.js') }}"></script>

    <script>
        @if (session()->has('success'))
            toastr.options = {
                "positionClass": "toast-bottom-left"
            };
            toastr.success("{{ session('success') }}");
        @endif
        @if (Session::has('error'))
            toastr.options = {
                "positionClass": "toast-bottom-left"
            };
            toastr.error("{{ session('error') }}");
        @endif
        @if (Session::has('info'))
            toastr.options = {
                "positionClass": "toast-bottom-left"
            };
            toastr.info("{{ session('info') }}");
        @endif
        @if (Session::has('warning'))
            toastr.options = {
                "positionClass": "toast-bottom-left"
            };
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    @yield('script')

    <script>
        var back_to_top_btn = $('#back-to-top-btn');

        $(window).scroll(function() {
            if ($(window).scrollTop() > 300) {
                back_to_top_btn.addClass('show');
            } else {
                back_to_top_btn.removeClass('show');
            }
        });

        back_to_top_btn.on('click', function(e) {
            e.preventDefault();
            $('html, body').scrollTop(0);
        });


        $('.select2').select2();
        @if (session()->has('success'))
            Swal.fire({
                //position: 'bottom-end',
                title: 'Success',
                text: '{{ session('success') }}',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000
            });
        @endif

        @if (Session::has('error'))
            Swal.fire({
                //position: 'bottom-end',
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                showConfirmButton: false,
                timer: 1000
            });
        @endif


        @if (Session::has('info'))
            Swal.fire({
                //position: 'bottom-end',
                title: 'Info',
                text: '{{ session('info') }}',
                icon: 'info',
                showConfirmButton: false,
                timer: 1000
            });
        @endif

        @if (Session::has('warning'))
            Swal.fire({
                //position: 'bottom-end',
                title: 'Warning',
                text: '{{ session('warning') }}',
                icon: 'warning',
                showConfirmButton: false,
                timer: 1000
            });
        @endif

        $(document).on("click", ".auto-select-number", function() {
            if ($(this).val() <= 0) {
                $(this).select();
            }
        });
    </script>
    <script>
        // Global Select2 Search Auto Focus
        $(document).on('select2:open', function() {
            setTimeout(function() {
                let searchField = document.querySelector('.select2-container--open .select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            }, 10);
        });
    </script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {

            const sidebar = document.getElementById("sidebar");
            if (!sidebar) return;

            // Only desktop (lg and above)
            if (window.innerWidth >= 992) {

                const savedScroll = localStorage.getItem("sidebar-scroll");

                if (savedScroll !== null) {
                    sidebar.scrollTop = savedScroll;
                }

                sidebar.addEventListener("scroll", function() {
                    localStorage.setItem("sidebar-scroll", sidebar.scrollTop);
                });
            }

        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const sidebar = document.getElementById("sidebar");
            if (!sidebar) return;

            // Only for desktop (navbar-expand-lg)
            if (window.innerWidth >= 992) {

                const activeItem = sidebar.querySelector(".nav-item.active, .dropdown-item.active");

                if (activeItem) {
                    // Smooth scroll to active item
                    sidebar.scrollTo({
                        top: activeItem.offsetTop - 100,
                        behavior: "smooth"
                    });
                }
            }

        });
    </script>



    @stack('js')
</body>

</html>

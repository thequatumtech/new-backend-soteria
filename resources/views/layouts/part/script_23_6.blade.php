<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/left-nav.js') }}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script>
    $(document).on('click', '.logoutbtn', function () {
        $('#LogoutModal').modal('show');
    });
 
    jQuery(function ($) { 
        $(".sidebar-dropdown > a").click(function () {
            $(".sidebar-submenu").slideUp(200);
            $(".sidebar-dropdown").removeClass("active active-main");
            if (!$(this).parent().hasClass("active")) {
                $(this).next(".sidebar-submenu").slideDown(200);
                $(this).parent().addClass("active active-main");
            }
        });
 
        // Toggle sidebar
        $("#close-sidebar").click(function () {
            $(".page-wrapper").removeClass("toggled");
        });
        $("#show-sidebar").click(function () {
            $(".page-wrapper").addClass("toggled");
        });
 
        // Mobile auto-hide
        $(document).ready(function () {
            var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            if (isMobile) {
                $("#close-sidebar").click();
            }
            $('table').parent("div").css('overflow', 'auto');
        });
 
        // 💡 Inner Submenu Dropdown (with arrow toggle)
        $(".inner-dropdown > a").click(function (e) {
            e.preventDefault();
            var $submenu = $(this).next(".inner-submenu");
            var $icon = $(this).find(".toggle-arrow");
 
            // Close others
            $(".inner-submenu").not($submenu).slideUp(200);
            $(".inner-dropdown").not($(this).parent()).removeClass("active")
                .find(".toggle-arrow").removeClass("fa-angle-down").addClass("fa-angle-right");
 
            // Toggle current
            if ($submenu.is(":visible")) {
                $submenu.slideUp(200);
                $(this).parent().removeClass("active");
                $icon.removeClass("fa-angle-down").addClass("fa-angle-right");
            } else {
                $submenu.slideDown(200);
                $(this).parent().addClass("active");
                $icon.removeClass("fa-angle-right").addClass("fa-angle-down");
            }
        });
    });
    $(document).ready(function () {
        let currentPath = window.location.pathname;

        $('.sidebar-submenu a, .inner-submenu a').each(function () {
            let linkPath = new URL(this.href, window.location.origin).pathname;

            if (linkPath === currentPath) {
                // Mark only the clicked or matched route as active
                $(this).addClass('active');

                // Expand inner-dropdown
                let $innerDropdown = $(this).closest('.inner-dropdown');
                if ($innerDropdown.length) {
                    $innerDropdown.addClass('active');
                    $innerDropdown.find('.inner-submenu').show();
                    $innerDropdown.find('.toggle-arrow')
                        .removeClass('fa-angle-right')
                        .addClass('fa-angle-down');
                }

                // Expand sidebar-dropdown
                let $sidebarDropdown = $(this).closest('.sidebar-dropdown');
                if ($sidebarDropdown.length) {
                    $sidebarDropdown.addClass('active active-main');
                    $sidebarDropdown.find('.sidebar-submenu').show();
                }
            }
        });
    });

</script>
  --}}
<script>   
    $(document).on('click', '.logoutbtn', function() {
        $('#LogoutModal').modal('show');
    });
    jQuery(function($) {
        $(".sidebar-dropdown > a").click(function() {
            $(".sidebar-submenu").slideUp(200);
            if ($(this).parent().hasClass("active")) 
            {
                $(".sidebar-dropdown").removeClass("active active-main");
                $(this).parent().removeClass("active active-main");
            } 
            else 
            {
                $(".sidebar-dropdown").removeClass("active active-main");
                $(this).next(".sidebar-submenu").slideDown(200);
                $(this).parent().addClass("active");
            }
        });
        $("#close-sidebar").click(function() {
            $(".page-wrapper").removeClass("toggled");
        });
        $("#show-sidebar").click(function() {
            $(".page-wrapper").addClass("toggled");
        });
        $(document).ready(function() {
            var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            if (isMobile) {
                $("#close-sidebar").click()
            }
            $('table').parent("div").css('overflow', 'auto');

        });
        $(document).ready(function() {
            /*
                // Get selected cities when the countries change
                $('select[name="restricted_country_ids[]"]').on('change', function() {
                    let restricted_country_ids = $(this).val(); // Get selected values as an array
                    // Target the select element by its name
                    const $select = $('select[name="restricted_city_ids[]"]');
                    let selected_city_ids = $select.val();
                    $select.empty();
                    $select.trigger('change');
                    if(restricted_country_ids && restricted_country_ids.length > 0 ) {
                        let baseUrl = window.location.origin; // Get the base URL of your Laravel application
                        let url = baseUrl + '/get-cities';
                        const csrf_token = "{{ csrf_token() }}";
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {'restricted_country_ids': restricted_country_ids, "_token": csrf_token,},
                            success: function (response) {
                                // Add options dynamically
                                response.data.forEach(city => {
                                    $select.append(new Option(city.name, city.id)); // Creates an <option> element
                                });
                                $select.val(selected_city_ids).change();
                                // If you're using Select2, reinitialize it after adding options
                                if ($select.hasClass('select2')) {
                                    $select.trigger('change'); // Refresh Select2
                                }
                            }
                        });
                    }
                });
                // Get selected districts when the cities change
                $(document).on('change', 'select[name="restricted_city_ids[]"]', function() {
                    let restricted_city_ids = $(this).val(); // Get selected values as an array
                    // Target the select element by its name
                    const $select = $('select[name="restricted_district_ids[]"]');
                    let selected_district_ids = $select.val();
                    $select.empty();
                    $select.trigger('change');
                    if(restricted_city_ids && restricted_city_ids.length > 0 ) {
                       let baseUrl = window.location.origin;
                       // Get the base URL of your Laravel application
                       let url = baseUrl + '/get-districts';
                       const csrf_token = "{{ csrf_token() }}";
                       $.ajax({
                           type: "POST",
                           url: url,
                           dataType: "json",
                           data: {'restricted_cities_ids': restricted_city_ids, "_token": csrf_token,},
                           success: function (response) {
                               // Add options dynamically
                               response.data.forEach(district => {
                                   $select.append(new Option(district.name, district.id)); // Creates an <option> element
                               });
                               $select.val(selected_district_ids).change();
                               // If you're using Select2, reinitialize it after adding options
                               if ($select.hasClass('select2')) {
                                   $select.trigger('change'); // Refresh Select2
                               }
                           }
                       });
                   }

                });
            */

            $('.positive_number_only_2_decimal').on('input', function() {
                let value = $(this).val();

                // Allow only positive numbers with up to 2 decimal places
                let validValue = value.match(/^\d*\.?\d{0,2}$/) ? value : value.replace(
                    /[^0-9.]/g, '');

                // Ensure only one decimal point is allowed
                let parts = validValue.split('.');
                if (parts.length > 2) {
                    validValue = parts[0] + '.' + parts.slice(1).join('').replace(/\./g, '');
                }

                // Restrict to two decimal places
                if (parts.length === 2 && parts[1].length > 2) {
                    validValue = parts[0] + '.' + parts[1].substring(0, 2);
                }

                $(this).val(validValue);
            });
        });

    });
</script>
@yield('script')

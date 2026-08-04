<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/left-nav.js')}}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).on('click', '.logoutbtn', function() {
        $('#LogoutModal').modal('show');
    });

    jQuery(function ($) {

        $(".sidebar-dropdown > a").click(function () {
            $(".sidebar-submenu").slideUp(200);
            if (
                $(this)
                    .parent()
                    .hasClass("active")
            ) {
                $(".sidebar-dropdown").removeClass("active active-main");
                $(this)
                    .parent()
                    .removeClass("active active-main");
            } else {
                $(".sidebar-dropdown").removeClass("active active-main");
                $(this)
                    .next(".sidebar-submenu")
                    .slideDown(200);
                $(this)
                    .parent()
                    .addClass("active");
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

    $('.positive_number_only_2_decimal').on('input', function () {
        let value = $(this).val();

        let validValue = value.match(/^\d*\.?\d{0,2}$/) ? value : value.replace(/[^0-9.]/g, '');

        let parts = validValue.split('.');
        if (parts.length > 2) {
            validValue = parts[0] + '.' + parts.slice(1).join('').replace(/\./g, '');
        }

        if (parts.length === 2 && parts[1].length > 2) {
            validValue = parts[0] + '.' + parts[1].substring(0, 2);
        }

        $(this).val(validValue);
    });
});

});

</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Toggle submenu on click
    document.querySelectorAll(".inner-dropdown > a").forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();

            const parentLi = this.closest(".inner-dropdown");
            const submenu = parentLi.querySelector(".inner-submenu");
            const arrow = this.querySelector(".toggle-arrow");

            // Toggle visibility
            submenu.style.display = (submenu.style.display === "none" || submenu.style.display === "") ? "block" : "none";

            // Toggle arrow direction
            if (arrow) {
                arrow.classList.toggle("rotate-90");
            }

            // Optionally: close others
            document.querySelectorAll(".inner-dropdown").forEach(function (li) {
                if (li !== parentLi) {
                    const otherSub = li.querySelector(".inner-submenu");
                    const otherArrow = li.querySelector(".toggle-arrow");
                    if (otherSub) otherSub.style.display = "none";
                    if (otherArrow) otherArrow.classList.remove("rotate-90");
                }
            });
        });
    });
});
</script>

@yield('script')

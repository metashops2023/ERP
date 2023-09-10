// $(window).on('load', function() {
//     $('.main__nav ul li').first().addClass('active');
//     $('#sidebar div').first().addClass('active');
// })

$(document).ready(function() {
    $('.main__nav ul li').on('click', function() {
        $('.main__nav ul li').removeClass('active');
        $(this).addClass('active');
        let menuID = $(this).data('menu')
        $('#sidebar_t div').removeClass('active');
        $('#' + menuID).addClass('active')
    })
});

// =============================================================side manu====================
// $(document).ready(function() {
//     $('.main__nav_t ul li').on('click', function() {
//         $('.main__nav_t ul li').removeClass('active');
//         $(this).addClass('active');
//         let menuID = $(this).data('menu')
//         $('#sidebar_t div').removeClass('active');
//         $('#' + menuID).addClass('active')
//     })
// })

// ===============================================================sub menu active ====================
// $(document).ready(function() {
//     $('#sidenav li').on('click', function() {
//         $('#sidenav li').removeClass('active');
//         $(this).addClass('active');
//         var sidemenu = $(this).data('submenu')
//         $('#sidenav div').removeClass('active');
//         $('#' + sidemenu).addClass('active')
//     })
// })

// ===============================================================sub menu active end ====================
// ===============================================================sub menu active ====================
$(document).ready(function() {
    $('.close-model').on('click', function() {
        $('.sub-menu').removeClass('active');
    })
})

$(document).ready(function() {
    $('.close-model').on('click', function() {
        $('.sub-menu_t').removeClass('active');
    })
})

// ===============================================================sub menu close end ====================
$(document).ready(function() {
    $('#left_bar_toggle').on('click', function() {
        $('#primary_nav').toggleClass('active');
        $('.top-main-menu').toggleClass('active');

        $('.top-menu-dropdown').on('click', function() {
            $('.top-dp-menu-one').toggleClass('first-menu-active');
        });

        if ( $(window).width() < 768 && $(window).width() > 500 ) {
            $('.body-woaper').toggleClass('toggle_reduce_body_woaper_tab');
            $('.main-woaper').toggleClass('toggle_reduce_main_woaper_tab');
            $('.navigation').toggleClass('toggle_reduce_navigation_width_tab');
            $('#left_bar_toggle').css('margin-right','0px!important');
        } else if ($(window).width() < 500 ) {
            $('.body-woaper').toggleClass('toggle_reduce_body_woaper_mobile');
            $('.main-woaper').toggleClass('toggle_reduce_main_woaper_mobile');
            $('.navigation').toggleClass('toggle_reduce_navigation_width_mobile');
            $('#left_bar_toggle').css('margin-right','0px!important');
        }
    })
})

// ===============================================toltip==============================
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

// =====================================================text editor=================================
// ClassicEditor
//     .create(document.querySelector('#editor'), {
//         // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
//     })
//     .then(editor => {
//         window.editor = editor;
//     })
//     .catch(err => {
//         console.error(err.stack);
//     });


// =============================================taginput======================
// $('#input-tags').selectize({
//     persist: false,
//     createOnBlur: true,
//     create: true,
//     plugins: ['remove_button'],
// });

// $('#select-state').selectize({
//     maxItems: 9999,
//     plugins: ['remove_button'],
// });

// $('#select-gear').selectize({
//     sortField: 'text',
//     plugins: ['remove_button'],
// });

// ==================================================================data table==================
$(document).ready(function() {
    $('#example').DataTable({
        select: true
    });
});

// =====================================================form repetar==========
// $(document).ready(function() {
//     $('.repeater').repeater({
//         show: function() {
//             $(this).slideDown();
//         },
//         hide: function(deleteElement) {
//             if (confirm('Are you sure you want to delete this element?')) {
//                 $(this).slideUp(deleteElement);
//             }
//         },
//         ready: function(setIndexes) {

//         }
//     });
// });

// ===========================================date picer==================
$(function() {
    $("#start-date").datepicker({
        autoclose: true,
        todayHighlight: true
    }).datepicker('update', new Date());
});
$(function() {
    $("#end-date").datepicker({
        autoclose: true,
        todayHighlight: true
    }).datepicker('update', new Date());
});

// ======================================================button switch============
$(function() {
    $('div.switch-grup button').on('click', function() {
        $(this).addClass('selected').siblings().removeClass('selected');
    });
});

// =========================================rating===============
$('.star').fontstar({}, function(value, self) {
    console.log("hello " + value);
});

// =========================================clickeditor==================
// $(document).ready(function() {
//     $("#textEditor").cleditor();
// });

// ===============================================search and select================
$(function() {
    $("#searchSelect").customselect();
});

/* Dore Theme Select & Initializer Script 

Table of Contents

01. Css Loading Util
02. Theme Selector And Initializer
*/


/* 01. Css Loading Util */

initDatatable = (el, opt) => {
  $(el).DataTable({
    searching: !opt.search ? false : opt.search,
    lengthchange: !opt.change ? false : opt.change,
    destroy: true,
    info: !opt.info ? false : opt.info,
    ordering: !opt.order ? false : opt.order,
    sDom:
      '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
    pageLength: 6,
    language: {
      paginate: {
        previous: "<i class='simple-icon-arrow-left'></i>",
        next: "<i class='simple-icon-arrow-right'></i>"
      }
    },

    drawCallback: function () {
      $($(".dataTables_wrapper .pagination li:first-of-type"))
        .find("a")
        .addClass("prev");
      $($(".dataTables_wrapper .pagination li:last-of-type"))
        .find("a")
        .addClass("next");

      $(".dataTables_wrapper .pagination").addClass("pagination-sm");
    }
  });
}

const getRandomId = (tipe = 'string', length = 5, between = null) => {

  if (tipe == 'number' && between)
    return Math.random().toString(between).substr(2, length);
  else
    return Math.random().toString(20).substr(2, length)
};

const waktu = (time = null, format = 'mysqltimestamp') => {
  if (format == 'mysqltimestamp')
    format = 'YYYY-MM-DD HH:mm:ss';
  if (!time)
    time = new Date();

  return moment(time).format(format);
};

String.prototype.capitalize = function (tipe = 'first') {
  if (tipe != 'first') {
    var strings = this.split(' ');
    var text = [];

    strings.forEach(s => {
      text.push(s.charAt(0).toUpperCase() + s.slice(1));
    });
    return text.join(' ');
  }
  else
    return this.charAt(0).toUpperCase() + this.slice(1);

}
String.prototype.replaceAll = function (awal, baru) {
  var strings = this.split(awal);
  return strings.join(baru);
}
String.prototype.rupiahFormat = function () {
  var bilangan = this;
  var number_string = bilangan.toString(),
    sisa = number_string.length % 3,
    rupiah = number_string.substr(0, sisa),
    ribuan = number_string.substr(sisa).match(/\d{3}/g);

  if (ribuan) {
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }
  return rupiah;
}
function loadStyle(href, callback) {
  for (var i = 0; i < document.styleSheets.length; i++) {
    if (document.styleSheets[i].href == href) {
      return;
    }
  }
  var head = document.getElementsByTagName("head")[0];
  var link = document.createElement("link");
  link.rel = "stylesheet";
  link.type = "text/css";
  link.href = href;
  if (callback) {
    link.onload = function () {
      callback();
    };
  }
  head.appendChild(link);
}
/* 02. Theme Selector And Initializer */
(function ($) {
  if ($().dropzone) {
    Dropzone.autoDiscover = false;
  }

  var themeColorsDom =
    '<div class="theme-colors"> <div class="p-4"> <p class="text-muted mb-2">Light Theme</p> <div class="d-flex flex-row justify-content-between mb-4"> <a href="#" data-theme="dore.light.blue.min.css" class="theme-color theme-color-blue"></a> <a href="#" data-theme="dore.light.purple.min.css" class="theme-color theme-color-purple"></a> <a href="#" data-theme="dore.light.green.min.css" class="theme-color theme-color-green"></a> <a href="#" data-theme="dore.light.orange.min.css" class="theme-color theme-color-orange"></a> <a href="#" data-theme="dore.light.red.min.css" class="theme-color theme-color-red"></a> </div> <p class="text-muted mb-2">Dark Theme</p> <div class="d-flex flex-row justify-content-between"> <a href="#" data-theme="dore.dark.blue.min.css" class="theme-color theme-color-blue"></a> <a href="#" data-theme="dore.dark.purple.min.css" class="theme-color theme-color-purple"></a> <a href="#" data-theme="dore.dark.green.min.css" class="theme-color theme-color-green"></a> <a href="#" data-theme="dore.dark.orange.min.css" class="theme-color theme-color-orange"></a> <a href="#" data-theme="dore.dark.red.min.css" class="theme-color theme-color-red"></a> </div> </div> <a href="#" class="theme-button"> <i class="simple-icon-magic-wand"></i> </a> </div>';
  $("body").append(themeColorsDom);
  var theme = resources_path + "/css/dore.light.blue.min.css";
  if (typeof Storage !== "undefined") {
    if (localStorage.getItem("theme")) {
      theme = localStorage.getItem("theme");
    }
  }

  $(".theme-color[data-theme='" + theme + "']").addClass("active");

  loadStyle(theme, onStyleComplete);
  function onStyleComplete() {
    setTimeout(onStyleCompleteDelayed, 300);
  }

  function onStyleCompleteDelayed() {
    var $dore = $("body").dore();
  }

  $("body").on("click", ".theme-color", function (event) {
    event.preventDefault();
    var dataTheme = $(this).data("theme");
    if (typeof Storage !== "undefined") {
      localStorage.setItem("theme", dataTheme);
      window.location.reload();
    }
  });


  $(".theme-button").on("click", function (event) {
    event.preventDefault();
    $(this)
      .parents(".theme-colors")
      .toggleClass("shown");
  });
  $(document).on("click", function (event) {
    if (
      !(
        $(event.target)
          .parents()
          .hasClass("theme-colors") ||
        $(event.target)
          .parents()
          .hasClass("theme-button") ||
        $(event.target).hasClass("theme-button") ||
        $(event.target).hasClass("theme-colors")
      )
    ) {
      if ($(".theme-colors").hasClass("shown")) {
        $(".theme-colors").removeClass("shown");
      }
    }
  });
})(jQuery);

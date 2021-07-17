/**
 * WP External Links Plugin
 * Admin
 */
/*global jQuery, window*/
jQuery(function ($) {
  // add custom jQuery show/hide function
  $.extend($.fn, {
    wpelShow: function () {
      var self = this;
      this.stop({ clearQueue: true, jumpToEnd: true });
      this.fadeIn({
        duration: 500,
        queue: false,
        complete: function () {
          self.removeClass("wpel-hidden");
        },
      });
    },
    wpelHide: function () {
      var self = this;
      this.stop({ clearQueue: true, jumpToEnd: true });
      this.fadeOut({
        duration: 500,
        queue: false,
        complete: function () {
          self.addClass("wpel-hidden");
        },
      });
    },
  });

  var $wrapper = $(".wpel-settings-page");

  /**
   * Apply Sections Settings
   */
  $wrapper.on("change", ".js-wpel-apply input", function () {
    var applyAll = $(this).is(":checked");
    var $items = $wrapper.find(".js-wpel-apply-child");

    if (applyAll) {
      $items.wpelHide();
    } else {
      $items.wpelShow();
    }
  });

  // trigger immediatly
  $wrapper.find('.js-wpel-apply input[type="checkbox"]').change();

  /**
   * Link Settings
   */
  $wrapper.on("change", ".js-icon-type select", function () {
    var iconType = $(this).val();
    var $itemsChild = $wrapper.find(".js-icon-type-child");
    var $itemsDepend = $wrapper.find(".js-icon-type-depend");

    $itemsChild.hide();

    if (iconType === "image") {
      $itemsDepend.wpelShow();
      $itemsChild.filter(".js-icon-type-image").wpelShow();
    } else if (iconType === "dashicon") {
      $itemsDepend.wpelShow();
      $itemsChild.filter(".js-icon-type-dashicon").wpelShow();
    } else if (iconType === "fontawesome") {
      $itemsDepend.wpelShow();
      $itemsChild.filter(".js-icon-type-fontawesome").wpelShow();
    } else {
      $itemsDepend.wpelHide();
    }
  });

  $wrapper.on("change", '.js-apply-settings input[type="checkbox"]', function () {
    var $items = $wrapper.find(".form-table tr").not(".js-apply-settings");

    if ($(this).prop("checked")) {
      $items.wpelShow();
      $wrapper.find(".js-icon-type select").change();
    } else {
      $items.wpelHide();
    }
  });

  // trigger immediatly
  $wrapper.find('.js-apply-settings input[type="checkbox"]').change();

  /**
   * Support
   * Copy to clipboard
   */
  $wrapper.on("click", ".js-wpel-copy", function (e) {
    e.preventDefault();

    var node = $wrapper.find(".js-wpel-copy-target").get(0);
    node.select();

    var range = document.createRange();
    range.selectNode(node);
    window.getSelection().addRange(range);

    try {
      document.execCommand("copy");
    } catch (err) {
    }
  });

  /**
   * Help documentation links/buttons
   */
  $wrapper.on("click", "[data-wpel-help]", function () {
    var helpKey = $(this).data("wpel-help");

    if (helpKey) {
      // activate given tab
      $("#tab-link-" + helpKey + " a").click();
    } else {
      // activate first tab
      $(".contextual-help-tabs li a").first().click();
    }

    $('#contextual-help-link[aria-expanded="false"]').click();
  });

  // show current tab
  $wrapper.find("form").wpelShow();
  // for network pages
  $(".wpel-network-page").find("form").wpelShow();

  let check_links_timeout;

  $wrapper.on("click", ".check-links", function (e) {
    e.preventDefault();
    
    check_links($(this).data("force"));
    if (!$(this).data("force")) {
      $("#lh_results").html('<tr class="lh-results-loader"><td><img src=' + wpel.loader + " /><br />Generating list of pages</td></tr>");
    }
  });

  const urlParams = new URLSearchParams(window.location.search);
  const current_tab = decodeURI(urlParams.get("tab"));
  
  if ((current_tab == 'null' || current_tab == null || current_tab == "link-checking") && wpel.link_checking_enabled) {
    check_links();
  }

  function check_links(force) {
    $("#lh_results").show();
    let loader_html = '<img class="wpel-loader" src=' + wpel.loader + " />";
    if (force) {
      clearTimeout(check_links_timeout);
    }
    $.ajax({
      url: ajaxurl,
      method: "POST",
      crossDomain: true,
      dataType: "json",
      timeout: 30000,
      data: {
        _ajax_nonce: wpel.nonce_ajax,
        action: "wpel_run_tool",
        force: force,
        tool: "check_links",
      },
    })
      .done(function (response) {
        if (response.success == true) {
          if (force == true) {
            location.reload();
            return;
          }

          if (response.data.status == "scan_pending" || response.data.status == "pending" || response.data.status == "finished") {
            var total_pages = 0;
            var total_pages_links = 0;
            var total_links = 0;
            var total_finished = 0;

            for (page in response.data.pages) {
              total_pages++;
              var page_id = page.replace(/\/|\:|\./g, "");
              var html = "";

              html += '<td class="lh-page-href">';
              if (response.data.pages[page].title && response.data.pages[page].title.length > 0) {
                html += response.data.pages[page].title + " - ";
              }
              html += '<a href="' + page + '" target="_blank">' + page + '<span class="dashicons dashicons-external"></span></a>';
              html += "</td>";

              html += '<td class="lh-results-stats">';
              html += '<span class="lh-results-links-total">' + response.data.pages[page].links_total + " links</span>";
              html += '<span class="lh-results-links-finished">' + response.data.pages[page].links_finished + " scanned</span>";
              if(response.data.pages[page].links_error > 0){
                html += '<span class="lh-results-links-error">' + response.data.pages[page].links_error + " errors</span>";
              }
              html += "</td>";

              html += "<td>";
              //Show loader
              total_links+=response.data.pages[page].links_total;
              total_finished+=response.data.pages[page].links_error + response.data.pages[page].links_finished;
              if(response.data.pages[page].links_total > 0){
                total_pages_links++;
              }
              if (response.data.pages[page].links_total == 0 || response.data.pages[page].links_total > response.data.pages[page].links_error + response.data.pages[page].links_finished) {
                html += loader_html;
              } else {
                html += '<div class="button button-primary lh-open-analysis">Open Details</div>';
              }
              html += "</td>";

              if ($("#lh_results #wpel-page-" + page_id).length > 0) {
                if ($("#wpel-page-" + page_id).text() != $("<div>").append(html).text()) {
                  $("#wpel-page-" + page_id).html(html);
                }
              } else {
                $("#lh_results").append('<tr class="lh-page" id="wpel-page-' + page_id + '" data-page="' + page + '">' + html + "</tr>");
              }
            }

            if (total_pages > 0) {
              $(".lh-results-loader").remove();

              var page_progress = total_pages_links/total_pages * 25;
              var link_progress = total_finished/total_links * 75;

              if(total_finished < total_links){
                $('#lh-progress-bar-wrapper').show();
                var progress = page_progress + link_progress;
                $('#lh-progress-bar').css('width',progress+'%');
              } else {
                $('#lh-progress-bar-wrapper').hide();
              }

              $(".lh-search-wrapper").show();
              if (response.data.total_pages > total_pages) {
                var unscanned_pages = response.data.total_pages - total_pages;
                $("#lh_pro_count").html(unscanned_pages);
                $("#lh_pro").show();
              } else {
                $("#lh_pro").hide();
              }
            } else {
              $(".lh-search-wrapper").hide();
            }

            if (response.data.status == "pending") {
              check_links_timeout = setTimeout(function () {
                check_links();
              }, 2000);
            }
          }
        } else {
          alert(response.data);
        }
      })
      .fail(function (data) {
        alert("An undocumented error occured checking the links");
      });
  } // check_links

  var analysis_table = false;
  $("#lh_results").on("click", ".lh-open-analysis", function () {
    var page = $(this).parents(".lh-page").attr("data-page");
    var title = $(this).parents(".lh-page").children(".lh-page-href").html();

    $("#lh_details_title").html(title);

    if (analysis_table != false) {
      analysis_table.destroy();
      analysis_table = false;
    }

    analysis_table = $("#lh_page_details").DataTable({
      ajax: ajaxurl + "?action=wpel_run_tool&tool=link_details&link=" + page,
      columnDefs: [
        {
          targets: [0],
          className: "dt-body-center",
          width: 100,
        },
        {
          targets: [1, 2, 3],
          className: "dt-body-left dt-head-center",
        },
      ],
      fixedColumns: true,
    });

    $("#lh_details").show();
    $("body").addClass("body_lh_details_open");
  });

  $("#lh_details").on("click", ".lh-close", function () {
    $("#lh_details").hide();
    $("body").removeClass("body_lh_details_open");
  });

  $("#lh-search").on("change, keyup", function () {
    var search_term = $(this).val();
    var $rows = $("#lh_results tr");
    $rows
      .show()
      .filter(function () {
        var text = $(this).text().replace(/\s+/g, " ").toLowerCase();
        return !~text.indexOf(search_term);
      })
      .hide();
  });

  $("#lh_subscribe_button").on("click", function () {
    if($(this).hasClass('disabled')){
        return false;
    }
    var email = $("#lh_subscribe_email").val();
    $("#lh_subscribe_button_loader").html('<img class="lh-subscribe-loader" src=' + wpel.loader + ' />');
    $('#lh_subscribe_button').addClass('disabled');
    $.ajax({
      url: wpel.lh_url,
      method: "POST",
      crossDomain: true,
      dataType: "json",
      timeout: 30000,
      data: {
        email: email,
      },
    })
      .done(function (response) {
        if (response.success == true) {
          subscribe_message(true, "<b>Thank you!</b> We'll be in touch soon!");
          $('#lh_subscribe_email').hide();
          $('#lh_subscribe_button').hide();
          $.ajax({
            url: ajaxurl,
            method: "POST",
            crossDomain: true,
            dataType: "json",
            timeout: 30000,
            data: {
              _ajax_nonce: wpel.nonce_ajax,
              action: "wpel_run_tool",
              tool: "subscribed",
            },
          }).done(function (response) {
            //
          });
        } else {
          subscribe_message(false, response.data);
        }
      })
      .fail(function (data) {
        subscribe_message(false, "An undocumented error occured.");
      })
      .always(function (data) {
        $(".lh-subscribe-loader").remove();
        $('#lh_subscribe_button').removeClass('disabled');
      });
  });

  function subscribe_message(success, message) {
    if (success) {
      $("#lh_subscribe_message").removeClass("lh-subscribe-bad");
      $("#lh_subscribe_message").addClass("lh-subscribe-good");
    } else {
      $("#lh_subscribe_message").removeClass("lh-subscribe-good");
      $("#lh_subscribe_message").addClass("lh-subscribe-bad");
    }

    $("#lh_subscribe_message").html(message).show();
  }
});

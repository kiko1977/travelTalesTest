/**
 * @file
 * Attaches behaviors for the UIkit theme.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.uikitButtonLinks = {
    attach: function () {
      var buttonLink = $('a.button');

      buttonLink.each(function () {
        var coreButton = !this.classList.contains('uk-button');
        var dangerButton = this.classList.contains('button--danger');
        var smallButton = this.classList.contains('button--small');


        if (dangerButton) {
          // Add the uk-button-danger class to button--danger links.
          $(this).addClass('uk-button uk-button-danger');
        }
        else if (coreButton) {
          // Add the uk-button class to button links.
          $(this).addClass('uk-button uk-button-default');
        }

        if (smallButton) {
          // Add the uk-button-smalll class to button--small links.
          $(this).addClass('uk-button-small');
        }
      })
    }
  };

  Drupal.behaviors.uikitComments = {
    attach: function () {
      var comments = $('.uk-comment-list');
      var permalink = comments.find(':regex(id,comment-[0-9])');

      // First move all permalinks inside their list item.
      permalink.each(function () {
        $(this).prependTo($(this).next('li'));
      });

      // Now move the indented comments into the previous list item.
      var indented = comments.find('.indented');
      var comment = '<ul>' + indented.html() + '</ul>';
      var sibling = indented.prev('li');
      $(comment).appendTo(sibling);

      // We don't want the original indented comment, so we remove it.
      $(indented).replaceWith();
    }
  }
})(jQuery);

/**
 * Regular expression selector filter.
 */
jQuery.expr[':'].regex = function(elem, index, match) {
  var matchParams = match[3].split(','),
    validLabels = /^(data|css):/,
    attr = {
      method: matchParams[0].match(validLabels) ?
        matchParams[0].split(':')[0] : 'attr',
      property: matchParams.shift().replace(validLabels,'')
    },
    regexFlags = 'ig',
    regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
  return regex.test(jQuery(elem)[attr.method](attr.property));
};;
(function ($) {
  Drupal.behaviors.datatables = {
    attach: function (context, settings) {
      $.each(settings.datatables, function (selector) {
        $(selector, context).once('datatables').each(function () {
          // Check if table contains expandable hidden rows.
          var settings = drupalSettings.datatables[selector];
          if (settings.bExpandable) {
            // Insert a "view more" column to the table.
            var nCloneTh = document.createElement('th');
            var nCloneTd = document.createElement('td');
            nCloneTd.innerHTML = '<a href="#" class="datatables-expand datatables-closed">' + Drupal.t('Show Details') + '</a>';

            $(selector + ' thead tr').each(function () {
              this.insertBefore(nCloneTh, this.childNodes[0]);
            });

            $(selector + ' tbody tr').each(function () {
              this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
            });

            settings.aoColumns.unshift({"bSortable": false});
          }

          var datatable = $(selector).dataTable(settings);

          if (settings.bExpandable) {
            // Add column headers to table settings.
            var datatables_settings = datatable.fnSettings();
            // Add blank column header for show details column.
            settings.aoColumnHeaders.unshift('');
            // Add column headers to table settings.
            datatables_settings.aoColumnHeaders = settings.aoColumnHeaders;

            /* Add event listener for opening and closing details
             * Note that the indicator for showing which row is open is not controlled by DataTables,
             * rather it is done here
             */
            $('td a.datatables-expand', datatable.fnGetNodes()).each(function () {
              $(this).click(function () {
                var row = this.parentNode.parentNode;
                if (datatable.fnIsOpen(row)) {
                  datatable.fnClose(row);
                  $(this).html(Drupal.t('Show Details'));
                }
                else {
                  datatable.fnOpen(row, Drupal.theme('datatablesExpandableRow', datatable, row), 'details');
                  $(this).html(Drupal.t('Hide Details'));
                }
                return false;
              });
            });
          }
        });
      });
    }
  };

  /**
   * Theme an expandable hidden row.
   *
   * @param object
   *   The datatable object.
   * @param array
   *   The row array for which the hidden row is being displayed.
   * @return
   *   The formatted text (html).
   */
  Drupal.theme.prototype.datatablesExpandableRow = function (datatable, row) {
    var rowData = datatable.fnGetData(row);
    var settings = datatable.fnSettings();

    var output = '<table style="padding-left: 50px">';
    $.each(rowData, function (index) {
      if (!settings.aoColumns[index].bVisible) {
        output += '<tr><td>' + settings.aoColumnHeaders[index] + '</td><td style="text-align: left">' + this + '</td></tr>';
      }
    });
    output += '</table>';
    return output;
  };
})(jQuery);
;
;

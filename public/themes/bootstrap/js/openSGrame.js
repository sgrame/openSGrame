/**
 * General javascript functionality
 */

;jQuery(document).ready(function($) {
  
  // Make the dropdowns work
  $('.dropdown-toggle').dropdown();
  
  // Tooltips
  $("[rel=tooltip]").tooltip();
  
  // Alert close
  $(".alert-message").alert();
  
  
  // Make lists sortable
  $(".list-sortable").sortable({
    placeholder: "sortable-highlight",
    forcePlaceholderSize: true,
    stop: function(event, ui) {
      ui.item.effect("highlight", {}, 1000);
      sortable_update_input(event, ui, this);
    }
  });
  $(".list-sortable").disableSelection();
  
  /**
   * Writes/updates serialized version to optional input target
   * Set the target by adding  data-target="#input-selector" to the list holder.
   * 
   * @param event
   *     The event that triggered the sortable
   * @param ui
   *     The user interface item
   * @param list
   *     The list the drop just received
   */
  function sortable_update_input(event, ui, list) {
    var target = $(list).attr("data-target");
    if (!target) {
      return;
    }
    var data = $(list).sortable("serialize");
    $(target).val(data);
  }
});



jQuery(document).ready(function(){

  (function($) {
    var CheckboxDropdown = function(el) {
      var _this = this;
      this.isOpen = false;
      this.areAllChecked = false;
      this.$el = $(el);
      this.$label = this.$el.find('.dropdown-label');
      this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
      this.$inputs = this.$el.find('[type="checkbox"]');
      
      this.onCheckBox();
      
      this.$label.on('click', function(e) {
        e.preventDefault();
        _this.toggleOpen();
      });
      
      this.$checkAll.on('click', function(e) {
        e.preventDefault();
        _this.onCheckAll();
      });
      
      this.$inputs.on('change', function(e) {
        _this.onCheckBox();
      });
    };
    
    CheckboxDropdown.prototype.onCheckBox = function() {
      this.updateStatus();
    };
    
    CheckboxDropdown.prototype.updateStatus = function() {
      var checked = this.$el.find(':checked');
      
      this.areAllChecked = false;
      this.$checkAll.html('Check All');
      
      if(checked.length <= 0) {
        this.$label.html('Select Fields');
      }
      else if(checked.length === 1) {
        this.$label.html(checked.parent('label').text());
      }
      else if(checked.length === this.$inputs.length) {
        this.$label.html('All Selected');
        this.areAllChecked = true;
        this.$checkAll.html('Uncheck All');
      }
      else {
        this.$label.html(checked.length + ' Selected');
      }
    };
    
    CheckboxDropdown.prototype.onCheckAll = function(checkAll) {
      if(!this.areAllChecked || checkAll) {
        this.areAllChecked = true;
        this.$checkAll.html('Uncheck All');
        this.$inputs.prop('checked', true);
      }
      else {
        this.areAllChecked = false;
        this.$checkAll.html('Check All');
        this.$inputs.prop('checked', false);
      }
      
      this.updateStatus();
    };
    
    CheckboxDropdown.prototype.toggleOpen = function(forceOpen) {
      var _this = this;
      
      if(!this.isOpen || forceOpen) {
         this.isOpen = true;
         this.$el.addClass('on');
        $(document).on('click', function(e) {
          if(!$(e.target).closest('[data-control]').length) {
           _this.toggleOpen();
          }
        });
      }
      else {
        this.isOpen = false;
        this.$el.removeClass('on');
        $(document).off('click');
      }
    };
    
    var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
    for(var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
      new CheckboxDropdown(checkboxesDropdowns[i]);
    }
  })(jQuery);

  jQuery('.dropdown-list input').on('click', function(){
    customfield_auto_hide_show();
   // var dataTable = jQuery('#contactsTable').DataTable();
    var ids = [];
    jQuery('.dropdown-list input').each(function(){
      if(this.checked){
        var thisval = jQuery(this).val();
        ids.push(thisval)
      }

     /* var datakey = jQuery(this).attr('data-tablekey');
      var columnIndex = dataTable.column(datakey).index();
        dataTable.column(columnIndex).visible(this.checked);*/

    });
    var checked_ids = ids.join(',');

    jQuery.ajax({
        type: 'POST',
        url: lbb_ajax.ajaxurl,
        data: {
          'action': 'lbb_save_checked_customfield_ids',
          'checked_ids': checked_ids,
        },
        success: function (response) {
          response = JSON.parse(response);
          if(response){
            jQuery('.lbb-datatable-with-column-filter .lbb-table-data').html(response);
            lbb_datatable();
          }
        } 
    });
  });
});


function customfield_auto_hide_show(){
  var dataTable = jQuery('#contactsTable').DataTable();
  jQuery('.dropdown-list input').each(function(){
      var datakey = jQuery(this).attr('data-tablekey');
      var columnIndex = dataTable.column(datakey).index();
        dataTable.column(columnIndex).visible(this.checked);
        
    });
}
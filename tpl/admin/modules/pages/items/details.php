<style>
  .label.sel {
    font-weight: bold;
  }
</style>

<div id="table"></div>

<script>
  var initGrid = function() {
    var grid = new Ngn.DdGrid.Admin({
      data: <?= json_encode($d['grid']) ?>
    });

    var filtersForm = Ngn.Form.factory($('ddFilters').getElement('form'));
    if (filtersForm) {
      filtersForm.addEvent('jsComplete', function() {
        new Ngn.DdFilterPath.Interface(grid, this);
      });
    }
    Ngn.DdFilterPath.reformat = function(s) {
      s = basename(s);
      return s.replace(/.*_(\d+)-(\d+)-(\d+)_(\d+)-(\d+)-(\d+)/, '$1.$2.$3 $4:$5');
    };
    var job = new Ngn.LongJob({
      title: 'Выгрузка',
      url: Ngn.DdFilterPath.getUrl(),
      action: 'xls',
      period: 2000,
      completeText: function(r) {
        return '<a href="' + r.data + '">' + Ngn.DdFilterPath.reformat(r.data) + '</a>'
      }
    });
    $('subNav').getElement('.xls').addEvent('click', function(e) {
      job.options.url = Ngn.DdFilterPath.getUrl();
      job.start();
    });
  };

  window.addEvent('domready', function() {
    initGrid();
  });
</script>
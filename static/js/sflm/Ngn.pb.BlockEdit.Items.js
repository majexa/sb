Ngn.pb.BlockEdit.Items = new Class({
  Extends: Ngn.pb.BlockEdit,

  init: function() {
    if (!Ngn.isAdmin) return;
    this.addEditBlockBtn({
      cls: 'list',
      caption: 'Редактировать новости'
    }, function() {
      new Ngn.Dialog.DdGrid({
        strName: 'news',
        gridOpts: {
          menu: [
            {
              title: 'Добавить запись',
              cls: 'add',
              action: function(grid) {
                new Ngn.Dialog.RequestForm({
                  id: grid.options.strName,
                  title: 'Добавление записи',
                  url: grid.options.basePath + '/json_new',
                  onSubmitSuccess: function() {
                    grid.reload();
                  }
                });
              }
            }
          ]
        }
      });
    });
  }

});
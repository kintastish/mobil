if (!RedactorPlugins) var RedactorPlugins = {};

(function ($) {
    RedactorPlugins.linkinternal = function () {
        return {
            init: function () {
                this.button.remove('link');
                var dropdown = {};
                dropdown.point1 = {title: 'Внешняя ссылка...', func: 'link.show'};
                dropdown.point2 = {title: 'Внутренняя ссылка...', func: 'linkinternal.show'};
                dropdown.point3 = {title: 'Удалить ссылку', func: 'link.unlink'};

                var button = this.button.add('linkinternal', 'Вставить ссылку...');
                this.button.addDropdown(button, dropdown);
                this.modal.addCallback('linkinternal', this.linkinternal.load);
            },
            show: function (e) {
                if (typeof e != 'undefined' && e.preventDefault) e.preventDefault();
                this.modal.addTemplate('linkinternal', this.linkinternal.getTemplate());
                this.modal.load('linkinternal', 'Внутренняя ссылка', 700);
                this.linkinternal._insertButton = this.modal.createActionButton('Выбрать');
                this.linkinternal._insertButton.bind('click', $.proxy(this.linkinternal.insert, this));

                this.modal.createCancelButton();
                this.modal.show();
                
                this.selection.get();
                this.link.getData();
                this.link.cleanUrl();
                this.linkinternal._linkTitle = $('#linkinternal-title');
                this.linkinternal._linkSelect = $('#linkinternal-items>select');
                this.linkinternal._linkUrl = $('#linkinternal-url');
                if (this.link.text != '')
                {
                    this.linkinternal._linkTitle.val( this.link.text );
                    this.linkinternal._linkTitle.addClass('protected');
                };
                this.selection.save();

                this.linkinternal._linkSelect.bind('change', function(ev){
                    var op = $('#linkinternal-items>select option:selected');
                    $('#linkinternal-url').text(op.data('url'));
                });
                
                this.linkinternal._linkSelect.bind('dblclick', $.proxy(function(){
                    var op = $('#linkinternal-items>select option:selected');
                    if (op.val() != undefined) {
                        if (!this.linkinternal._linkTitle.hasClass('protected')) {
                            this.linkinternal._linkTitle.val(op.text());
                        };
                        if (op.data('node')) {
                            this.linkinternal.appendRoute(op.val(), op.text(), op.data('url'));
                            $.get(this.opts.linkExplorer, {'node' : this.linkinternal._linkSelect.val()}, this.linkinternal.refreshSelect);
                        }
                        else {
                            
                        }
                    }
                }, this));
                
                //$('#linkinternal-items>a[class*=btn-success]').bind('click', this.linkinternal.insert);

            },
            appendRoute: function(node_id, title, url) {
                var link = $('<a href="#">' + title + '</a>');
                link.data('id', node_id);
                link.data('url', url);
                $('#linkinternal-route').append(link);
                link.bind('click', $.proxy(function(ev){
                    var node = $(ev.target).data('id');
                    this.linkinternal.loaddata(node);
                    this.linkinternal._linkUrl.text($(ev.target).data('url'));
                    var i = $(ev.target).index();
                    $('#linkinternal-route>a').slice( i+1 ).hide(400, function(){
                         $(this).remove();
                    });
                }, this));
            },
            load: function () {
                this.linkinternal.appendRoute('0', 'Главная', '/');
                this.linkinternal.loaddata(0);
            },
            loaddata: function (id) {
                if (id == undefined) id = 0;
                $.get(this.opts.linkExplorer, {'node':id}, this.linkinternal.refreshSelect);
            },
            getTemplate: function() {
                return String()
                + '<section id="redactor-modal-linkinternal">'
                + '<label>Текст ссылки</label>'
                + '<input type="text" id="linkinternal-title">'
                + '<div id="linkinternal-route"></div>'
                + '<div id="linkinternal-items">'
                + '<select size="5"></select>'
                + '</div>'
                + '<div id="linkinternal-url"></div>'
                + '</section>';
            },
            refreshSelect: function(data) {
                var select = this.linkinternal._linkSelect;
                select.empty();
                if (data.length == 0) {
                    select.append('<option value="default" disabled="disabled">Раздел пуст</option>');
                }
                else {
                    $.each(data, function (key, val) {
                        var option = $('<option value="' + val.id + '">' + val.title + '</option>');
                        if (val.node) {
                            option.addClass('node');
                        };

                        option.data('node', val.node);
                        option.data('url', val.url);
                        select.append(option);
                    });
                }
                select.trigger('change');
            },
            insert: function () {
                var link = this.linkinternal._linkUrl.text();
                var text = this.linkinternal._linkTitle.val();
                this.link.set(text, link, '');
                this.modal.close();
            }
        };
    };
})(jQuery);
CKEDITOR.editorConfig = function( config ) {
    config.toolbar = [
        { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo' ] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Glyphicons', 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Youtube', 'Slideshow' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        { name: 'document', items: [ 'Source' ] },
        '/',
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        { name: 'styles', items: [ 'Styles', 'Format' ] }
    ];
    config.extraPlugins = 'colordialog';
};

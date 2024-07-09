
CKEDITOR.plugins.add('addPersonalization', {
    // icons: 'addpersonalization', // Make sure to create an icon with this name
    onLoad: function() {
        CKEDITOR.document.appendStyleSheet(this.path + "styles.css");
    },
    init: function(editor) {
        editor.addCommand('addPersonalizationCommand', {
            exec: function(editor) {
                editor.insertText('Personalized Content');
            }
        });

        editor.ui.addButton('AddPersonalization', {
            label: 'Add Personalization',
            command: 'addPersonalizationCommand',
            toolbar: 'insert',
            // icon: this.path + 'icons/addpersonalization.png' // Path to the custom icon
        });

        editor.addContentsCss(this.path + 'styles.css');
    }
});
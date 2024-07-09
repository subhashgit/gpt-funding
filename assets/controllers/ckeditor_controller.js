import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    connect() {

        if (window.CKEDITOR) {
            CKEDITOR.replace(this.element,{
                extraPlugins: 'wordcount,notification,autogrow,askAI',
                toolbar: 'Basic',
                autoGrow_onStartup: true,
                autoGrow_maxHeight: 3000,
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] },
                    { name: 'custom', items: ['askAI'] } 
                ],
                removeButtons: 'Source,Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,HiddenField,ImageButton,Find,Scayt,Underline,Strike,Subscript,Superscript,CopyFormatting,RemoveFormat,Blockquote,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,BidiLtr,BidiRtl,Language,Anchor,Image,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,TextColor,Maximize,About,Styles,Font,FontSize,Format,BGColor,ShowBlocks'

            })
        } else {
            console.error('CKEditor is not loaded');
        }
    }

    disconnect() {
        // if (window.CKEDITOR && CKEDITOR.instances[this.element.id]) {
        //     CKEDITOR.instances.forEach(i=>i.destroy(true));
        // }
    }
}

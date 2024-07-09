CKEDITOR.plugins.add('askAI', {
    onLoad: function() {
        CKEDITOR.document.appendStyleSheet(this.path + "styles.css");
    },
    init: function(editor) {

        function refreshButtonState(commandName, buttonName) {
            const command = editor.getCommand(commandName);
            const button = editor.container.findOne('.cke_button__' + buttonName.toLowerCase());
            const state = editor.getSelection().getSelectedText().length > 0 ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED;

            if (command.state !== state) {
                command.setState(state);
            }

            if (button) {
                if (state === CKEDITOR.TRISTATE_OFF) {
                    button.removeClass('cke_button_disabled');
                    button.addClass('cke_button_off');
                    button.setAttribute('aria-disabled', 'false');
                } else {
                    button.removeClass('cke_button_off');
                    button.addClass('cke_button_disabled');
                    button.setAttribute('aria-disabled', 'true');
                }
            }
        }

        editor.addCommand('askAICommand', {
            exec: function(editor) {
                openAskAIPopup(editor, 'ask');
            }
        });

        editor.addCommand('improveWritingCommand', {
            exec: function(editor) {
                openAskAIPopup(editor, 'improve');
            },
            refresh: function(editor) {
                refreshButtonState('improveWritingCommand', 'ImproveWriting');
            },
            canUndo: false
        });

        editor.addCommand('shortenTextCommand', {
            exec: function(editor) {
                openAskAIPopup(editor, 'shorten');
            },
            refresh: function(editor) {
                refreshButtonState('shortenTextCommand', 'ShortenText');
            },
            canUndo: false
        });

        editor.addCommand('lengthenTextCommand', {
            exec: function(editor) {
                openAskAIPopup(editor, 'lengthen');
            },
            refresh: function(editor) {
                refreshButtonState('lengthenTextCommand', 'LengthenText');
            },
            canUndo: false
        });

        editor.ui.addButton('AskAI', {
            label: 'Ask AI',
            command: 'askAICommand',
            toolbar: 'custom',
        });

        editor.ui.addButton('ImproveWriting', {
            label: 'Improve Writing',
            command: 'improveWritingCommand',
            toolbar: 'custom',
            state: CKEDITOR.TRISTATE_DISABLED
        });

        editor.ui.addButton('ShortenText', {
            label: 'Shorten Text',
            command: 'shortenTextCommand',
            toolbar: 'custom',
            state: CKEDITOR.TRISTATE_DISABLED
        });

        editor.ui.addButton('LengthenText', {
            label: 'Lengthen Text',
            command: 'lengthenTextCommand',
            toolbar: 'custom',
            state: CKEDITOR.TRISTATE_DISABLED
        });

        // Refresh button states on instance ready
        editor.on('instanceReady', function() {
            editor.getCommand('improveWritingCommand').refresh(editor);
            editor.getCommand('shortenTextCommand').refresh(editor);
            editor.getCommand('lengthenTextCommand').refresh(editor);
        });

        // Ensure content is fully loaded
        editor.on('contentDom', function() {
            const editable = editor.editable();
            editable.attachListener(editable, 'keyup', function() {
                setTimeout(function() {
                    editor.getCommand('improveWritingCommand').refresh(editor);
                    editor.getCommand('shortenTextCommand').refresh(editor);
                    editor.getCommand('lengthenTextCommand').refresh(editor);
                }, 100); // Delay to ensure selection is processed
            });
            editable.attachListener(editable, 'mouseup', function() {
                setTimeout(function() {
                    editor.getCommand('improveWritingCommand').refresh(editor);
                    editor.getCommand('shortenTextCommand').refresh(editor);
                    editor.getCommand('lengthenTextCommand').refresh(editor);
                }, 100); // Delay to ensure selection is processed
            });
        });

        // Refresh button states on selection change
        editor.on('selectionChange', function() {
            setTimeout(function() {
                editor.getCommand('improveWritingCommand').refresh(editor);
                editor.getCommand('shortenTextCommand').refresh(editor);
                editor.getCommand('lengthenTextCommand').refresh(editor);
            }, 100); // Delay to ensure selection is processed
        });
    }
});

// Function to open a popup for Ask AI
function openAskAIPopup(editor, mode) {
    var existingPopup = document.getElementById('askAIPopup');
    if (existingPopup) {
        existingPopup.remove();
    }

    var selectedText = editor.getSelection().getSelectedText();
    var placeholderText = 'Type your question...';
    var actionButtonText = 'Submit';
    var textareaContent = '';

    switch (mode) {
        case 'ask':
            placeholderText = 'Type your question...';
            actionButtonText = 'Submit';
            textareaContent = '';
            break;
        case 'improve':
            placeholderText = 'Selected text will be improved...';
            actionButtonText = 'Improve';
            textareaContent = selectedText;
            break;
        case 'shorten':
            placeholderText = 'Selected text will be shortened...';
            actionButtonText = 'Shorten';
            textareaContent = selectedText;
            break;
        case 'lengthen':
            placeholderText = 'Selected text will be lengthened...';
            actionButtonText = 'Lengthen';
            textareaContent = selectedText;
            break;
    }

    var popupHtml = `
        <div id="askAIBackdrop" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9998;"></div>
        <div id="askAIPopup" style="position:fixed;top:20%;left:50%;transform:translate(-50%, -20%);background:white;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,0.1);z-index:9999;">
            <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-gray-200">Ask AI</h3>
            <textarea id="aiInput" rows="4" cols="50" placeholder="${placeholderText}">${textareaContent}</textarea>
            <br/>
            <button type="button" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800" id="askAIButton" onclick="askAIQuery('${mode}')">${actionButtonText}</button>
            <button type="button" class="py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="closeAskAIPopup()">Cancel</button>
            <div id="askAILoader" style="display:none;"><i class="fa-solid fa-spinner"></i></div>
            <div id="aiResponse" style="display:none; margin-top: 10px;">
                <textarea id="aiOutput" rows="4" cols="50" readonly></textarea>
                <br/>
                <button type="button" class="py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600" onclick="saveAIResponse()">Save</button>
                <button type="button" class="py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="copyAIResponse()">Copy</button>
            </div>
        </div>
    `;
    var popupContainer = document.createElement('div');
    popupContainer.innerHTML = popupHtml;
    document.body.appendChild(popupContainer);

    var aiInput = document.getElementById('aiInput');
    var askAIButton = document.getElementById('askAIButton');

    aiInput.addEventListener('input', function() {
        askAIButton.disabled = aiInput.value.trim() === '';
    });

    window.askAIQuery = function(mode) {
        var query = document.getElementById('aiInput').value;
        var loader = document.getElementById('askAILoader');
        var button = document.getElementById('askAIButton');

        // Show loader and disable button
        loader.style.display = 'block';
        button.disabled = true;

        // Perform the API call (replace with your actual API endpoint)
        fetch('/ask-ai', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: query, mode: mode })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data && data.data) {
                var aiOutput = document.getElementById('aiOutput');
                var aiResponse = document.getElementById('aiResponse');
                aiOutput.value = data.data;
                aiResponse.style.display = 'block';
                // var range = editor.getSelection().getRanges()[0];
                // range.deleteContents();
                // range.insertNode(new CKEDITOR.dom.text(data.data));
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Hide loader and enable button
            loader.style.display = 'none';
            button.disabled = false;
        });
    };

    window.saveAIResponse = function() {
        var aiOutput = document.getElementById('aiOutput').value;
        if (aiOutput.trim() !== '') {
            editor.insertText(aiOutput);
            closeAskAIPopup();
        }
    };

    window.copyAIResponse = function() {
        var aiOutput = document.getElementById('aiOutput');
        aiOutput.select();
        document.execCommand('copy');
    };

    window.closeAskAIPopup = function() {
        var popup = document.getElementById('askAIPopup');
        var backdrop = document.getElementById('askAIBackdrop');
        if (popup) {
            popup.remove();
        }
        if (backdrop) {
            backdrop.remove();
        }
    };
}
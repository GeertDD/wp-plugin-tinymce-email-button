(function () {

	tinymce.create('tinymce.plugins.email', {

		init: function (editor, url) {

			editor.addButton('email', {
				cmd: 'email'
				,title: tinymce_email_button.title
				,image: tinymce_email_button.url + 'images/email-button.png'
			});

			editor.addCommand('email', function() {
				// Grab the selected text
				var linkText = editor.selection.getContent({ format:'text' });

				// Look for an email in the selection and use that email as default input in the prompt
				var found = linkText.match(/\S+@\S+\.[a-z]+/i);
				var defaultInput = (found) ? found[0] : '';

				// Ask for the email address to link to
				var email = prompt(tinymce_email_button.prompt, defaultInput);

				// If nothing was entered, don't create a link
				if ( ! email) return;

				// If no text was selected, use the email address as text for the link
				if ('' === linkText) {
					linkText = email;
				}

				// Create and insert the email link
				var newText = '<a href="mailto:' + email + '">' + linkText + '</a>';
				editor.execCommand('mceInsertContent', false, newText);
			});

			editor.onNodeChange.add(function (ed, cm, el) { // cm = ControlManager
				// Reset email button status
				cm.setActive('email', false);
				cm.setDisabled('email', false);

				if ('A' === el.nodeName) {
					var href = el.href || '';
					if (href.match(/^mailto:/i)) {
						// Enable the button when an email link is selected
						cm.setActive('email', true);
					} else {
						// Disable the button if a regular link is selected
						cm.setDisabled('email', true);
					}
				}
			});
		}

	});

	tinymce.PluginManager.add('email', tinymce.plugins.email);

}());
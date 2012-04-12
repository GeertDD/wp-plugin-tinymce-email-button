;(function () {

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

				// If an email address was selected, use that email as default input in the prompt
				var defaultInput = (linkText.match(/^\S+@\S+\.\S+$/)) ? linkText : '';

				// Ask for the email address to link to
				var email = prompt(tinymce_email_button.prompt, defaultInput);

				// If nothing was entered, don't create a link
				if ( ! email) return;

				// If no text was selected, use the email address as text for the link
				if (linkText === '') {
					linkText = email;
				}

				// Create and insert the email link
				var newText = '<a href="mailto:' + email + '">' + linkText + '</a>';
				editor.execCommand('mceInsertContent', false, newText);
			});
		}

	});

	tinymce.PluginManager.add('email', tinymce.plugins.email);

}());
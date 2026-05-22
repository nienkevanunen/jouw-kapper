jQuery(function($) {
  "use strict";

  var emailPattern = /^[^\s()<>@,;:/]+@\w[\w.-]+\.[a-z]{2,}$/i;
  var defaultAction = 'https://api.web3forms.com/submit';
  var defaultAccessKey = '5a94db67-d2f2-4f9f-82d9-c00ff4adaee0';

  function parseRule(rule) {
    var parts = String(rule || '').split(':');

    return {
      name: parts[0],
      value: parts.slice(1).join(':')
    };
  }

  function isFieldInvalid($field, parsedRule) {
    var value = $.trim($field.val());

    switch (parsedRule.name) {
      case 'required':
        return value === '';

      case 'minlen':
        return value.length < parseInt(parsedRule.value, 10);

      case 'email':
        return !emailPattern.test(value);

      case 'checked':
        return !$field.is(':checked');

      case 'regexp':
        return !(new RegExp(parsedRule.value)).test(value);

      default:
        return false;
    }
  }

  function validateField() {
    var $field = $(this);
    var rule = $field.attr('data-rule');
    var $validation = $field.next('.validation');
    var invalid = false;

    if (rule) {
      invalid = isFieldInvalid($field, parseRule(rule));
    }

    $validation
      .text(invalid ? ($field.attr('data-msg') || 'Controleer dit veld') : '')
      .toggle(invalid);

    return !invalid;
  }

  function validateForm($form) {
    var isValid = true;

    $form.find('input, textarea').each(function() {
      if (!validateField.call(this)) {
        isValid = false;
      }
    });

    return isValid;
  }

  function setFormState($button, originalText, isSubmitting) {
    $button
      .text(isSubmitting ? 'Versturen...' : originalText)
      .prop('disabled', isSubmitting);
  }

  $('form.contactForm').on('submit', function(event) {
    event.preventDefault();

    var formElement = this;
    var $form = $(formElement);
    var $submitButton = $form.find('button[type="submit"]');
    var originalButtonText = $submitButton.text();
    var $successMessage = $('#sendmessage');
    var $errorMessage = $('#errormessage');

    if (!validateForm($form)) {
      return false;
    }

    var formData = new FormData(formElement);
    if (!formData.has('access_key')) {
      formData.append('access_key', defaultAccessKey);
    }

    setFormState($submitButton, originalButtonText, true);
    $successMessage.removeClass('show');
    $errorMessage.removeClass('show').text('');

    fetch($form.attr('action') || defaultAction, {
      method: 'POST',
      body: formData
    })
      .then(function(response) {
        return response.json().then(function(data) {
          if (!response.ok || data.success === false) {
            throw new Error(data.message || 'Het bericht kon niet worden verzonden.');
          }

          $successMessage.addClass('show');
          $errorMessage.removeClass('show').text('');
          $form.find("input:not([type='hidden']), textarea").val('');
        });
      })
      .catch(function(error) {
        $successMessage.removeClass('show');
        $errorMessage
          .text(error.message || 'Er ging iets mis. Probeer het opnieuw.')
          .addClass('show');
      })
      .finally(function() {
        setFormState($submitButton, originalButtonText, false);
      });

    return false;
  });

  $('form.contactForm').on('input change', 'input, textarea', validateField);
});

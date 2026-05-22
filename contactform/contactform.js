jQuery(document).ready(function($) {
  "use strict";

  //Contact
  $('form.contactForm').submit(function() {
    var f = $(this).find('.form-group'),
      ferror = false,
      emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

    f.children('input').each(function() { // run all inputs

      var i = $(this); // current input
      var rule = i.attr('data-rule');

      if (rule !== undefined) {
        var ierror = false; // error flag for current input
        var pos = rule.indexOf(':', 0);
        if (pos >= 0) {
          var exp = rule.substr(pos + 1, rule.length);
          rule = rule.substr(0, pos);
        } else {
          rule = rule.substr(pos + 1, rule.length);
        }

        switch (rule) {
          case 'required':
            if (i.val() === '') {
              ferror = ierror = true;
            }
            break;

          case 'minlen':
            if (i.val().length < parseInt(exp)) {
              ferror = ierror = true;
            }
            break;

          case 'email':
            if (!emailExp.test(i.val())) {
              ferror = ierror = true;
            }
            break;

          case 'checked':
            if (! i.is(':checked')) {
              ferror = ierror = true;
            }
            break;

          case 'regexp':
            exp = new RegExp(exp);
            if (!exp.test(i.val())) {
              ferror = ierror = true;
            }
            break;
        }
        i.next('.validation').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
      }
    });
    f.children('textarea').each(function() { // run all inputs

      var i = $(this); // current input
      var rule = i.attr('data-rule');

      if (rule !== undefined) {
        var ierror = false; // error flag for current input
        var pos = rule.indexOf(':', 0);
        if (pos >= 0) {
          var exp = rule.substr(pos + 1, rule.length);
          rule = rule.substr(0, pos);
        } else {
          rule = rule.substr(pos + 1, rule.length);
        }

        switch (rule) {
          case 'required':
            if (i.val() === '') {
              ferror = ierror = true;
            }
            break;

          case 'minlen':
            if (i.val().length < parseInt(exp)) {
              ferror = ierror = true;
            }
            break;
        }
        i.next('.validation').html((ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
      }
    });
    if (ferror) return false;

    var form = $(this);
    var action = form.attr('action') || 'https://api.web3forms.com/submit';
    var submitButton = form.find('button[type="submit"]');
    var originalButtonText = submitButton.text();
    var formData = new FormData(this);

    if (!formData.has('access_key')) {
      formData.append('access_key', '5a94db67-d2f2-4f9f-82d9-c00ff4adaee0');
    }

    submitButton.text('Versturen...').prop('disabled', true);
    $("#sendmessage").removeClass("show");
    $("#errormessage").removeClass("show");

    fetch(action, {
      method: "POST",
      body: formData
    })
      .then(function(response) {
        return response.json().then(function(data) {
          if (!response.ok || data.success === false) {
            throw new Error(data.message || 'Het bericht kon niet worden verzonden.');
          }

          $("#sendmessage").addClass("show");
          $("#errormessage").removeClass("show");
          form.find("input:not([type='hidden']), textarea").val("");
        });
      })
      .catch(function(error) {
        $("#sendmessage").removeClass("show");
        $("#errormessage").addClass("show");
        $('#errormessage').html(error.message || 'Er ging iets mis. Probeer het opnieuw.');
      })
      .finally(function() {
        submitButton.text(originalButtonText).prop('disabled', false);
      });

    return false;
  });

});

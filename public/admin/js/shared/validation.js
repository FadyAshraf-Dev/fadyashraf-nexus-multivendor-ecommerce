let validationStarted = false;
/* =====================================================
   Container Validation
===================================================== */

function validateContainer(container) {
  const fields = container.querySelectorAll("input, select, textarea");

  let valid = true;
  let firstInvalid = null;

  fields.forEach(function (field) {
    if (field.disabled) {
      return;
    }

    if (field.type === "file") {
      if (field.required && field.files.length === 0) {
        showFeedback(field, field.validationMessage);
        valid = false;

        if (!firstInvalid) {
          firstInvalid = field;
        }
      } else {
        clearFeedback(field);
      }

      return;
    }

    if (!field.checkValidity()) {
      showFeedback(field, field.validationMessage);
      valid = false;

      if (!firstInvalid) {
        firstInvalid = field;
      }
    } else {
      clearFeedback(field);
    }
  });

  if (firstInvalid) {
    firstInvalid.focus();
  }

  return valid;
}

/* =====================================================
   Live Validation
===================================================== */

function initializeFormValidation(form) {
  form.querySelectorAll("input, select, textarea").forEach(function (field) {
    field.addEventListener("input", function () {
      if (field.type === "file" || !isValidationStarted()) {
        return;
      }

      if (field.checkValidity()) {
        clearFeedback(field);
      } else {
        showFeedback(field, field.validationMessage);
      }
    });
    field.addEventListener("change", function () {
      if (!isValidationStarted()) {
        return;
      }

      if (field.type === "file") {
        if (field.checkValidity()) {
          clearFeedback(field);
        } else {
          showFeedback(field, field.validationMessage);
        }
        return;
      }

      if (field.checkValidity()) {
        clearFeedback(field);
      } else  {
        showFeedback(field, field.validationMessage);
      }
    });
  });
}
/* =====================================================
   Helpers
===================================================== */
function startValidation() {
  validationStarted = true;
}

function isValidationStarted() {
  return validationStarted;
}

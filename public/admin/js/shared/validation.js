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
        showValidation(field, field.validationMessage);
        valid = false;

        if (!firstInvalid) {
          firstInvalid = field;
        }
      } else {
        clearValidation(field);
      }

      return;
    }

    if (!field.checkValidity()) {
      showValidation(field, field.validationMessage);
      valid = false;

      if (!firstInvalid) {
        firstInvalid = field;
      }
    } else {
      clearValidation(field);
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
        clearValidation(field);
      } else {
        showValidation(field, field.validationMessage);
      }
    });
    field.addEventListener("change", function () {
      if (!isValidationStarted()) {
        return;
      }

      if (field.type === "file") {
        if (field.checkValidity()) {
          clearValidation(field);
        } else {
          showValidation(field, field.validationMessage);
        }
        return;
      }

      if (field.checkValidity()) {
        clearValidation(field);
      } else  {
        showValidation(field, field.validationMessage);
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
function showValidation(field, message="") {
  field.classList.add("is-invalid");

  const feedback = field.parentElement.querySelector(".invalid-feedback");

  if (!feedback) {
    return;
  }

  feedback.textContent = message;
}
function clearValidation(field) {
  field.classList.remove("is-invalid");

  const feedback = field.parentElement.querySelector(".invalid-feedback");

  if (!feedback) {
    return;
  }

  feedback.textContent = "";
}

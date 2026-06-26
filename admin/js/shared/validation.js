/* =====================================================
   Container Validation
===================================================== */

function validateContainer(form) {

    const fields = form.querySelectorAll("input, select, textarea");

    let valid = true;
    let firstInvalid = null;

    fields.forEach(function (field) {

        if (field.disabled) {
            return;
        }

        if (field.type === "file") {

            if (field.required && field.files.length === 0) {

                field.classList.add("is-invalid");

                if (!firstInvalid) {
                    firstInvalid = field;
                }

                valid = false;
            }
            else {

                field.classList.remove("is-invalid");

            }

            return;
        }

        if (!field.checkValidity()) {

            field.classList.add("is-invalid");

            if (!firstInvalid) {
                firstInvalid = field;
            }

            valid = false;

        }
        else {

            field.classList.remove("is-invalid");

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

            if (field.type === "file") {
                return;
            }

            if (field.checkValidity()) {

                field.classList.remove("is-invalid");
            }
            else{
                field.classList.add("is-invalid");
            }

        });

        field.addEventListener("change", function () {

            if (field.type === "file") {

                if (field.files.length > 0) {
                    field.classList.remove("is-invalid");
                }

                return;
            }

            if (field.checkValidity()) {
                field.classList.remove("is-invalid");
            }

        });

    });

}

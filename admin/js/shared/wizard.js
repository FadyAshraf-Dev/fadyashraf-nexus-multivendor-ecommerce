/* =====================================================
   Wizard Navigation
===================================================== */

function initializeWizardNavigation(dom) {

    document.querySelectorAll(".btn-wizard-next").forEach(function (button) {

        button.addEventListener("click", function () {

            const currentStep = button.closest(".tab-pane");

            if (!validateContainer(currentStep)) {
                return;
            }

            const targetTab = document.querySelector(button.dataset.next);

            if (!targetTab) {
                return;
            }

            bootstrap.Tab.getOrCreateInstance(targetTab).show();

        });

    });

    document.querySelectorAll(".btn-wizard-prev").forEach(function (button) {

        button.addEventListener("click", function () {

            const targetTab = document.querySelector(button.dataset.prev);

            if (!targetTab) {
                return;
            }

            bootstrap.Tab.getOrCreateInstance(targetTab).show();

        });

    });

}

/* =====================================================
   Form Submission
===================================================== */

function initializeFormSubmission(dom) {

    if (!dom.form) {
        return;
    }

    dom.form.addEventListener("submit", function (event) {

        const lastStep = document.getElementById("step4");

        if (!validateContainer(lastStep)) {

            event.preventDefault();

        }

    });

}






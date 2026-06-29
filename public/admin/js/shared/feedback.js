function showFeedback(field, message="") {
  field.classList.add("is-invalid");

  const feedback = field.parentElement.querySelector(".invalid-feedback");

  if (!feedback) {
    return;
  }

  feedback.textContent = message;
}
function clearFeedback(field) {
  field.classList.remove("is-invalid");

  const feedback = field.parentElement.querySelector(".invalid-feedback");

  if (!feedback) {
    return;
  }

  feedback.textContent = "";
}

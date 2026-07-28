function checkEmail() {
  const email = document.getElementById("email");
  const button = document.getElementById("button");
  if(email.value && email.value.length) {
    button.disabled = false;
  } else {
    button.disabled = true;
  }
}
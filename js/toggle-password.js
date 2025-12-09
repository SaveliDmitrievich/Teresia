
function togglePassword(icon, inputId) {
  const input = document.getElementById(inputId);
  if (!input) return;

  if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("bx-hide");
      icon.classList.add("bx-show");
  } else {
      input.type = "password";
      icon.classList.remove("bx-show");
      icon.classList.add("bx-hide");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.form-row-wrapper').forEach(wrapper => {
      const input = wrapper.querySelector('input[type="password"], input[type="text"]');
      const icon = wrapper.querySelector('.toggle-password');

      if (!input || !icon) return;
      icon.style.display = input.value ? 'block' : 'none';

      input.addEventListener('input', () => {
          icon.style.display = input.value ? 'block' : 'none';
      });
  });
});

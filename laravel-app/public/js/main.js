document.addEventListener('DOMContentLoaded', () => {
  const roleSelect = document.querySelector('#roleSelect');
  const pharmacyFields = document.querySelector('#pharmacyFields');
  if (roleSelect && pharmacyFields) {
    const toggle = () => {
      const show = roleSelect.value === 'pharmacist';
      pharmacyFields.classList.toggle('d-none', !show);
      pharmacyFields.querySelectorAll('input').forEach(inp => inp.required = show);
    };
    roleSelect.addEventListener('change', toggle);
    toggle();
  }
});

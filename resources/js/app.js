import './bootstrap';

/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;

// Reinitialize flatpickr every time the modal is shown
const modal = document.getElementById('modalCompanyDoc');

if (modal) {
  modal.addEventListener('shown.bs.modal', () => {
    const dateInputs = modal.querySelectorAll('.flatpickr-date');

    dateInputs.forEach(input => {
      // Destroy any existing instance before initializing a new one
      if (input._flatpickr) {
        input._flatpickr.destroy();
      }

      flatpickr(input, {
        monthSelectorType: 'static',
        static: true,
        dateFormat: 'Y-m-d'
      });
    });
  });
}
window.renderArabicDate = function (value, fallback = '—') {
  if (!value) return fallback;

  const date = new Date(value);
  return isNaN(date.getTime()) ? fallback : date.toLocaleDateString('ar-EG');
};

document.addEventListener('DOMContentLoaded', showExpiringToasts);

function showExpiringToasts() {
  fetch('/expiring-toast')
    .then(res => res.json())
    .then(data => {
      if (!Array.isArray(data)) return;

      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: toast => {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
      });

      data.forEach(doc => {
        Toast.fire({
          icon: 'warning',
          title: `المستند "${doc.name}" ينتهي بتاريخ ${doc.renewal_date}`
        });
      });
    })
    .catch(err => {
      console.error('فشل في تحميل التنبيهات:', err);
    });
}

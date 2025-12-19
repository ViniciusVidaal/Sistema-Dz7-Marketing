document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarOverlay = document.getElementById('sidebarOverlay');

  const closeSidebar = () => {
    if (!sidebar) return;
    sidebar.classList.add('-translate-x-full');
    if (sidebarOverlay) {
      sidebarOverlay.classList.add('hidden');
    }
  };

  const openSidebar = () => {
    if (!sidebar) return;
    sidebar.classList.remove('-translate-x-full');
    if (sidebarOverlay) {
      sidebarOverlay.classList.remove('hidden');
    }
  };

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
      if (sidebar.classList.contains('-translate-x-full')) {
        openSidebar();
      } else {
        closeSidebar();
      }
    });
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }
  const paymentSelect = document.querySelector('[data-payment-type]');
  const recurringFields = document.querySelectorAll('[data-recurring-field]');

  const toggleRecurring = () => {
    if (!paymentSelect) return;
    const show = paymentSelect.value === 'RECURRING';
    recurringFields.forEach((el) => {
      el.classList.toggle('hidden', !show);
    });
  };

  if (paymentSelect) {
    paymentSelect.addEventListener('change', toggleRecurring);
    toggleRecurring();
  }

  const revenueChartEl = document.getElementById('revenueChart');
  if (revenueChartEl && window.Chart) {
    const series = JSON.parse(revenueChartEl.getAttribute('data-series') || '[]');
    const labels = series.map((item) => item.month);
    const values = series.map((item) => item.total);

    new Chart(revenueChartEl, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Faturamento',
          data: values,
          borderColor: '#2d6bff',
          backgroundColor: 'rgba(45,107,255,0.2)',
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { ticks: { color: '#cbd5f5' }, grid: { color: 'rgba(148,163,184,0.15)' } },
          y: { ticks: { color: '#cbd5f5' }, grid: { color: 'rgba(148,163,184,0.15)' } }
        }
      }
    });
  }

  const spendChartEl = document.getElementById('spendChart');
  if (spendChartEl && window.Chart) {
    const meta = parseFloat(spendChartEl.getAttribute('data-meta') || '0');
    const google = parseFloat(spendChartEl.getAttribute('data-google') || '0');
    const misc = parseFloat(spendChartEl.getAttribute('data-misc') || '0');

    new Chart(spendChartEl, {
      type: 'doughnut',
      data: {
        labels: ['Meta Ads', 'Google Ads', 'Diversos'],
        datasets: [{
          data: [meta, google, misc],
          backgroundColor: ['#2d6bff', '#1f52d6', '#334155'],
          borderWidth: 0,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#cbd5f5' } } }
      }
    });
  }
});

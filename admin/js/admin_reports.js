document.addEventListener('DOMContentLoaded', function() {
  const startDateInput = document.getElementById('startDate');
  const endDateInput = document.getElementById('endDate');
  const generateReportBtn = document.getElementById('generateReportBtn');
  const exportCsvBtn = document.getElementById('exportCsvBtn');
  const ordersReportBody = document.getElementById('ordersReportBody');
  const reportTitle = document.getElementById('reportTitle');

  const today = new Date();
  const lastMonth = new Date();
  lastMonth.setMonth(today.getMonth() - 1);

  startDateInput.value = lastMonth.toISOString().split('T')[0];
  endDateInput.value = today.toISOString().split('T')[0];

  generateReportBtn.addEventListener('click', function() {
      const startDate = startDateInput.value;
      const endDate = endDateInput.value;

      if (!startDate || !endDate) {
          alert('Пожалуйста, выберите обе даты для формирования отчета.');
          return;
      }

      reportTitle.textContent = `Отчет по заказам за период с ${startDate} по ${endDate}`;
      ordersReportBody.innerHTML = '<tr><td colspan="8">Загрузка отчета...</td></tr>';
      exportCsvBtn.style.display = 'none';

      fetch(`api/reports.php?start_date=${startDate}&end_date=${endDate}`)
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  ordersReportBody.innerHTML = '';

                  if (data.orders.length > 0) {
                      data.orders.forEach(order => {
                          const row = ordersReportBody.insertRow();
                          row.innerHTML = `
                              <td>${order.id_order}</td>
                              <td>${new Date(order.order_date).toLocaleString('ru-RU')}</td>
                              <td>${htmlspecialchars(order.fullname)}</td>
                              <td>${htmlspecialchars(order.phone)}</td>
                              <td>${htmlspecialchars(order.address)}</td>
                              <td>${htmlspecialchars(order.comment || 'Нет')}</td>
                              <td>${order.items_formatted}</td>
                              <td>${parseFloat(order.total_price).toFixed(2)} BYN</td>
                          `;
                      });
                      exportCsvBtn.style.display = 'inline-flex'; 
                  } else {
                      ordersReportBody.innerHTML = '<tr><td colspan="8">Заказы за выбранный период не найдены.</td></tr>';
                  }
              } else {
                  alert('Ошибка при формировании отчета: ' + data.message);
                  ordersReportBody.innerHTML = '<tr><td colspan="8">Ошибка при загрузке отчета.</td></tr>';
              }
          })
          .catch(error => {
              console.error('Ошибка:', error);
              alert('Ошибка сети при формировании отчета.');
              ordersReportBody.innerHTML = '<tr><td colspan="8">Ошибка сети при загрузке отчета.</td></tr>';
          });
  });

  function htmlspecialchars(str) {
      let div = document.createElement('div');
      div.appendChild(document.createTextNode(str));
      return div.innerHTML;
  }

  exportCsvBtn.addEventListener('click', function() {
      const table = document.getElementById('ordersReportTable');
      let csv = [];
      const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
      csv.push(headers.join(';'));

      table.querySelectorAll('tbody tr').forEach(row => {
          const rowData = Array.from(row.querySelectorAll('td')).map(td => {
              let text = td.textContent.trim();
              if (text.includes(';') || text.includes('\n') || text.includes('"')) {
                  text = '"' + text.replace(/"/g, '""') + '"';
              }
              return text;
          });
          csv.push(rowData.join(';'));
      });

      const csvString = csv.join('\n');
      const blob = new Blob(["\uFEFF" + csvString], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `orders_report_${startDateInput.value}_to_${endDateInput.value}.csv`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
  });

});
(() => {
  const source = document.getElementById('admin-chart-data');
  const chart = document.getElementById('admin-overview-chart');
  if (!source || !chart) return;
  const data = JSON.parse(source.textContent);
  const metricSelect = document.getElementById('admin-chart-metric');
  const readout = document.getElementById('admin-chart-readout');
  const metricNames = { reservations: 'Reservations', users: 'New users', pharmacies: 'New pharmacies' };
  const periodNames = { week: 'this week', month: 'this month', year: 'this year' };
  let period = 'week';
  const svgElement = (name, attributes = {}, text) => {
    const node = document.createElementNS('http://www.w3.org/2000/svg', name);
    Object.entries(attributes).forEach(([key, value]) => node.setAttribute(key, value));
    if (text !== undefined) node.textContent = text;
    return node;
  };
  function render() {
    const metric = metricSelect.value;
    const current = data[period];
    const values = current.series[metric];
    const total = values.reduce((sum, value) => sum + value, 0);
    const name = metricNames[metric];
    document.getElementById('admin-chart-total').textContent = `${total.toLocaleString()} ${name.toLowerCase()} ${periodNames[period]}`;
    chart.setAttribute('aria-label', `${name} ${periodNames[period]}: ${total} total. Use the chart data table for all values.`);
    readout.textContent = total === 0 ? `No ${name.toLowerCase()} recorded ${periodNames[period]}.` : 'Hover over or focus a point to see its count.';
    chart.replaceChildren();
    const width = Math.max(320, Math.round(chart.getBoundingClientRect().width) || 760);
    chart.setAttribute('viewBox', `0 0 ${width} 265`);
    const left = 44, right = width - 18, top = 16, bottom = 227;
    const max = Math.max(...values, 1);
    const step = Math.max(1, Math.ceil(max / 4));
    const ceiling = step * 4;
    const x = index => left + index * (right - left) / Math.max(1, values.length - 1);
    const y = value => bottom - (value / ceiling) * (bottom - top);
    const defs = svgElement('defs');
    const gradient = svgElement('linearGradient', { id: 'admin-chart-fill', x1: '0', y1: '0', x2: '0', y2: '1' });
    gradient.append(svgElement('stop', { offset: '0%', 'stop-color': '#0d6efd', 'stop-opacity': '.22' }), svgElement('stop', { offset: '100%', 'stop-color': '#0d6efd', 'stop-opacity': '.02' }));
    defs.append(gradient); chart.append(defs);
    for (let i = 0; i <= 4; i++) {
      const value = i * step;
      chart.append(svgElement('line', { x1: left, x2: right, y1: y(value), y2: y(value), stroke: '#edf0f8' }));
      chart.append(svgElement('text', { x: left - 10, y: y(value) + 4, 'text-anchor': 'end', fill: '#7d8da9', 'font-size': '11' }, value.toLocaleString()));
    }
    const labelInterval = Math.ceil(values.length / Math.max(4, Math.floor((right - left) / 40)));
    values.forEach((value, index) => {
      if (index % labelInterval === 0) {
        chart.append(svgElement('line', { x1: x(index), x2: x(index), y1: top, y2: bottom, stroke: '#f2f4fa' }));
        chart.append(svgElement('text', { x: x(index), y: 250, 'text-anchor': 'middle', fill: '#627493', 'font-size': '11' }, current.labels[index]));
      }
    });
    const points = values.map((value, index) => `${x(index)},${y(value)}`).join(' ');
    chart.append(svgElement('polygon', { points: `${left},${bottom} ${points} ${right},${bottom}`, fill: 'url(#admin-chart-fill)' }));
    chart.append(svgElement('polyline', { points, fill: 'none', stroke: '#0d6efd', 'stroke-width': '2.5', 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }));
    values.forEach((value, index) => {
      const description = `${current.dates[index]}: ${value} ${name.toLowerCase()}`;
      const point = svgElement('circle', { cx: x(index), cy: y(value), r: '4', fill: '#0d6efd', stroke: '#fff', 'stroke-width': '1.5', tabindex: '0', class: 'admin-chart-point', 'aria-label': description });
      point.append(svgElement('title', {}, description));
      ['mouseenter', 'focus', 'click'].forEach(event => point.addEventListener(event, () => { readout.textContent = description; }));
      chart.append(point);
    });
    document.getElementById('admin-chart-table-heading').textContent = name;
    const table = document.getElementById('admin-chart-table-body');
    table.replaceChildren();
    values.forEach((value, index) => {
      const row = document.createElement('tr');
      [current.dates[index], value].forEach(text => { const cell = document.createElement('td'); cell.textContent = text; row.append(cell); });
      table.append(row);
    });
  }
  document.querySelectorAll('[data-admin-period]').forEach(button => {
    button.addEventListener('click', () => {
      period = button.dataset.adminPeriod;
      document.querySelectorAll('[data-admin-period]').forEach(item => {
        const active = item === button;
        item.classList.toggle('active', active);
        item.setAttribute('aria-pressed', String(active));
      });
      render();
    });
  });
  metricSelect.addEventListener('change', render);
  render();
  let previousWidth = chart.getBoundingClientRect().width;
  const resize = () => {
    const width = chart.getBoundingClientRect().width;
    if (Math.abs(width - previousWidth) < 1) return;
    previousWidth = width;
    render();
  };
  if (typeof ResizeObserver !== 'undefined') new ResizeObserver(resize).observe(chart);
  else window.addEventListener('resize', resize);
})();

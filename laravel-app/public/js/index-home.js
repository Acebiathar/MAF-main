window.addEventListener('load', () => {
    const spinner = document.getElementById('spinnerOverlay');
    if (spinner) {
        spinner.style.opacity = '0';
        setTimeout(() => spinner.remove(), 500);
    }

    // Defer heavy animations for better initial load
    setTimeout(() => {
        animateCounters();
        loadMedicalTips();
    }, 100);
});

function animateCounters() {
    const counters = [{
        id: 'verifiedPartners',
        target: 180
    }, {
        id: 'monthlyOrders',
        target: 24500
    }, {
        id: 'customerRating',
        target: 4.9
    }];
    counters.forEach(c => {
        let current = 0;
        const el = document.getElementById(c.id);
        if (!el) return;
        const step = c.target / 40;
        const interval = setInterval(() => {
            current += step;
            if (current >= c.target) {
                el.innerText = c.id === 'customerRating' ? c.target.toFixed(1) : Math.round(c.target).toLocaleString();
                clearInterval(interval);
            } else {
                el.innerText = c.id === 'customerRating' ? current.toFixed(1) : Math.floor(current).toLocaleString();
            }
        }, 25);
    });
}

const tipsDatabase = [{
    title: "💊 Stay Hydrated with Meds",
    desc: "Take most oral medications with a full glass of water to aid absorption and reduce stomach irritation."
}, {
    title: "⏰ Set Medicine Reminders",
    desc: "Use alarms or MedFinder's upcoming reminder feature to never miss a dose — consistency saves lives."
}, {
    title: "🌿 Antibiotics Course",
    desc: "Always complete your full antibiotic course even if symptoms improve. Prevents resistance."
}, {
    title: "📦 Check Expiry Dates",
    desc: "Regularly check medicine expiry; dispose properly. Never use expired meds."
}, {
    title: "🍊 Vitamin C Timing",
    desc: "Take Vitamin C with meals to improve absorption and reduce stomach upset."
}, {
    title: "🩸 Monitor BP at Home",
    desc: "Regular home BP monitoring helps adjust medication early. Keep a log for your doctor."
}];

function loadMedicalTips() {
    const container = document.getElementById('medicalTipsContainer');
    if (!container) return;
    const shuffled = [...tipsDatabase].sort(() => 0.5 - Math.random());
    const selected = shuffled.slice(0, 3);
    container.innerHTML = '';
    selected.forEach((tip, idx) => {
        const col = document.createElement('div');
        col.className = 'col-md-4';
        col.innerHTML = `
            <div class="tip-card p-3 d-flex gap-3 align-items-start animate-tip" style="animation-delay: ${idx*0.1}s">
                <div class="fs-1 text-primary"><i class="fas fa-notes-medical"></i></div>
                <div><h5 class="fw-bold">${tip.title}</h5><p class="text-muted small mb-0">${tip.desc}</p></div>
            </div>
        `;
        container.appendChild(col);
    });
    document.querySelectorAll('.tip-card').forEach(card => {
        card.classList.add('animate-tip');
    });
}

document.getElementById('refreshTipsBtn')?.addEventListener('click', () => {
    loadMedicalTips();
    showFloatingToast('New health tips loaded!', 'info');
});

function performHeroSearch() {
    const query = document.getElementById('heroSearchInput')?.value.trim();
    if (!query) {
        showFloatingToast('Enter medicine name', 'warning');
        return;
    }
    window.location.href = "/?search=" + encodeURIComponent(query);
}

document.getElementById('heroSearchBtn')?.addEventListener('click', performHeroSearch);
document.getElementById('heroSearchInput')?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') performHeroSearch();
});

function showFloatingToast(msg, type = 'info') {
    const root = document.getElementById('toastRoot');
    if (!root) return;
    const toastDiv = document.createElement('div');
    toastDiv.className = `toast align-items-center text-white bg-${type === 'warning' ? 'warning' : 'primary'} border-0 mb-2`;
    toastDiv.setAttribute('role', 'alert');
    toastDiv.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    root.appendChild(toastDiv);
    const bsToast = new bootstrap.Toast(toastDiv, {
        delay: 3000
    });
    bsToast.show();
    toastDiv.addEventListener('hidden.bs.toast', () => toastDiv.remove());
}

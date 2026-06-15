<div class="dropdown" id="notifBell">
    <button class="btn btn-link position-relative text-secondary p-1" data-bs-toggle="dropdown" id="notifToggle" aria-expanded="false">
        <i class="fas fa-bell fa-lg"></i>
        <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" style="font-size:.6rem">0</span>
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow" style="min-width:320px;max-height:400px;overflow-y:auto;" id="notifDropdown">
        <div class="dropdown-header fw-bold d-flex justify-content-between">
            <span>Announcements</span>
            <small id="notifCount" class="text-muted"></small>
        </div>
        <div id="notifList">
            <div class="dropdown-item text-muted small">Loading…</div>
        </div>
    </div>
</div>

<script>
(function(){
    const btn = document.getElementById('notifToggle');
    const list = document.getElementById('notifList');
    const badge = document.getElementById('notifBadge');
    const countEl = document.getElementById('notifCount');
    let loaded = false;

    btn.addEventListener('click', () => {
        if (loaded) return;
        fetch('/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                loaded = true;
                list.innerHTML = '';
                const count = data.notifications.length;
                countEl.textContent = `${count} new`;
                if (count > 0) { badge.textContent = count; badge.classList.remove('d-none'); }

                if (!count) {
                    list.innerHTML = '<div class="dropdown-item text-muted small">No announcements</div>';
                    return;
                }
                data.notifications.forEach(n => {
                    const item = document.createElement('a');
                    item.href = n.url;
                    item.className = 'dropdown-item py-2 border-bottom';
                    item.innerHTML = `
                        <div class="fw-medium small lh-sm">${n.title}</div>
                        <div class="text-muted" style="font-size:.7rem">${n.date}</div>
                    `;
                    list.appendChild(item);
                });
            });
    });
})();
</script>
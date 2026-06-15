<div class="search-wrapper position-relative" style="max-width:400px;">
    <input
        type="text"
        id="globalSearch"
        class="form-control ps-4"
        placeholder="Search students, courses, assignments…"
        autocomplete="off"
    />
    <span class="position-absolute top-50 start-0 translate-middle-y ps-2 text-muted">
        <i class="fas fa-search fa-sm"></i>
    </span>
    <div id="searchDropdown" class="search-dropdown card shadow-sm d-none position-absolute w-100" style="z-index:9999;top:calc(100% + 4px);">
        <div id="searchResults" class="list-group list-group-flush rounded"></div>
    </div>
</div>

<script>
(function(){
    const input = document.getElementById('globalSearch');
    const dropdown = document.getElementById('searchDropdown');
    const results = document.getElementById('searchResults');
    let timer;

    input.addEventListener('input', () => {
        clearTimeout(timer);
        const q = input.value.trim();
        if (q.length < 2) { dropdown.classList.add('d-none'); return; }
        timer = setTimeout(() => {
            fetch(`/search?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                results.innerHTML = '';
                if (!data.results.length) {
                    results.innerHTML = '<div class="list-group-item text-muted small">No results found</div>';
                } else {
                    data.results.forEach(item => {
                        const a = document.createElement('a');
                        a.href = item.url;
                        a.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2 py-2';
                        a.innerHTML = `
                            <span class="badge bg-secondary text-capitalize" style="font-size:.65rem;min-width:70px">${item.type}</span>
                            <span class="small">${item.label}</span>
                        `;
                        results.appendChild(a);
                    });
                }
                dropdown.classList.remove('d-none');
            });
        }, 300);
    });

    document.addEventListener('click', e => {
        if (!input.contains(e.target) && !dropdown.contains(e.target))
            dropdown.classList.add('d-none');
    });
})();
</script>
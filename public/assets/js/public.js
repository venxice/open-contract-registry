const API = '/api/biddings';
let state = [], year = '', query = '';

const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[c]);
const money = v => new Intl.NumberFormat('en-US', {style:'currency',currency:'USD',maximumFractionDigits:0}).format(v||0);
const fmt = v => v ? new Intl.DateTimeFormat('en-US', {month:'short',day:'numeric',year:'numeric'}).format(new Date(v+'T00:00:00')) : '—';
const allDesc = p => (p.projects||[]).flatMap(t => t.descriptions||[]);

async function loadData() {
    try {
        const res = await fetch(API);
        state = await res.json();
    } catch(e) { state = []; }
    render();
}

function header() {
    return `<div class="topline"></div><nav class="public-nav"><div class="container"><div class="nav-inner d-flex align-items-center justify-content-between"><button class="brand-lockup" onclick="render()"><span class="brand-mark"><i class="bi bi-building"></i></span><span class="text-start"><span class="brand-name">Open Contract Register</span><span class="brand-sub">Public Bidding Portal</span></span></button><div><button class="nav-linkish" onclick="render()"><i class="bi bi-search me-1"></i>Browse awards</button><a class="nav-linkish nav-admin" href="admin"><i class="bi bi-shield-check me-1"></i>Admin sign in</a></div></div></div></nav>`;
}

function publicHome() {
    let rows = state.filter(p => (!year || (p.notice_date||'').startsWith(year)) && (!query || `${p.contractor} ${p.contract_number}`.toLowerCase().includes(query.toLowerCase())));
    return `${header()}<header class="public-hero"><div class="container hero-content"><div class="eyebrow hero-kicker">Transparency in practice / public record</div><h1>See where public money is put to work.</h1><p>Browse awarded government contracts, read the project record, and open the source documents behind each decision.</p><span class="hero-stamp"><i class="bi bi-eye me-2"></i>Accessible to everyone, no account required</span></div></header><main><div class="container"><section class="filter-bar"><div><label class="filter-label">Award year</label><select class="form-select" onchange="year=this.value;render()"><option value="">All years</option>${[2026,2025,2024,2023].map(y=>`<option ${String(y)===year?'selected':''}>${y}</option>`).join('')}</select></div><div class="search-wrap flex-grow-1"><label class="filter-label">Search the register</label><i class="bi bi-search"></i><input class="form-control" value="${esc(query)}" oninput="query=this.value;render()" placeholder="Project, contractor, or contract number"></div><button class="btn-outline-civic" onclick="year='';query='';render()">Reset</button></section><section class="section"><div class="record-heading"><div><div class="eyebrow">Awarded contracts</div><h2>Summary of Awarded Contract for Public Bidding</h2></div><span class="record-count"><i class="bi bi-file-earmark-text me-1"></i>Last published <strong>${fmt(rows[0]?.notice_date)}</strong></span></div><div class="table-shell"><div class="table-responsive"><table class="table contract-table align-middle"><thead><tr><th>Title / Project</th><th>Contractor</th><th>Contract amount</th><th>Notice of award</th><th>Contract number</th><th>Contract date</th><th>Notice to proceed</th></tr></thead><tbody>${rows.map(p=>`<tr><td><button class="project-link" onclick="detail(${p.bidding_id})">${esc(p.contractor)}</button><div class="project-code mono">${p.bidding_id}</div></td><td>${esc(p.contractor)}</td><td class="amount">${money(p.amount)}</td><td>${fmt(p.notice_date)}</td><td class="mono small">${p.contract_number||'—'}</td><td>${fmt(p.contract_date)}</td><td>${fmt(p.notice_proceed)}</td></tr>`).join('')||`<tr><td colspan="7" class="text-center py-5 text-muted">No awards match those filters.</td></tr>`}</tbody></table></div><div class="table-footer"><span>Showing <strong>${rows.length}</strong> of ${state.length} published awards</span><span class="mono">PUBLIC RECORD / ${year||'ALL YEARS'}</span></div></div><div class="info-band"><strong><i class="bi bi-info-circle me-2"></i>How to use this register.</strong> Select a project title to see its separate project titles, descriptions, and documents.</div></section></div></main><footer class="public-footer"><div class="container d-flex flex-wrap justify-content-between gap-4"><div class="brand-lockup text-white"><span class="brand-mark"><i class="bi bi-building"></i></span><span><span class="brand-name">Open Contract Register</span><span class="brand-sub text-white-50">A public record of public work</span></span></div><p class="footer-note m-0">A prototype public disclosure service for transparency, accountability, and informed civic participation.</p></div></footer>`;
}

function detail(id) {
    let p = state.find(x => x.bidding_id == id);
    if (!p) return;
    history.replaceState(null, '', `?project=${id}`);
    document.getElementById('app').innerHTML = `${header()}<main class="container"><div class="crumbs"><button onclick="history.replaceState(null,'','/');render()"><i class="bi bi-arrow-left me-1"></i>All awarded contracts</button> <span class="mx-2">/</span> Project record</div><section class="detail-title"><div class="eyebrow">Project record / ${p.contract_number||'—'}</div><h1>${esc(p.contractor)}</h1><p>Public project titles, descriptions, and source documents associated with this awarded contract.</p></section><div class="detail-grid"><div><section class="detail-card mb-4"><h2>Contract summary</h2><div class="detail-meta">${[['Contractor',p.contractor],['Contract amount',money(p.amount)],['Contract number',p.contract_number||'—'],['Notice of award',fmt(p.notice_date)],['Contract date',fmt(p.contract_date)],['Notice to proceed',fmt(p.notice_proceed)]].map(x=>`<div class="meta-item"><span class="meta-label">${x[0]}</span><span class="meta-value">${esc(x[1])}</span></div>`).join('')}</div></section><section class="detail-card"><div class="d-flex justify-content-between align-items-end mb-3"><div><h2 class="mb-1">Project titles &amp; descriptions</h2><p class="small text-muted mb-0">Open a description to view its supporting document.</p></div><span class="record-count">${(p.projects||[]).length} title${(p.projects||[]).length===1?'':'s'} · ${allDesc(p).length} description${allDesc(p).length===1?'':'s'}</span></div><div class="description-groups">${(p.projects||[]).map((t,i)=>`<section class="description-group"><div class="description-group-head"><div><span class="group-index">PROJECT TITLE ${String(i+1).padStart(2,'0')}</span><h3>${esc(t.project_title)}</h3></div><span class="record-count">${(t.descriptions||[]).length} description${(t.descriptions||[]).length===1?'':'s'}</span></div><div class="description-list">${(t.descriptions||[]).map(d=>`<div class="description-row"><div><div class="description-title">${esc(d.project_description)}</div><div class="description-date">Date posted ${fmt(d.date_posted)}</div></div>${d.project_attachment?`<button class="pdf-pill" onclick="previewPdf(${p.bidding_id},${d.description_id})"><i class="bi bi-file-earmark-pdf me-1"></i>Preview PDF</button>`:`<span class="pdf-pill" style="opacity:0.5;cursor:default"><i class="bi bi-file-earmark me-1"></i>No document</span>`}</div>`).join('')}</div></section>`).join('')}</div></section></div><aside class="detail-card h-fit"><div class="eyebrow mb-3">Record details</div><div class="side-stat"><span class="meta-label">Published by</span><strong>Procurement Office</strong></div><div class="side-stat"><span class="meta-label">Award year</span><strong>${(p.notice_date||'').slice(0,4)}</strong></div><div class="side-stat"><span class="meta-label">Record status</span><strong class="text-success"><i class="bi bi-check-circle me-1"></i>Published</strong></div><a class="btn-outline-civic w-100 mt-3 d-block" href="admin"><i class="bi bi-pencil-square me-1"></i>Staff access</a></aside></div></main>`;
}

function previewPdf(bid, did) {
    var p = state.find(function(x) { return x.bidding_id == bid; });
    if (!p) return;
    var d = null;
    (p.projects || []).forEach(function(t) {
        (t.descriptions || []).forEach(function(desc) {
            if (desc.description_id == did) d = desc;
        });
    });
    if (!d || !d.project_attachment) return;
    var src = d.project_attachment.startsWith('/') ? d.project_attachment : '/' + d.project_attachment;
    document.body.insertAdjacentHTML('beforeend', '<div class="modal-backdrop-custom" onclick="if(event.target===this)this.remove()"><div class="custom-modal"><div class="modal-head"><div><div class="eyebrow">Source document</div><h2 class="mt-1 mb-0">' + esc(d.project_description) + '</h2></div><button class="close-btn" onclick="this.closest(\'.modal-backdrop-custom\').remove()"><i class="bi bi-x-lg"></i></button></div><div class="modal-body"><iframe title="PDF preview" class="pdf-frame" src="' + src + '"></iframe></div><div class="modal-foot"><span class="small text-muted me-auto">Posted ' + fmt(d.date_posted) + '</span><a class="btn-civic" href="' + src + '" download><i class="bi bi-download me-1"></i>Download PDF</a><button class="btn-outline-civic" onclick="this.closest(\'.modal-backdrop-custom\').remove()">Close</button></div></div></div>');
}

function render() {
    document.getElementById('app').innerHTML = publicHome();
}

const initialProject = new URLSearchParams(location.search).get('project');
loadData().then(() => { if (initialProject) detail(initialProject); });

const API_BIDDINGS = '/api/biddings';
const API_USERS = '/api/users';
const API_UPLOAD = '/api/upload';
let projects = [], users = [], adminTab = 'overview', editingId = null;

const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[c]);
const money = v => new Intl.NumberFormat('en-US', {style:'currency',currency:'USD',maximumFractionDigits:0}).format(v||0);
const fmt = v => v ? new Intl.DateTimeFormat('en-US', {month:'short',day:'numeric',year:'numeric'}).format(new Date(v+'T00:00:00')) : '—';
const allDesc = p => (p.projects||[]).flatMap(t => t.descriptions||[]);

async function loadProjects() {
    try { const r = await fetch(API_BIDDINGS); projects = await r.json(); } catch(e) { projects = []; }
}
async function loadUsers() {
    try { const r = await fetch(API_USERS); users = await r.json(); } catch(e) { users = []; }
}
async function loadAll() { await Promise.all([loadProjects(), loadUsers()]); render(); }

function toast(message, type='success') {
    let el = document.createElement('div');
    el.className = 'toast-stack';
    el.innerHTML = `<div class="toast-custom ${type}"><i class="bi ${type==='success'?'bi-check-circle':'bi-info-circle'} me-2"></i>${esc(message)}</div>`;
    document.body.append(el);
    setTimeout(() => el.remove(), 3000);
}

function header() {
    return `<div class="admin-topbar"><div class="container-fluid px-3 px-lg-4"><div class="d-flex align-items-center justify-content-between" style="min-height:68px"><button class="brand-lockup text-white" onclick="adminTab='overview';loadAll()"><span class="brand-mark"><i class="bi bi-building"></i></span><span class="text-start"><span class="brand-name">Open Contract Register</span><span class="brand-sub text-white-50">Administrator console</span></span></button><a class="btn btn-sm btn-outline-light" href="index.html"><i class="bi bi-box-arrow-up-right me-1"></i>Public register</a></div></div></div>`;
}

function adminShell(content) {
    return `<div class="admin-shell">${header()}<div class="admin-layout"><aside class="admin-sidebar"><p class="admin-sidebar-label">Workspace</p>${['overview','projects','users'].map((t,i)=>`<button class="admin-nav-btn ${adminTab===t?'active':''}" onclick="adminTab='${t}';render()"><i class="bi bi-${['grid-1x2','folder2-open','people'][i]}"></i><span>${t[0].toUpperCase()+t.slice(1)}</span></button>`).join('')}</aside><main class="admin-main">${content}</main></div></div>`;
}

function overview() {
    let total = projects.reduce((s,p) => s + Number(p.amount||0), 0);
    return adminShell(`<div class="admin-heading"><div><div class="eyebrow">Workspace overview</div><h1>Good morning, Mara.</h1><p>A quick read on the public contract register.</p></div><button class="btn-civic" onclick="editingId=null;adminTab='form';render()"><i class="bi bi-plus-lg me-1"></i>New project</button></div><div class="overview-cards">${[['Published projects',projects.length,'Across the public register'],['Published value',money(total),'Total awarded contract value'],['Source documents',projects.reduce((s,p)=>s+allDesc(p).length,0),'Descriptions in the register'],['Active users',users.filter(u=>u.status==='ACTIVE').length,'Staff with access']].map(x=>`<div class="overview-card"><span class="eyebrow">${x[0]}</span><div class="stat-number">${x[1]}</div><span class="stat-caption">${x[2]}</span></div>`).join('')}</div><div class="admin-columns"><section class="admin-panel"><div class="admin-panel-head"><h2>Recent project activity</h2><button class="btn btn-sm btn-link text-decoration-none" onclick="adminTab='projects';render()">View all →</button></div>${projects.slice(0,4).map(p=>`<div class="activity-row"><div class="activity-icon"><i class="bi bi-file-earmark-check"></i></div><div class="activity-text"><strong>${esc(p.contractor)}</strong><span>Published to public register</span></div><div class="activity-time">${fmt(p.notice_date)}</div></div>`).join('')}</section><section class="admin-panel"><div class="admin-panel-head"><h2>Register health</h2><i class="bi bi-bar-chart-line text-muted"></i></div><div class="p-4"><div class="d-flex justify-content-between small"><span>Records with source documents</span><strong>100%</strong></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:100%;background:var(--teal)"></div></div><p class="small text-muted mt-3">Each description can have its own supporting PDF.</p></div></section></div>`);
}

function projectList() {
    return adminShell(`<div class="admin-heading"><div><div class="eyebrow">Content workspace</div><h1>Projects</h1><p>Create, review, and publish contract records.</p></div><button class="btn-civic" onclick="editingId=null;adminTab='form';render()"><i class="bi bi-plus-lg me-1"></i>New project</button></div><div class="management-toolbar"><h2>All project records <span class="record-count ms-2">${projects.length}</span></h2></div><div class="admin-panel"><div class="table-responsive"><table class="table admin-table align-middle"><thead><tr><th>Project / ID</th><th>Contractor</th><th>Amount</th><th>Award date</th><th>Titles / descriptions</th><th class="text-end">Manage</th></tr></thead><tbody>${projects.map(p=>`<tr><td><strong>${esc(p.contract_number||'—')}</strong><div class="project-code mono">PRJ-${p.bidding_id}</div></td><td>${esc(p.contractor)}</td><td class="amount">${money(p.amount)}</td><td>${fmt(p.notice_date)}</td><td><span class="role-badge">${(p.projects||[]).length} title${(p.projects||[]).length===1?'':'s'} · ${allDesc(p).length} description${allDesc(p).length===1?'':'s'}</span></td><td class="text-end text-nowrap"><a class="action-btn" href="index.html?project=${p.bidding_id}" target="_blank" title="View on public register"><i class="bi bi-eye"></i></a><button class="action-btn" onclick="editingId='${p.bidding_id}';adminTab='form';render()" title="Edit"><i class="bi bi-pencil"></i></button></td></tr>`).join('')}</tbody></table></div></div>`);
}

function titleForm(t, i) {
    return `<div class="project-title-record nested-record" data-title="${t.id||''}"><div class="nested-head"><div><span>PROJECT TITLE ${String(i+1).padStart(2,'0')}</span><h3 class="nested-title-heading">Project title</h3></div><button type="button" class="action-btn danger" onclick="this.closest('.project-title-record').remove()"><i class="bi bi-trash3"></i></button></div><label class="form-label">Project Title</label><input class="form-control project-title-input" value="${esc(t.project_title||'')}" placeholder="e.g. Learning Center Construction Works"><div class="nested-description-head"><div><strong>Descriptions</strong><span class="small text-muted d-block">Each description has its own PDF document.</span></div><button type="button" class="btn-outline-civic btn-sm" onclick="addDesc(this)"><i class="bi bi-plus-lg me-1"></i>Add description</button></div><div class="desc-list">${(t.descriptions||[]).map((d,j)=>descForm(d,j)).join('')}</div></div>`;
}

function descForm(d, j) {
    return `<div class="description-record"><div class="nested-head"><span>DESCRIPTION ${String(j+1).padStart(2,'0')}</span><button type="button" class="action-btn danger" onclick="this.closest('.description-record').remove()"><i class="bi bi-trash3"></i></button></div><div class="row g-3"><div class="col-md-7"><label class="form-label">Description</label><textarea class="form-control description-input" rows="2" placeholder="Describe the project scope">${esc(d.project_description||'')}</textarea></div><div class="col-md-5"><label class="form-label">Date Posted</label><input type="date" class="form-control date-input" value="${d.date_posted||''}"></div><div class="col-12"><label class="form-label">PDF Document</label><div class="upload-box"><i class="bi bi-paperclip"></i><input type="file" accept="application/pdf" onchange="fileChange(this)">${d.project_attachment?`<span class="file-ready"><i class="bi bi-check-circle me-1"></i>File attached</span>`:`<span class="small">PDF only · up to 10 MB</span>`}</div></div></div></div>`;
}

function projectForm() {
    let p = projects.find(x => x.bidding_id == editingId) || {contractor:'',amount:'',notice_date:'',contract_number:'',contract_date:'',notice_proceed:'',projects:[{project_title:'',descriptions:[{project_description:'',date_posted:'',project_attachment:''}]}]};
    return adminShell(`<div class="admin-heading"><div><div class="eyebrow">${editingId?'Edit record':'Create record'}</div><h1>${editingId?'Edit project':'New project'}</h1><p>Build the public record, then organize project titles and supporting descriptions.</p></div><button class="btn-outline-civic" onclick="adminTab='projects';render()"><i class="bi bi-x-lg me-1"></i>Cancel</button></div><form id="project-form" onsubmit="saveProject(event)"><section class="form-section"><div class="form-section-head"><div><span class="section-num">01</span><strong>Contract record</strong></div><span class="small text-muted">All fields are public</span></div><div class="row g-3">${[['contractor','Contractor',p.contractor,'text'],['amount','Contract amount',p.amount,'number'],['notice_date','Notice of award',p.notice_date,'date'],['contract_number','Contract number',p.contract_number,'text'],['contract_date','Contract date',p.contract_date,'date'],['notice_proceed','Notice to proceed',p.notice_proceed,'date']].map((x,i)=>`<div class="${i===0?'col-12':'col-md-6'}"><label class="form-label">${x[1]}</label><input required id="f-${x[0]}" class="form-control" type="${x[3]}" value="${esc(x[2])}" placeholder="${x[1]}"></div>`).join('')}</div></section><section class="form-section"><div class="form-section-head"><div><span class="section-num">02</span><strong>Project titles &amp; descriptions</strong><div class="small text-muted">Add a Project Title, then add multiple separate Descriptions.</div></div><button type="button" class="btn-outline-civic btn-sm" onclick="addTitle()"><i class="bi bi-plus-lg me-1"></i>Add project title</button></div><div id="title-list">${(p.projects||[]).map((t,i)=>titleForm(t,i)).join('')}</div></section><div class="form-actions"><button type="button" class="btn-outline-civic" onclick="adminTab='projects';render()">Cancel</button><button class="btn-civic" type="submit"><i class="bi bi-check2 me-1"></i>${editingId?'Save changes':'Publish project'}</button></div></form>`);
}

function usersList() {
    return adminShell(`<div class="admin-heading"><div><div class="eyebrow">Access control</div><h1>Users</h1><p>Manage who can publish and maintain the register.</p></div><button class="btn-civic" onclick="userModal()"><i class="bi bi-person-plus me-1"></i>Create user</button></div><div class="admin-panel"><div class="table-responsive"><table class="table admin-table align-middle"><thead><tr><th>User</th><th>Role</th><th>Status</th><th class="text-end">Manage</th></tr></thead><tbody>${users.map(u=>`<tr><td><strong>${esc(u.first_name)} ${esc(u.last_name)}</strong><div class="small text-muted">${esc(u.email)}</div></td><td><span class="role-badge">${u.role}</span></td><td>${u.status}</td><td class="text-end"><button class="action-btn" onclick="userModal(${u.user_id})"><i class="bi bi-pencil"></i></button><button class="action-btn danger" onclick="deleteUser(${u.user_id})"><i class="bi bi-trash3"></i></button></td></tr>`).join('')}</tbody></table></div></div>`);
}

function render() {
    document.getElementById('app').innerHTML = adminTab==='overview'?overview():adminTab==='projects'?projectList():adminTab==='users'?usersList():projectForm();
}

function addTitle() {
    let list = document.getElementById('title-list');
    list.insertAdjacentHTML('beforeend', titleForm({project_title:'',descriptions:[{project_description:'',date_posted:'',project_attachment:''}]}, list.children.length));
}

function addDesc(btn) {
    let list = btn.closest('.project-title-record').querySelector('.desc-list');
    list.insertAdjacentHTML('beforeend', descForm({project_description:'',date_posted:'',project_attachment:''}, list.children.length));
}

async function fileChange(input) {
    let f = input.files[0];
    if (!f) return;
    if (f.type !== 'application/pdf' || f.size > 10*1024*1024) return toast('Choose a PDF smaller than 10 MB.','info');
    
    input.disabled = true;
    let fd = new FormData();
    fd.append('file', f);
    try {
        let res = await fetch(API_UPLOAD, {method:'POST', body: fd});
        let data = await res.json();
        if (data.path) {
            input.dataset.path = data.path;
            input.dataset.name = data.name;
            input.nextElementSibling?.remove();
            input.insertAdjacentHTML('afterend', `<span class="file-ready"><i class="bi bi-check-circle me-1"></i>${esc(data.name)}</span>`);
        } else {
            toast(data.error || 'Upload failed', 'info');
        }
    } catch(e) {
        toast('Upload failed', 'info');
    }
    input.disabled = false;
}

async function saveProject(e) {
    e.preventDefault();
    let f = e.target;
    let data = {
        contractor: f.querySelector('#f-contractor').value,
        amount: Number(f.querySelector('#f-amount').value),
        notice_date: f.querySelector('#f-notice_date').value || null,
        contract_number: f.querySelector('#f-contract_number').value,
        contract_date: f.querySelector('#f-contract_date').value || null,
        notice_proceed: f.querySelector('#f-notice_proceed').value || null,
        projectTitles: [...f.querySelectorAll('.project-title-record')].map(t => ({
            title: t.querySelector('.project-title-input').value,
            descriptions: [...t.querySelectorAll('.description-record')].map(d => {
                let input = d.querySelector('input[type=file]');
                return {
                    description: d.querySelector('.description-input').value,
                    date_posted: d.querySelector('.date-input').value || null,
                    fileData: input?.dataset?.path || '',
                };
            }),
        })),
    };
    if (!data.projectTitles.length || data.projectTitles.some(t => !t.title)) return toast('Add at least one Project Title.','info');
    
    let url = editingId ? `${API_BIDDINGS}/${editingId}` : API_BIDDINGS;
    let method = editingId ? 'PUT' : 'POST';
    let res = await fetch(url, {method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(data)});
    if (res.ok) {
        toast(editingId ? 'Project updated.' : 'Project created.');
        editingId = null;
        await loadAll();
        adminTab = 'projects';
        render();
    } else {
        toast('Failed to save project.','info');
    }
}

async function deleteBidding(id) {
    if (!confirm('Delete this project?')) return;
    let res = await fetch(`${API_BIDDINGS}/${id}`, {method:'DELETE'});
    if (res.ok) { toast('Project deleted.'); await loadAll(); render(); }
}

async function userModal(id='') {
    let u = id ? users.find(x => x.user_id == id) : {first_name:'',last_name:'',middle_initial:'',email:'',role:'Editor',status:'ACTIVE'};
    let fullName = `${u.first_name||''} ${u.middle_initial?u.middle_initial+'. ':''}${u.last_name||''}`.trim();
    document.body.insertAdjacentHTML('beforeend', `<div class="modal-backdrop-custom" onclick="if(event.target===this)this.remove()"><div class="custom-modal" style="max-width:620px"><div class="modal-head"><h2 class="mb-0">${id?'Edit user':'Create user'}</h2><button class="close-btn" onclick="this.closest('.modal-backdrop-custom').remove()"><i class="bi bi-x-lg"></i></button></div><form onsubmit="saveUser(event,'${id}')"><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="form-label">Full name</label><input required id="user-name" class="form-control" value="${esc(fullName)}"></div><div class="col-md-6"><label class="form-label">Email</label><input required type="email" id="user-email" class="form-control" value="${esc(u.email||'')}"></div><div class="col-md-6"><label class="form-label">Role</label><select id="user-role" class="form-select"><option ${u.role==='Administrator'?'selected':''}>Administrator</option><option ${u.role==='Editor'?'selected':''}>Editor</option><option ${u.role==='Viewer'?'selected':''}>Viewer</option></select></div><div class="col-md-6"><label class="form-label">Status</label><select id="user-status" class="form-select"><option ${u.status==='ACTIVE'?'selected':''}>ACTIVE</option><option ${u.status==='INACTIVE'?'selected':''}>INACTIVE</option></select></div></div></div><div class="modal-foot"><button type="button" class="btn-outline-civic" onclick="this.closest('.modal-backdrop-custom').remove()">Cancel</button><button class="btn-civic">Save user</button></div></form></div></div>`);
}

async function saveUser(e, id) {
    e.preventDefault();
    let fullName = e.target.querySelector('#user-name').value.trim();
    let parts = fullName.split(/\s+/);
    let last = parts.pop() || '';
    let mi = '';
    if (parts.length > 1 && parts[parts.length-1].endsWith('.')) {
        mi = parts.pop().replace('.','');
    }
    let first = parts.join(' ') || '';
    
    let data = {
        first_name: first,
        last_name: last,
        middle_initial: mi,
        email: e.target.querySelector('#user-email').value,
        role: e.target.querySelector('#user-role').value,
        status: e.target.querySelector('#user-status').value,
    };
    
    let url = id ? `${API_USERS}/${id}` : API_USERS;
    let method = id ? 'PUT' : 'POST';
    let res = await fetch(url, {method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(data)});
    if (res.ok) {
        toast(id ? 'User updated.' : 'User created.');
        e.target.closest('.modal-backdrop-custom').remove();
        await loadUsers();
        render();
    } else {
        toast('Failed to save user.','info');
    }
}

async function deleteUser(id) {
    if (!confirm('Delete this user?')) return;
    let res = await fetch(`${API_USERS}/${id}`, {method:'DELETE'});
    if (res.ok) { toast('User deleted.'); await loadUsers(); render(); }
}

loadAll();
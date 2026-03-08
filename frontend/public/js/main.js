const API_BASE_URL = 'http://localhost/esercizioSlim/backend/api/v2';
const DEFAULT_LIMIT = 10;

const dictionary = {
    'pnome': 'Nome Pezzo',
    'fnome': 'Nome Fornitore',
    'fid': 'ID Fornitore',
    'pid': 'ID Pezzo',
    'colore': 'Colore',
    'costo': 'Prezzo',
    'indirizzo': 'Sede Fornitore'
};

document.addEventListener('DOMContentLoaded', () => {
    loadData(1);
});

async function loadData(page = 1) {
    const querySelect = document.getElementById('querySelect');
    const loader = document.getElementById('loader');

    if(!querySelect) return;

    const queryId = querySelect.value;
    loader.style.display = 'block';

    try{
        const response = await fetch(`${API_BASE_URL}/${queryId}?page=${page}&limit=${DEFAULT_LIMIT}`, {credentials: 'include'});
        const result = await response.json();

        if(result.success) {
            renderTable(result.data);
            renderPagination(result.pagination);
        } else {
            alert ("API error: " + result.error);
        }
    } catch (error) {
        console.log(error);
        console.error("Connection failed");
    } finally {
        loader.style.display = 'none';
    }
}

function renderTable(data) {
    const header = document.getElementById('tableHeader');
    const body = document.getElementById('tableBody');
    header.innerHTML = '';
    body.innerHTML = '';

    if (!data || data.length === 0) {
        body.innerHTML = '<tr><td class="text-center py-4">Nessun dato trovato.</td></tr>';
        return;
    }

    const keys = Object.keys(data[0]);
    const displayKeys = keys.filter(key => {
        if (key === 'pid' && keys.includes('pnome')) return false;
        if (key === 'fid' && keys.includes('fnome')) return false;
        return true;
    });

    // Header
    let headerHtml = '<tr>';
    displayKeys.forEach(key => {
        headerHtml += `<th>${dictionary[key] || key.toUpperCase()}</th>`;
    });
    header.innerHTML = headerHtml + '</tr>';

    // Righe
    data.forEach(row => {
        let tr = '<tr>';
        displayKeys.forEach(key => {
            let value = row[key];
            let cellContent = value;

            // Logica Link per Nomi e ID
            if (key === 'pnome' && row['pid']) {
                cellContent = `<a href="#" class="clickable-id" onclick="showDetails('product', '${row['pid']}'); event.preventDefault();">${value}</a>`;
            } 
            else if (key === 'fnome' && row['fid']) {
                cellContent = `<a href="#" class="clickable-id text-success" onclick="showDetails('supplier', '${row['fid']}'); event.preventDefault();">${value}</a>`;
            }
            else if (key === 'pid' || key === 'fid') {
                cellContent = `<a href="#" class="clickable-id text-success" onclick="showDetails('${key === 'pid' ? 'product' : 'supplier'}', '${row[`${key === 'pid' ? 'pid' : 'fid'}`]}'); event.preventDefault();">${value}</a>`;
            }
            
            // Formattazione Costo
            if (key === 'costo' && value !== null) {
                cellContent = `<span class="fw-bold text-dark">€ ${parseFloat(value).toFixed(2)}</span>`;
            }

            tr += `<td>${cellContent}</td>`;
        });
        body.innerHTML += tr + '</tr>';
    });
}

function renderPagination(p) {
    const controls = document.getElementById('paginationControls');
    const info = document.getElementById('pageInfo');

    controls.innerHTML = "";

    if (!p.total_records || p.total_records === 0) {
        console.log(p.total_records)
        controls.innerHTML = '';
        info.innerText = 'Nessun risultato trovato';
        return;
    }

    let total_pages = Math.max(1, Math.ceil(p.total_records / DEFAULT_LIMIT));

    info.innerText = `Pagina ${p.current_page} di ${total_pages} (${p.total_records} record)`;

    if (total_pages > 1){
        let html = `
            <li class="page-item ${p.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); loadData(${p.current_page - 1})">Precedente</a>
            </li>
        `;

        for (let i = 1; i <= total_pages; i++) {
            html += `
                <li class="page-item ${i === p.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); loadData(${i})">${i}</a>
                </li>
            `;
        }

        html += `
            <li class="page-item ${p.current_page === total_pages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); loadData(${p.current_page + 1})">Successiva</a>
            </li>
        `;

        controls.innerHTML = html;
    }
}

async function showDetails(resource, id) {
    const modalContent = document.getElementById('modalContent');
    // Inizializziamo la modale di Bootstrap
    const modalElement = document.getElementById('detailsModal');
    const myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
    
    // Mostra caricamento
    modalContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Caricamento dettagli...</p>
        </div>`;
    myModal.show();

    try {
        // Chiamata alla rotta specifica (pezzi o fornitori)
        const response = await fetch(`${API_BASE_URL}/${resource}/${id}`, {credentials: 'include'});
        const result = await response.json();

        if (result.success) {
            renderModalContent(result.data, resource);
        } else {
            modalContent.innerHTML = `<div class="alert alert-warning">${result.error}</div>`;
        }
    } catch (error) {
        modalContent.innerHTML = `<div class="alert alert-danger">Errore di rete o server non raggiungibile.</div>`;
    }
}

function renderModalContent(data, type) {
    const modalContent = document.getElementById('modalContent');
    
    let html = `<div class="list-group list-group-flush">`;
    
    for (const [key, val] of Object.entries(data)) {
        const label = (typeof dictionary !== 'undefined' && dictionary[key]) ? dictionary[key] : key.toUpperCase();
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                <span class="text-muted small text-uppercase fw-bold">${label}</span>
                <span class="fw-bold text-dark">${val || 'N/D'}</span>
            </div>`;
    }
    
    html += `</div>
        <div class="mt-4 d-grid">
            <button class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Chiudi Dettagli</button>
        </div>`;
        
    modalContent.innerHTML = html;
}


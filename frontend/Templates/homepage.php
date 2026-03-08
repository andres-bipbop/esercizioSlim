<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestione Fornitori - Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .loader { display: none; }
            .table-responsive { min-height: 200px; }
        </style>
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row mb-4 text-center">
                <div class="col">
                    <h1 class="display-5 fw-bold text-primary">📦 Catalogo Fornitori</h1>
                    <p class="lead">Seleziona una query per visualizzare i dati in tempo reale.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Seleziona Interrogazione:</label>
                            <select id="querySelect" class="form-select form-select-lg shadow-sm border-primary" onchange="loadData(1)">
                                <option value="1">1. Pezzi con almeno un fornitore</option>
                                <option value="2">2. Fornitori che forniscono OGNI pezzo</option>
                                <option value="3">3. Fornitori per tutti i pezzi ROSSI</option>
                                <option value="4">4. Pezzi forniti SOLO da Acme</option>
                                <option value="5">5. Fornitori che ricaricano su alcuni pezzi più del loro costo medio</option>
                                <option value="6">6. Fornitori che ricaricano di più su un pezzo</option>
                                <option value="7">7. Fornitori con solo pezzi rossi</option>
                                <option value="8">8. Fornitori con almeno un pezzo rosso e uno verde</option>
                                <option value="9">9. Fornitori con almeno un pezzo rosso o uno verde</option>
                                <option value="10">10. Pezzi con almeno due fornitori</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Risultati della Ricerca</h5>
                </div>
                <div class="card-body">
                    <div id="loader" class="text-center my-4 loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="resultsTable">
                            <thead class="table-dark" id="tableHeader"></thead>
                            <tbody id="tableBody">
                                <tr><td class="text-center text-muted py-5">Seleziona una query e premi 'Esegui'</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <nav class="d-flex justify-content-between align-items-center mt-4">
                        <div id="pageInfo" class="text-muted small"></div>
                        <ul class="pagination mb-0" id="paginationControls"></ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="card border-0 shadow-lg w-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">🔍 Dettagli Record</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="card-body" id="modalContent">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
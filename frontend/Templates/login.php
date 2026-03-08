<!DOCTYPE html>
<html lang="en">

<head>
    <title>Gestione Fornitori - Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Fornitori - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Blocca il comportamento default del form (ricarica pagina)

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('http://localhost/esercizioSlim/backend/api/v1/login', {
                method: 'POST',
                credentials: 'include', // Dice al browser di salvare i cookie della risposta
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = '/homepage.php'; // Reindirizza alla dashboard
            } else if (!data.success && data.error === 'Nome utente non trovato') {
                alert(data.error);
            } else if (!data.success && data.error === 'Password errata') {
                alert(data.error);
            }

        } catch (error) {
            alert('Errore di connessione al server');
        }
    });
</script>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" id="form-title">LOGIN</h5>
                        <form id="loginForm" method="POST">
                            <div class="form-group" id="username-form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required />
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
                            </div>
                            <button class="btn btn-danger mt-3" id="send-form-button">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
console.log("A pagina carregou (console.log do navegador)");

fetch('/api/status')
    .then(res => res.json())
    .then(data => {
        console.log("Resposta do Node.js (BFF):", data);
    });

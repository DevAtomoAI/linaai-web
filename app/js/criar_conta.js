function criarContaNova() {
    const nome = document.getElementById('nomeNovoUsuario').value;
    const email = document.getElementById('emailNovoUsuario').value;
    const senha = document.getElementById('senhaNovoUsuario').value;

    fetch('http://localhost/Serviços%20Átomo%20AI/web-linaai/api/index.php?endpoint=add_client', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'nome': nome,
            'email': email,
            'senha': senha
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // Aqui você pode tratar a resposta, exibir mensagens, etc.
    })
    .catch(error => console.error('Erro:', error));
}
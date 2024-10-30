function verificaUsuarioExistente() {
    const emailLogin = document.getElementById('emailLogin').value;
    const senhaLogin = document.getElementById('senhaLogin').value;

    var dadosLogin = {
        'emailLogin': emailLogin,
        'senhaLogin': senhaLogin 
    };

    fetch('http://localhost/Serviços%20Átomo%20AI/web-linaai/api/index.php?endpoint=verify_existant_user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(dadosLogin)
    })
    .then(response => {
        // Certifique-se de que a resposta é JSON
        if (!response.ok) {
            throw new Error('Erro na resposta da rede');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.status === 'SUCCESS') {
            window.location.href = '../app/chatbot.html'; // Redirecionar para chatbot.html
        } else {
            alert(data.message); // Exibir mensagem de erro
        }
    })
    .catch(error => console.error('Erro:', error));
}

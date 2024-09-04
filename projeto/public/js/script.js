// script.js
document.addEventListener('DOMContentLoaded', () => {
    const positions = document.querySelectorAll('.position');
    const players = document.querySelectorAll('.player');

    players.forEach(player => {
        player.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', e.target.id);
        });
    });

    positions.forEach(position => {
        position.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        position.addEventListener('drop', (e) => {
            e.preventDefault();
            const playerId = e.dataTransfer.getData('text/plain');
            const player = document.getElementById(playerId);
            position.appendChild(player);
            savePosition(playerId, position.dataset.position);
        });
    });
});

function savePosition(playerId, position) {
    fetch('save_position.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'player_id': playerId,
            'position': position,
        }),
    })
        .then(response => response.text())
        .then(result => {
            console.log(result);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function validarNomeUsuario() {
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    const nome = nameInput.value;

    let erro = '';

    // Verifica se o nome tem mais de 3 caracteres
    if (nome.length <= 3) {
        erro = 'O nome deve ter mais de 3 caracteres.';
    } else if (/\s/.test(nome)) { // Verifica se o nome contém espaços
        erro = 'O nome não pode conter espaços.';
    }

    if (erro) {
        nameInput.classList.add('error');
        nameError.textContent = erro;
    } else {
        nameInput.classList.remove('error');
        nameError.textContent = '';
    }
}

function validarSenha() {
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    const senha = passwordInput.value;

    let erro = '';

    // Verifica se a senha tem mais de 3 caracteres
    if (senha.length <= 3) {
        erro = 'A senha deve ter mais de 3 caracteres.';
    }

    if (erro) {
        passwordInput.classList.add('error');
        passwordError.textContent = erro;
    } else {
        passwordInput.classList.remove('error');
        passwordError.textContent = '';
    }
}

function validarEmail() {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const email = emailInput.value;

    let erro = '';

    // Verifica se o email está no formato correto usando uma expressão regular simples
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        erro = 'Insira um endereço de email válido.';
    }

    if (erro) {
        emailInput.classList.add('error');
        emailError.textContent = erro;
    } else {
        emailInput.classList.remove('error');
        emailError.textContent = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const errorMessageDiv = document.getElementById('error-message');

    if (errorMessage) {
        errorMessageDiv.textContent = errorMessage;
        errorMessageDiv.style.display = 'block';
    }
});

// Permite o drop
function allowDrop(event) {
    event.preventDefault();
}

// Define o tipo de dados a ser transferido durante o drag
function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
}

// Manipula o drop e move o jogador para o novo quadro
function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    var jogador = document.getElementById(data);
    var targetQuadro = event.target.closest('.quadro');

    // Certifique-se de que o alvo é um quadro e adicione o jogador ao quadro
    if (targetQuadro && targetQuadro.querySelector('.jogadores')) {
        targetQuadro.querySelector('.jogadores').appendChild(jogador);
    }
}

// Prepara os dados do formulário com base nas posições dos jogadores
function prepareForm(event) {
    const positionsContainer = document.getElementById('positions');
    positionsContainer.innerHTML = ''; // Limpar o conteúdo anterior

    document.querySelectorAll('.quadro').forEach(quadro => {
        let position = quadro.id; // O ID do quadro é o nome da posição (pivo, ala, etc.)
        quadro.querySelectorAll('.jogador').forEach(jogador => {
            let jogadorId = jogador.id.split('-')[1]; // Extrai o ID do jogador
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'positions[' + jogadorId +
                ']'; // Cria um campo oculto para cada jogador com sua posição
            input.value = position;
            positionsContainer.appendChild(input);
        });
    });
}

// Adiciona um evento para preparar o formulário antes do envio
document.querySelector('form').addEventListener('submit', prepareForm);


<?php

class api_logic
{

    private $endpoint;
    private $params;

    // --------------------------------------------------
    public function __construct($endpoint, $params = null)
    {
        // define the object/class properties
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    // --------------------------------------------------
    public function endpoint_exists()
    {
        // check if the endpoint is a valid class method
        return method_exists($this, $this->endpoint);
    }

    // --------------------------------------------------
    public function error_response($message)
    {
        // returns an error from the API
        return [
            'status' => 'ERROR',
            'message' => $message,
            'results' => []
        ];
    }


    // --------------------------------------------------
    // ENDPOINTS
    // --------------------------------------------------
    public function status()
    {
        return [
            'status' => 'SUCCESS',
            'message' => 'API is running ok!',
            'results' => null
        ];
    }

    // --------------------------------------------------
    // INSERT NEW USER
    // --------------------------------------------------
    public function add_client()
    {
        if (empty($this->params['nome']) || empty($this->params['email']) || empty($this->params['senha'])) {
            return [
                'status' => 'ERROR',
                'message' => 'Parâmetros ausentes.'
            ];
        }

        $nome = $this->params['nome'];
        $email = $this->params['email'];
        $senha = password_hash($this->params['senha'], PASSWORD_DEFAULT);

        // Cria uma nova instância do banco de dados
        $db = new database();

        // Prepara a instrução SQL para inserir um novo cliente
        $query = "INSERT INTO usuarios_cadastrados (nome, email, senha) VALUES (:nome, :email, :senha)";

        // Executa a query passando os parâmetros
        $params = [
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha // Usa hash para senhas
        ];

        $result = $db->EXE_NON_QUERY($query, $params);

        return [
            'status' => 'SUCCESS',
            'message' => 'Cliente adicionado com sucesso.',
            'results' => $result
        ];
    }

    // --------------------------------------------------
    // VERIFY IF USER EXISTS
    // --------------------------------------------------
    public function verify_existant_user() {
        if (empty($this->params['emailLogin']) || empty($this->params['senhaLogin'])) {
            return [
                'status' => 'ERROR',
                'message' => 'Parâmetros ausentes.'
            ];
        }
    
        $emailLogin = $this->params['emailLogin'];
        $senhaLogin = $this->params['senhaLogin'];
    
        // Cria uma nova instância do banco de dados
        $db = new database();
        $query = "SELECT * FROM  usuarios_cadastrados WHERE email = :email";
        $params = [
            ':email' => $emailLogin,
        ];
        $result = $db->EXE_QUERY($query, $params);
    
        header('Content-Type: application/json'); // Mover para aqui
    
        if (count($result) === 1 && password_verify($senhaLogin, $result[0]['senha'])) {
            return [
                'status' => 'SUCCESS',
                'message' => 'Usuário autenticado com sucesso.'
            ];
        } else {
            return [
                'status' => 'ERROR',
                'message' => 'Usuário ou senha inválidos.'
            ];
        }
    }
    
    
}

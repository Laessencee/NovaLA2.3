<?php
session_start();

// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'laessencee';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Consulta para buscar os itens do carrinho, com informações do produto
$sql = "
    SELECT 
        carrinho.quantidade, 
        rgt_produto.nome, 
        rgt_produto.preco, 
        rgt_produto.id AS produto_id
    FROM 
        carrinho
    INNER JOIN 
        rgt_produto 
    ON 
        carrinho.produto_id = rgt_produto.id
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$itens_carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcula o total do carrinho
$total = 0;
foreach ($itens_carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>

        /* Importa a fonte 'Poppins' do Google Fonts com várias opções de peso (300 a 800) */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        /* Estilos para a página de carrinho */
        body {
            font-family: Poppins, sans-serif;
            background-color: #FFFAF0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-radius:38px;
            margin-bottom: 20px;
      
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            border-radius:20px;
         
        }
        th {
            background-color: #a0b29d;
            color: #333;
          
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            background-color:#678362;
            color: white;
            text-decoration: none;
            border-radius: 38px;
        }
        .btn:hover {
            background-color: #895829;
        }
        .remove-btn {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 38px;
            padding: 5px 10px;
        }
        .remove-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Carrinho de Compras</h2>

        <?php if (!empty($itens_carrinho)): ?>
            <table>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($itens_carrinho as $item): ?>
                    <tr>
                        <td><?php echo $item['nome']; ?></td>
                        <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="remover_do_carrinho.php?id=<?php echo $item['produto_id']; ?>" class="remove-btn">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="total">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></div>
            <a href="finalizar_compra.php" class="btn">Finalizar Compra</a>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>
        
        <a href="\NovaLA2.3/laessence/loja.php" class="btn">Continuar Comprando</a>
    </div>
</body>
</html>

<?php
  session_start();

  if(!isset($_SESSION["idItem"])) {
    $_SESSION["idItem"] = 0;
  }

  if(!isset($_SESSION["total"])) {
    $_SESSION["total"] = 0;
  }

  // session_destroy();

  class Item {
    private $idItem;
    public $nome;
    public $marca;
    public $tipo;
    public $quantidade;
    public $preco;

    public function __construct($id, $nome, $marca, $tipo, $qtd, $preco) {
      $this->idItem = $id;
      $this->nome = $nome;
      $this->marca = $marca;
      $this->tipo = $tipo;
      $this->quantidade = $qtd;
      $this->preco = $preco;
    }

    public function getId() {
      return $this->idItem;
    }

    public function getQuantidade() {
      return $this->quantidade;
    }

    public function getPreco() {
      return $this->preco;
    }

    public function Information() {
      echo "Id: " . $this->getId() . "<br/>";
      echo "Nome: " . $this->nome . "<br/>";
      echo "Marca: " . $this->marca . "<br/>";
      echo "Tipo: " . $this->tipo . "<br/>";
      echo "Quantidade: " . $this->quantidade . "<br/>";
      echo "Preço: " . $this->preco . "<br/>";
      echo "<hr>";
    }
  }
  
  class Pedido {
    private $idPedido;
    private $totalPedido = 0;
    private $items = [];

    public function __construct($id) {
      $this->idPedido = $id;
      if(isset($_SESSION["total"])) {
        $this->totalPedido = $_SESSION["total"];
      }
      if(isset($_SESSION["itens"])) {
        $this->items = $_SESSION["itens"];
      }
    }

    public function getId() {
      return $this->idPedido;
    }

    public function getTotal() {
      return $this->totalPedido;
    }

    public function addItem(Item $item) {
      $this->items[] = $item;
      $_SESSION["itens"] = $this->items;

      $this->somarTotal();
    }

    public function somarTotal() {
      $total = 0;
      foreach($this->items as $item) {
        $precoItem = $item->getQuantidade() * $item->getPreco();
        $total += $precoItem;
      }
      $_SESSION["total"] = $total;
      $this->totalPedido = $_SESSION["total"];
    }

    public function listarPedidos() {
      echo "Id do pedido: " . $this->getId() . "<br/>";
      echo "Total do pedido: " . $this->getTotal() . "<br/>";
      echo "<br/> Itens: <br/> <br/>";
      foreach($this->items as $item) {
        $item->Information();
      }
    }
  }

  $pedido1 = new Pedido(1);

  if($_POST) {
    if($_POST["btn"] == "b1") {
      $nome = $_POST["nome"];
      $marca = $_POST["marca"];
      $tipo = $_POST["tipo"];
      $quantidade = $_POST["quantidade"];
      $preco = $_POST["preco"];
      
      if(!empty($nome) && !empty($marca) && !empty($tipo) && !empty($quantidade) && !empty($preco)) {
        $idItem = $_SESSION["idItem"];
        $_SESSION["idItem"]++;

        $item1 = new Item($idItem, $nome, $marca, $tipo, $quantidade, $preco);
        $pedido1->addItem($item1);

        $_POST["nome"] = "";
        $_POST["marca"] = "";
        $_POST["tipo"] = "";
        $_POST["quantidade"] = "";
        $_POST["preco"] = "";
      } else {
        echo "Preencha os campos <br/>";
      }
      $pedido1->listarPedidos();
    }

  }
?>

<form action="classe.php" method="POST">
    Nome: <input type="text" name="nome"> <br/> <br/>
    Marca: <input type="text" name="marca"> <br/> <br/>
    Tipo: <input type="text" name="tipo"> <br/> <br/>
    Quantidade: <input type="number" name="quantidade"> <br/> <br/>
    Preço: <input type="number" step="0.01" name="preco"> <br/> <br/>
    <button name="btn" value="b1">Enviar</button>
</form>
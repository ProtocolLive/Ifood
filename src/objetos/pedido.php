<?php
//2022.07.09.00

enum IfoodPedidoDetalheTipo:string{
  case Entrega = 'DELIVERY';
  case Retirada = 'TAKEOUT';
  //case a = 'INDOOR'
}

enum IfoodPedidoDetalheEntregaModos:string{
  case Padrao = 'DEFAULT';
  case Economico = 'ECONOMIC';
  case Expresso = 'EXPRESS';
}

enum IfoodPedidoDetalheEntregaPor:string{
  case Ifood = 'IFOOD';
  case Loja = 'MERCHANT';
}

enum IfoodPedidoDetalhePagamentoMetodos:string{
  case Dinheiro = 'CASH';
  case Credito = 'CREDIT';
  case Debito = 'DEBIT';
  case ValeRefeicao1 = 'MEAL_VOUCHER';
  case ValeRefeicao2 = 'FOOD_VOUCHER';
  case CartaoPresente = 'GIFT_CARD';
  case CarteiraDigital = 'DIGITAL_WALLET';
  case Pix = 'PIX';
  case Outros = 'OTHER';
}

enum IfoodPedidoDetalhePagamentoTipos:string{
  case Online = 'ONLINE';
  case Offline = 'OFFLINE';
}

/**
 * @param string $Id Identificador único do pedido
 * @param int $Numero Id amigável para facilitar a identificação do pedido pela loja. Deve ser exibido na interface do seu aplicativo.
 * @param string $Tipo Tipo de pedido
 * @param string $Tempo Momento de entrega do pedido
 * @param string $Criado Data de criação do pedido
 * @param string $Preparacao Recomendação de início do preparo do pedido
 * @param bool $Teste Indica se é um pedido de teste ou não
 * @param bool $LojaId Identificador único da loja
 * @param bool $Loja Nome da loja
 * @param bool $Canal Canal de vendas pelo qual o pedido entra na plataforma (novos canais podem ser adicionados)
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details
 */
class IfoodPedidoDetalhe{
  public readonly string $Id;
  public readonly int $Numero;
  public readonly IfoodPedidoDetalheTipo $Tipo;
  public readonly int $Criado;
  public readonly int $Preparar;
  public readonly bool $Teste;
  public readonly string $LojaId;
  public readonly string $Loja;
  public readonly string $Canal;
  public readonly IfoodPedidoDetalheEntrega $Entrega;
  public readonly IfoodPedidoDetalheCliente $Cliente;
  public readonly IfoodPedidoDetalheTotal $Total;

  public function __construct(array $Data){
    $this->Id = $Data['id'];
    $this->Numero = $Data['displayId'];
    $this->Tipo = IfoodPedidoDetalheTipo::from($Data['orderType']);
    $this->Criado = strtotime($Data['createdAt']);
    $this->Preparar = strtotime($Data['preparationStartDateTime']);
    $this->Teste = $Data['isTest'];
    $this->LojaId = $Data['merchant']['id'];
    $this->Loja = $Data['merchant']['name'];
    $this->Canal = $Data['salesChannel'];
    $this->Entrega = new IfoodPedidoDetalheEntrega($Data['delivery']);
    $this->Cliente = new IfoodPedidoDetalheCliente($Data['customer']);
    $this->Total = new IfoodPedidoDetalheTotal($Data['total']);
  }
}

/**
 * @param IfoodPedidoDetalheEntregaModos $Modo Modo de entrega
 * @param IfoodPedidoDetalheEntregaPor $Por Responsável por fazer a entrega
 * @param string $Hora Data e horário da entrega
 * @param string $Obs Observações sobre a entrega
 * @param string $Completo Endereço formatado (Rua + Número)
 * @param string $Rua Nome da rua ou avenida
 * @param string $Num Número (Obs: pode conter letras)
 * @param string $Bairro Bairro ou setor
 * @param string $Complemento Complemento (Ex: Apartamento, Quadra, Lote)
 * @param string $Referencia Ponto de referência
 * @param string $Cep Código postal (CEP)
 * @param string $cidade Cidade
 * @param string $Estado Estado
 * @param string $Pais País
 * @param string $Latitude Latitude
 * @param string $Longitude Longitude
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details#delivery
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details#deliveryaddress
 */
class IfoodPedidoDetalheEntrega{
  public readonly IfoodPedidoDetalheEntregaModos $Modo;
  public readonly IfoodPedidoDetalheEntregaPor $Por;
  public readonly int $Hora;
  public readonly string $Obs;
  public readonly string $Completo;
  public readonly string $Rua;
  public readonly string $Num;
  public readonly string $Bairro;
  public readonly string|null $Complemento;
  public readonly string|null $Referencia;
  public readonly string $Cep;
  public readonly string $Cidade;
  public readonly string $Estado;
  public readonly string $Pais;
  public readonly string $Longitude;
  public readonly string $Latitude;

  public function __construct(array $Data){
    $this->Modo = IfoodPedidoDetalheEntregaModos::from($Data['mode']);
    $this->Por = IfoodPedidoDetalheEntregaPor::from($Data['deliveredBy']);
    $this->Hora = strtotime($Data['deliveryDateTime']);
    $this->Obs = $Data['observations'];
    $this->Rua = $Data['deliveryAddress']['streetName'];
    $this->Num = $Data['deliveryAddress']['streetNumber'];
    $this->Completo = $Data['deliveryAddress']['formattedAddress'];
    $this->Bairro = $Data['deliveryAddress']['neighborhood'];
    $this->Complemento = $Data['deliveryAddress']['complement'] ?? null;
    $this->Referencia = $Data['deliveryAddress']['reference'] ?? null;
    $this->Cep = $Data['deliveryAddress']['postalCode'];
    $this->Cidade = $Data['deliveryAddress']['city'];
    $this->Estado = $Data['deliveryAddress']['state'];
    $this->Pais = $Data['deliveryAddress']['country'];
    $this->Latitude = $Data['deliveryAddress']['coordinates']['latitude'];
    $this->Longitude = $Data['deliveryAddress']['coordinates']['longitude'];
  }
}

/**
 * @param string $Id Identificador único do cliente
 * @param string $Nome Nome do cliente
 * @param string $Tel Número de telefone do cliente ou do 0800 fornecido pelo iFood
 * @param string|null $Cpf número do documento do cliente (cpf) que deve ser utilizado somente para emissão de documento fiscal quando o cliente solicitar, pois o campo é opcional.
 * @param string $Localizador Código localizador que deve ser informado ao ligar para o número 0800
 * @param int $LocalizadorValidade Data de expiração do localizador do 0800
 * @param int|null $Contador Quantidade de pedidos já feito por esse cliente nessa loja. Campo opcional (eventualmente pode ser nulo).
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details#customer
 */
class IfoodPedidoDetalheCliente{
  public readonly string $Id;
  public readonly string $Nome;
  public readonly string $Tel;
  public readonly string|null $Cpf;
  public readonly string $Localizador;
  public readonly int $LocalizadorValidade;
  public readonly int|null $Contador;

  public function __construct(array $Data){
    $this->Id = $Data['id'];
    $this->Nome = $Data['name'];
    $this->Tel = $Data['phone']['number'];
    $this->Cpf = $Data['documentNumber'] ?? null;
    $this->Localizador = $Data['phone']['localizer'];
    $this->LocalizadorValidade = strtotime($Data['phone']['localizerExpiration']);
    $this->Contador = $Data['ordersCountOnMerchant'];
  }
}

/**
 * @param float $Sub Somatório do valor dos itens
 * @param float $Entrega Valor da taxa de entrega
 * @param float $Cupons Somatório dos cupons de desconto
 * @param float $Taxas Somatório das taxas adicionais
 * @param float $Total Valor total do pedido (total = sub + entrega + taxas - cupons)
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details#total
 */
class IfoodPedidoDetalheTotal{
  public readonly float $Sub;
  public readonly float $Entrega;
  public readonly float $Cupons;
  public readonly float $Taxas;
  public readonly float $Total;
  
  public function __construct(array $Data){
    $this->Sub = $Data['subTotal'];
    $this->Entrega = $Data['deliveryFee'];
    $this->Cupons = $Data['benefits'];
    $this->Taxas = $Data['additionalFees'];
    $this->Total = $Data['orderAmount'];
  }
}

/**
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details#payments
 */
class IfoodPedidoDetalhePagamento{
  public readonly int $Pago;
  public readonly int $Pendente;
  public array $Pag;

  public function __construct(array $Data){
    $this->Valor = $Data['prepaid'] * 100;
    $this->Valor = $Data['pending'] * 100;
    foreach($Data['methods'] as $metodo):
      $this->Pag[] = new IfoodPedidoDetalhePagamentoPag($metodo);
    endforeach;
  }
}

/**
 * @param int $Valor Valor do pagamento
 * @param string $Moeda Moeda
 * @param IfoodPedidoDetalhePagamentoMetodos $Metodo Método de pagamento
 * @param IfoodPedidoDetalhePagamentoTipos $Tipo Tipo de pagamento: pagamento já foi feito online pelo aplicativo e não deve ser cobrado na entrega ou pagamento deve ser feito no ato da entrega do pedido
 * @param string $Cartao Nome da bandeira do cartão
 * @param string|null $Carteira Nome da carteira digital
 * @param string $Troco Valor do troco
 * @param bool $Pago Se foi pago
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/details#payments
 */
class IfoodPedidoDetalhePagamentoPag{
  public readonly int $Valor;
  public readonly string $Moeda;
  public readonly IfoodPedidoDetalhePagamentoMetodos $Metodo;
  public readonly IfoodPedidoDetalhePagamentoTipos $Tipo;
  public readonly string $Cartao;
  public readonly string|null $Carteira;
  public readonly bool $Pago;
  public readonly int $Troco;

  public function __construct(array $Data){
    $this->Valor = $Data['value'];
    $this->Moeda = $Data['currency'];
    $this->Metodo = IfoodPedidoDetalhePagamentoMetodos::from($Data['method']);
    $this->Tipo = IfoodPedidoDetalhePagamentoTipos::from($Data['type']);
    $this->Cartao = $Data['card']['brand'];
    $this->Carteira = $Data['wallet']['name'];
    $this->Pago = $Data['prepaid'];
    $this->Troco = $Data['cash']['changeFor'];
  }
}
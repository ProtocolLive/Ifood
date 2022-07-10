<?php
//2022.07.10.00

enum IfoodModulos:string{
  case Autenticacao = 'authentication';
  case Lojas = 'merchant';
  case Pedidos = 'order';
}

/**
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/events#grupos-de-eventos
 */
enum IfoodPedidosGrupos:string{
  /**
   * Eventos relacionados ao cancelamento de um pedido
   */
  case Cancelamento = 'CANCELLATION_REQUEST';
  /**
   * Eventos relacionados à entrega de um pedido
   */
  case Entrega = 'DELIVERY';
  /**
   * Eventos relacionados à solicitação do serviço de entrega iFood sob demanda (modelo híbrido ou marketplace)
   */
  case EntregaDemanda = 'DELIVERY ON DEMAND';
  /**
   * Eventos relacionados a entrega complementar (quando o pedido entregue está incompleto)
   */
  case EntregaComplemento = 'DELIVERY_COMPLEMENT';
  case Outros = 'OUTROS';
  /**
   * Eventos relacionados à edição de pedidos
   */
  case PedidoEditar = 'ORDER_MODIFIER';
  /**
   * Eventos relacionados à retirada de pedidos
   */
  case Retirada = 'ORDER_TAKEOUT';
  /**
   * Eventos que representam uma mudança no status do pedido
   */
  case Status = 'ORDER_STATUS';
}

/**
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/events#order_status
 */
enum IfoodPedidosCodigoStatus:string{
  /**
   * Pedido foi Cancelado
   */
  case Cancelado = 'CANCELLED';
  /**
   * Solicitação de cancelamento feita pela loja ou pelo iFood (atendimento ao cliente)
   */
  case Cancelamento = 'CANCELLATION_REQUESTED';
  /**
   * Pedido foi concluído
   */
  case Concluido = 'CONCLUDED';
  /**
   * Indica que o pedido saiu para entrega (Delivery)
   */
  case Enviado = 'DISPATCHED';
  /**
   * Novo Pedido na plataforma
   */
  case Novo = 'PLACED';
  /**
   * Indica que o pedido está pronto para ser retirado (Pra Retirar)
   */
  case ProntoRetirada = 'READY_TO_PICKUP';
  /**
   * Pedido foi confirmado e será preparado
   */
  case Recebido = 'CONFIRMED';
}

/**
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/events#order_status
 */
enum IfoodPedidosCodigoStatus2:string{
  /**
   * Pedido foi Cancelado
   */
  case Cancelado = 'CAN';
  /**
   * Pedido foi concluído
   */
  case Concluido = 'CON';
  /**
   * Indica que o pedido saiu para entrega (Delivery)
   */
  case Enviado = 'DSP';
  /**
   * Novo Pedido na plataforma
   */
  case Novo = 'PLC';
  /**
   * Indica que o pedido está pronto para ser retirado (Pra Retirar)
   */
  case ProntoRetirada = 'RTP';
  /**
   * Pedido foi confirmado e será preparado
   */
  case Recebido = 'CFM';
}

/**
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/events#cancellation_request
 */
enum IfoodPedidosCodigoCancelado:string{
  /**
   * Solicitação de cancelamento feita pelo cliente
   */
  case CancelarCliente = 'CONSUMER_CANCELLATION_REQUESTED';
  /**
   * A solicitação de cancelamento feita pelo cliente foi negada pelo Merchant (loja)
   */
  case CancelarClienteNao = 'CONSUMER_CANCELLATION_DENIED';
  /**
   * A solicitação de cancelamento feita pelo cliente foi aprovada pelo Merchant (loja)
   */
  case CancelarClienteOk = 'CONSUMER_CANCELLATION_ACCEPTED';
  /**
   * Solicitação de cancelamento negada
   */
  case CancelarFalha = 'CANCELLATION_REQUEST_FAILED';
  /**
   * Solicitação de cancelamento feita pelo Merchant (loja) ou pelo iFood (atendimento ao cliente)
   */
  case CancelarLoja = 'CANCELLATION_REQUESTED';
}

/**
 * @link https://developer.ifood.com.br/pt-BR/docs/guides/order/events#cancellation_request
 */
enum IfoodPedidosCodigoCancelado2:string{
  /**
   * Solicitação de cancelamento feita pelo cliente
   */
  case CancelarCliente = 'CCR';
  /**
   * A solicitação de cancelamento feita pelo cliente foi negada pelo Merchant (loja)
   */
  case CancelarClienteNao = 'CCD';
  /**
   * A solicitação de cancelamento feita pelo cliente foi aprovada pelo Merchant (loja)
   */
  case CancelarClienteOk = 'CCA';
  /**
   * Solicitação de cancelamento negada
   */
  case CancelarFalha = 'CARF';
  /**
   * Solicitação de cancelamento feita pelo Merchant (loja) ou pelo iFood (atendimento ao cliente)
   */
  case CancelarLoja = 'CAR';
}
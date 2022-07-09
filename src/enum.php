<?php
//2022.07.09.01

enum IfoodModulos:string{
  case Autenticacao = 'authentication';
  case Lojas = 'merchant';
  case Pedidos = 'order';
}

enum IfoodPedidosGrupos:string{
  case Status = 'ORDER_STATUS';
  case Cancelamento = 'CANCELLATION_REQUEST';
  case Retirada = 'ORDER_TAKEOUT';
  case Entrega = 'DELIVERY';
  case EntregaDemanda = 'DELIVERY ON DEMAND';
  case EntregaComplemento = 'DELIVERY_COMPLEMENT';
  case PedidoEditar = 'ORDER_MODIFIER';
  case Outros = 'OUTROS';
}

enum IfoodPedidosCodigoStatus:string{
  case Novo = 'PLACED';
  case Recebido = 'CONFIRMED';
  case ProntoRetirada = 'READY_TO_PICKUP';
  case Enviado = 'DISPATCHED';
  case Concluido = 'CONCLUDED';
  case Cancelado = 'CANCELLED';
}

enum IfoodPedidosCodigoStatus2:string{
  case Novo = 'PLC';
  case Confirmado = 'CFM';
  case ProntoRetirada = 'RTP';
  case Enviado = 'DSP';
  case Concluido = 'CON';
  case Cancelado = 'CAN';
}

enum IfoodPedidosCodigoCancelado:string{
  case CancelarLoja = 'CANCELLATION_REQUESTED';
  case CancelarFalha = 'CANCELLATION_REQUEST_FAILED';
  case CancelarCliente = 'CONSUMER_CANCELLATION_REQUESTED';
  case CancelarClienteOk = 'CONSUMER_CANCELLATION_ACCEPTED';
  case CancelarClienteNao = 'CONSUMER_CANCELLATION_DENIED';
}

enum IfoodPedidosCodigoCancelado2:string{
  case CancelarLoja = 'CAR';
  case CancelarFalha = 'CARF';
  case CancelarCliente = 'CCR';
  case CancelarClienteOk = 'CCA';
  case CancelarClienteNao = 'CCD';
}
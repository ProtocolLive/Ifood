<?php
//2022.07.11.00

require(__DIR__ . '/requires.php');

class Ifood extends IfoodBasics{
  public readonly string|null $Token;
  public readonly int|null $TokenValidade;

  public function __construct(
    private string|null $Id = null,
    private string|null $Chave = null,
    string $Token = null,
    int $TokenValidade = null,
    string $Log = null,
    string $CurlCert = null
  ){
    if($Token !== null):
      $this->Token = $Token;
      $this->TokenValidade = $TokenValidade;
    endif;
    $this->Log = $Log;
    $this->CurlCert = $CurlCert;
  }

  public function Autenticar():bool{
    $get['grantType'] = 'client_credentials';
    $get['clientId'] = $this->Id;
    $get['clientSecret'] = $this->Chave;
    $url = self::Url . IfoodModulos::Autenticacao->value . self::Versao . 'oauth/token';
    $url .= '?' . http_build_query($get);
    $curl = $this->CurlFactory($url, true);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    if($return === null):
      return false;
    endif;
    $this->Token = $return['accessToken'];
    $this->TokenValidade = strtotime('+' . $return['expiresIn'] . ' seconds');
    return true;
  }

  public function Lojas(
    int $Pagina = null,
    int $Quantidade = null
  ){
    $url = self::Url . IfoodModulos::Lojas->value . self::Versao . 'merchants';
    $get = [];
    if($Pagina !== null):
      $get['page'] .= $Pagina;
    endif;
    if($Quantidade !== null):
      $get['size'] .= $Quantidade;
    endif;
    if($get !== []):
      $url .= '?' . http_build_query($get);
    endif;
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, false);
    $return = $this->CurlRun($curl);
    foreach($return as &$loja):
      $loja = new IfoodLoja(
        $loja['id'],
        $loja['name'],
        $loja['corporateName']
      );
    endforeach;
    return $return;
  }

  public function Pedidos(
    IfoodPedidosFiltroGrupo $Grupos = null,
    IfoodPedidosFiltroTipo $Tipos = null,
    array $Lojas = null
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . 'events:polling';
    $get = [];
    $header = [];
  
    if($Grupos !== null
    and ($temp = $Grupos->Get()) !== ''):
      $get['groups'] = $temp;
    endif;
    if($Tipos !== null
    and ($temp = $Tipos->Get()) !== ''):
      $get['types'] = $temp;
    endif;
    if($get !== []):
      $url .= '?' . http_build_query($get);
    endif;

    if($Lojas !== null):
      $header = ['x-polling-merchants' => implode(',', $Lojas)];
    endif;

    $curl = $this->CurlFactory($url, false, $header);
    curl_setopt($curl, CURLOPT_POST, false);
    $response = $this->CurlRun($curl);
    $return = [];
    foreach($response as $pedido):
      $return[$pedido['orderId']] = new IfoodPedidoResumo(
        $pedido['orderId'],
        IfoodPedidosStatus::from($pedido['fullCode']),
        $pedido['merchantId'],
        $pedido['createdAt'],
        $pedido['metadata'] ?? null
      );
    endforeach;
    return array_reverse($return);
  }

  public function Pedido(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . 'orders/' . $Id;
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, false);
    $return = $this->CurlRun($curl);
    return new IfoodPedidoDetalhe($return);
  }

  public function PedidoConfirma(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/confirm";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    return $return;
  }

  public function PedidoPreparando(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/startPreparation";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    return $return;
  }

  public function PedidoPronto(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/readyToPickup";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    return $return;
  }

  public function PedidoEnviado(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/dispatch";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    return $return;
  }

  public function PedidoCancelar(
    string $Id,
    IfoodPedidoCancelamento $Codigo,
    string $Motivo
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/requestCancellation";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, [
      'reason' => $Motivo,
      'cancellationCode' => $Codigo->value
    ]);
    $return = $this->CurlRun($curl);
    return $return;
  }

  public function PedidoCancelamentoAceitar(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/acceptCancellation";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    return $return;
  }

  public function PedidoCancelamentoNegar(
    string $Id
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . "orders/$Id/denyCancellation";
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, true);
    $return = $this->CurlRun($curl);
    return $return;
  }
}
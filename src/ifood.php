<?php
//2022.07.09.01

require(__DIR__ . '/basics.php');
require(__DIR__ . '/enum.php');
require(__DIR__ . '/objetos.php');
require(__DIR__ . '/objetos/pedido.php');

class Ifood extends IfoodBasics{
  public readonly string|null $Token;
  public readonly int|null $TokenValidade;

  public function __construct(
    private string|null $Id = null,
    private string|null $Chave = null,
    string $Token = null,
    int $TokenValidade = null,
    bool $Log = false,
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
    $return = curl_exec($curl);
    if($return === false):
      $this->Erro = IfoodErros::Curl;
      $this->ErroStr = curl_error($curl);
      $this->Log('Erro no cURL: ' . $this->ErroStr . PHP_EOL);
      return false;
    endif;
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($code !== 200):
      return false;
    endif;
    $return = json_decode($return, true);
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
    IfoodPedidosFiltroTipo $Tipos = null
  ){
    $url = self::Url . IfoodModulos::Pedidos->value . self::Versao . 'events:polling';
    $get = [];
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
    $curl = $this->CurlFactory($url);
    curl_setopt($curl, CURLOPT_POST, false);
    $response = $this->CurlRun($curl);
    $return = [];
    foreach($response as $pedido):
      $return[$pedido['orderId']] = new IfoodPedidoResumo(
        $pedido['orderId'],
        IfoodPedidosCodigoStatus::from($pedido['fullCode']),
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
}
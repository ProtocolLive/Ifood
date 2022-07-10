<?php
//2022.07.10.04

abstract class IfoodBasics{
  protected const Url = 'https://merchant-api.ifood.com.br/';
  protected const Versao = '/v1.0/';
  protected string|null $CurlCert;
  protected string $Log;
  public IfoodErros $Erro;
  public string|null $ErroStr = null;

  protected function CurlRun(
    CurlHandle $Curl
  ):mixed{
    $return = curl_exec($Curl);
    if($return === false):
      $this->Erro = IfoodErros::Curl;
      $this->ErroStr = curl_error($Curl);
      $this->Log('Erro no cURL: ' . $this->ErroStr . PHP_EOL);
      return null;
    endif;
    $code = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
    $this->Log('Retorno: ' . $code . PHP_EOL . $return . PHP_EOL);
    if($code === 200):
      return json_decode($return, true);
    endif;
    if($code === 202):
      return true;
    endif;
    if($code === 204):
      return [];
    endif;
    if($code === 401):
      $temp = json_decode($return, true);
      $this->Erro = IfoodErros::Token;
      $this->ErroStr = $temp['error_description'];
    endif;
    if($code === 400 or $code === 500):
      $temp = json_decode($return, true);
      $this->Erro = IfoodErros::Servidor;
      $this->ErroStr = $temp['error']['message'];
    endif;
    return null;
  }

  protected function CurlFactory(
    string $Url,
    bool $Autenticacao = false,
  ):CurlHandle|false{
    $header = [
      'Accept: application/json',
    ];
    if($Autenticacao):
      $header[] = 'Content-Type: application/json';
    else:
      $header[] = 'Authorization: Bearer ' . $this->Token;
    endif;
    $this->Log('URL: ' . $Url);
    $curl = curl_init($Url);
    if(is_file($this->CurlCert)):
      curl_setopt($curl, CURLOPT_CAINFO, $this->CurlCert);
    else:
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    endif;
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    return $curl;
  }

  protected function Log(string $Msg):void{
    if($this->Log === null):
      return;
    endif;
    if(is_dir($this->Log) === false):
      mkdir($this->Log, 0777, true);
    endif;
    file_put_contents($this->Log . '/ifood.log', $Msg . PHP_EOL, FILE_APPEND);
  }
}
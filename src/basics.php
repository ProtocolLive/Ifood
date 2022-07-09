<?php
//2022.07.09.01

abstract class IfoodBasics{
  protected const Url = 'https://merchant-api.ifood.com.br/';
  protected const Versao = '/v1.0/';
  protected string|null $CurlCert;
  protected bool $Log;
  public IfoodErros $Erro;
  public string $ErroStr;

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
    if($code === 204):
      return [];
    endif;

  }

  protected function CurlFactory(
    string $Url,
    bool $Autenticacao = false,
  ):CurlHandle|false{
    $header = [
      'Accept: application/json',
      'Content-Type: application/x-www-form-urlencoded'
    ];
    if($Autenticacao === false):
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
    if($this->Log):
      file_put_contents(__DIR__ . '/ifood.log', $Msg . PHP_EOL, FILE_APPEND);
    endif;
  }
}
<?php
//2022.07.11.01

class IfoodPedidoResumo{
  public readonly string $Id;
  public readonly IfoodPedidosStatus $Status;
  public readonly string $Loja;
  public readonly string $Criacao;
  public readonly array|null $Dados;

  public function __construct(
    string $Id,
    IfoodPedidosStatus $Status,
    string $Loja,
    string $Criacao,
    array $Dados = null
  ){
    $this->Id = $Id;
    $this->Status = $Status;
    $this->Loja = $Loja;
    $this->Criacao = strtotime($Criacao);
    $this->Dados = $Dados;
  }
}

class IfoodPedidosFiltroGrupo{
  private array $Filtro = [];

  public function Add(
    IfoodPedidosGrupos $Grupo
  ){
    $this->Filtro[] = $Grupo->value;
  }

  public function Get():string{
    return implode(',', $this->Filtro);
  }
}

class IfoodPedidosFiltroTipo{
  private array $Filtro = [];

  public function Add(
    IfoodPedidosStatus2|IfoodPedidosStatusCancelado2 $Tipo
  ){
    $this->Filtro[] = $Tipo->value;
  }

  public function Get():string{
    return implode(',', $this->Filtro);
  }
}
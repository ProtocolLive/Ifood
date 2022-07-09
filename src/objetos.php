<?php
//2022.07.09.00

class IfoodLoja{
  public function __construct(
    public readonly string $Id,
    public readonly string $Nome,
    public readonly string $Nome2
  ){}
}


class IfoodPedidoResumo{
  public readonly string $Id;
  public readonly IfoodPedidosCodigoStatus $Codigo;
  public readonly string $Loja;
  public readonly string $Criacao;
  public readonly array|null $Dados;

  public function __construct(
    string $Id,
    IfoodPedidosCodigoStatus $Codigo,
    string $Loja,
    string $Criacao,
    array $Dados = null
  ){
    $this->Id = $Id;
    $this->Codigo = $Codigo;
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
    IfoodPedidosCodigoStatus2|IfoodPedidosCodigoCancelado2 $Tipo
  ){
    $this->Filtro[] = $Tipo->value;
  }

  public function Get():string{
    return implode(',', $this->Filtro);
  }
}
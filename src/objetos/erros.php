<?php
//2022.07.09.01

enum IfoodErros{
  case Curl;
  case TokenExpirado;
  case TokenSem;
  case Servidor;
}
Aplicativo symfony/console, de blueprint mínimo, apenas para exemplo.

*Não usar em produção*

Instalação:
-----------

- clonar este repositório;
- em ambiente Linux, garanta que a pasta `var` neste diretório tem permissões de leitura e escrita;
- instalar dependencias utilizando o comando `composer install` ([https://getcomposer.org/]);
- configure suas propriedades do projeto, segurança e banco de dados no arquivo `.env`.

Execução:
---------

Em ambiente Linux é necessário dar permissão de execução ao arquivo `bin` na raiz do projeto.

Para visualizar os comandos disponíveis, incluindo comandos nativos do Symfony e Doctrine, execute:
```
php bin list
```

Este projeto traz apenas o comando `product-list` sob o namespace `sample`:
```
php bin sample:product-list
```

Utilizando a opção `--output` (ou `-o`) é possível direcionar a saída para arquivo CSV;
```
php bin sample:product-list --output=/path/to/file.csv
```

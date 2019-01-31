Aplicativo symfony/console, de blueprint m�nimo, apenas para exemplo.

*N�o usar em produ��o*

Instala��o:
-----------

- clonar este reposit�rio;
- em ambiente Linux, garanta que a pasta `var` neste diret�rio tem permiss�es de leitura e escrita;
- instalar dependencias utilizando o comando `composer install` ([https://getcomposer.org/]);
- configure suas propriedades do projeto, seguran�a e banco de dados no arquivo `.env`.

Execu��o:
---------

Em ambiente Linux � necess�rio dar permiss�o de execu��o ao arquivo `bin` na raiz do projeto.

Para visualizar os comandos dispon�veis, incluindo comandos nativos do Symfony e Doctrine, execute:
```
php bin list
```

Este projeto traz apenas o comando `product-list` sob o namespace `sample`:
```
php bin sample:product-list
```

Utilizando a op��o `--output` (ou `-o`) � poss�vel direcionar a sa�da para arquivo CSV;
```
php bin sample:product-list --output=/path/to/file.csv
```

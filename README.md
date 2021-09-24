<h1 align="center">ricardokovalski/installments-console</h1>

<p align="center">
    <strong>Uma aplicação PHP para calcular juros de parcelamentos utilizando a biblioteca ricardokovalski/installments-calculator.</strong>
</p>

<p align="center">
    <a href="https://github.com/ricardokovalski/installments-console"><img src="http://img.shields.io/badge/source-ricardokovalski/installments--console-blue.svg" alt="Source Code"></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/php-%3E=5.6-777bb3.svg" alt="PHP Programming Language"></a>
    <a href="https://github.com/ricardokovalski/installments-console/releases"><img src="https://img.shields.io/github/release/ricardokovalski/installments-console.svg" alt="Source Code"></a>
    <a href="https://github.com/ricardokovalski"><img src="http://img.shields.io/badge/author-@ricardokovalski-blue.svg" alt="Author"></a>
    <a href="https://github.com/ricardokovalski/installments-console/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg" alt="Read License"></a>
</p>

<h1>Sobre</h1>

ricardokovalski/installments-console é uma aplicação PHP que serve para calcular juros de parcelamentos utilizando a biblioteca ricardokovalski/installments-calculator.

<h1>Instalação</h1>

Instale este pacote como uma dependência usando [Composer](https://getcomposer.org).

```bash
composer require ricardokovalski/installments-console
```

<h1>Exemplos</h1>

Se instalado em sua aplicação, você pode executar o aplicativo de console a partir da linha de comando:

```bash
$ ./vendor/bin/installments
```

Se instalado globalmente usando o Composer, certifique-se de que a instalação global do Composer esteja em seu PATH(geralmente é em algum lugar ~/.composer/vendor/bin). Então, você pode executá-lo:

```bash
$ installments
```

Esteja ciente de que alguns sistemas podem já ter um aplicativo de linha de comando chamado interest instalado, portanto, isso pode criar um conflito se algo usando seu PATH esperar a outra interest ferramenta.

Veja a seguir a estrutura de argumentos para o comando.

Primeiro argumento do command é totalPurchase e logo na sequência passamos as opções.

```
--typeInterest (ex.: Financial, Compound ou Simple)
```

```
--interestValue (Type Double)
```

```
--limitValueInstallment (Type Double)
```

```
--numberMaxInstallments (Type Int)
```

```
--monetaryFormatterConfig
```

```
--currencyIsoCodes (ex.: usd ou brl)
```

```
--locale (ex.: en_us ou pt_br)
```

```
--fractionDigits (Type Int)
```

```
--monetaryFormatter (ex.: IntlCurrency, IntlDecimal, Decimal)
```

Exemplo:

```bash
$ ./vendor/bin/installments calculate 343.90
            --typeInterest=Financial
            --interestValue=2.99
            --limitValueInstallment=10.09
            --monetaryFormatterConfig
            --currencyIsoCodes=usd
            --locale=en_us
            --fractionDigits=3
            --monetaryFormatter=IntlCurrency
```

Para obter ajuda, basta digitar ./vendor/bin/installments e ler as informações de ajuda.

## Copyright and License

The ricardokovalski/installments-console library is copyright © [Ricardo Kovalski](https://github.com/ricardokovalski)
and licensed for use under the terms of the
MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

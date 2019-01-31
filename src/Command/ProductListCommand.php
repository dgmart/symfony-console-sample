<?php

namespace Microservices\Sample\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\Query;
use Microservices\Sample\Entity\Product;

/**
 * Lista produtos cadastrados em tela ou arquivo
 */
class ProductListCommand extends Command
{
    /**
     * @property string Comando a ser invocado no terminal
     */
    protected static $defaultName = 'sample:product-list';

    /**
     * @property Doctrine\Bundle\DoctrineBundle\Registry Gerencia conexoes com base de dados
     */
    private $doctrine;

    /**
     * Construtor/dependencias da classe
     */
    public function __construct(ContainerInterface $container)
    {
        $this->doctrine = $container->get('doctrine');
        parent::__construct();
    }

    /**
     * Definicao de opcoes e parametros para o comando
     */
    protected function configure()
    {
        $this
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Espeficar arquivo de saida que sera gerado em formato CSV.')
            ->setDescription('Lista produtos cadastrados.')
            ->setHelp('Permite visualizar e/ou exportar produtos cadastrados.
Use a opcao --output para especificar o arquivo de saída que será gerado em formato CSV.

    php %command.full_name% --output=/path/to/file.csv
')
        ;
    }

    /**
     * Acao a ser executada
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Listando produtos do cadastro');

        $qry = $this->doctrine->getManager()->createQuery(
            'SELECT p, f FROM Microservices\Sample\Entity\Product p JOIN p.family f');

        $output = $input->getOption('output');
        if ($output) {
            return $this->gerarCsv($output, $qry, $io);
        }

        return $this->gerarEmTela($qry, $io);
    }

    /**
     * Helper para limitar e exibir registros em tela
     */
    private function gerarEmTela(Query $qry, SymfonyStyle $io)
    {
        $qry->setMaxResults(10);
        $lista = array();
        foreach ($qry->iterate() as $item) {
            $lista[] = $this->productToArray($item[0]);
        }

        $io->table(['ID', 'Descricao', 'Familia'], $lista);

        if (10 <= count($lista)) {
            $io->newLine();
            $io->text('Limite de 10 itens atingido.');
            $io->text('Exporte para arquivo CSV com a opcao --output para visualizar lista completa.');
        }

        return 0;
    }

    /**
     * Helper para extrair dados para arquivo
     */
    private function gerarCsv(string $output, Query $qry, SymfonyStyle $io)
    {
        if (file_exists($output)) {
            if (!$io->confirm('O arquivo informado existe e sera sobrescrito. Deseja continuar?', false)) {
                return 0;
            }
        }

        $q = @fopen($output, 'w');
        if (!$q) {
            $io->error('Nao foi possivel criar o arquivo informado.');
        }

        $i = 0;
        foreach ($qry->iterate() as $item) {
            fputcsv($q, $this->productToArray($item[0]), ';', '"', '\\');
            $i++;
        }
        fclose($q);

        $io->success($i . ' produtos foram exportados com sucesso.');

        return 0;
    }

    /**
     * Helper converte obj Product para lista mais apropriada a ser exportada
     */
    private function productToArray(Product $p)
    {
        return [
            $p->getId(),
            $p->getDescription(),
            $p->getFamily()->getDescription()
        ];
    }
}

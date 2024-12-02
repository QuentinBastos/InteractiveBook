<?php

namespace App\Command;

use App\Entity\Book\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ImportTypesCommand extends Command
{
    protected static $defaultName = 'app:import-types';

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName) // Ensure the command name is set
            ->setDescription('Imports types from a YAML file.')
            ->setHelp('This command allows you to import types from a YAML file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = __DIR__ . '/../../config/import/types.yaml';
        $data = Yaml::parseFile($filePath);

        foreach ($data['types'] as $typeData) {
            $type = new Type();
            $type->setName($typeData['name']);
            $this->em->persist($type);
        }

        $this->em->flush();

        $output->writeln('Types imported successfully.');

        return Command::SUCCESS;
    }
}
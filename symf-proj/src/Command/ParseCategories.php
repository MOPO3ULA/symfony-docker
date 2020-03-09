<?php


namespace App\Command;


use App\Repository\CategoryRepository;
use App\Service\CategoriesParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCategories extends Command
{
    /**
     * @var CategoriesParser $categoriesParser
     */
    private CategoriesParser $categoriesParser;

    /**
     * @var CategoryRepository $categoryRepository
     */
    private CategoryRepository $categoryRepository;

    public function __construct(CategoriesParser $categoriesParser, CategoryRepository $categoryRepository)
    {
        $this->categoriesParser = $categoriesParser;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('parse:categories')
            ->setDescription('Парсим и записываем категории');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $categories = $this->categoryRepository->findAll();
        if (count($categories) > 0) {
            $output->writeln('Categories table is not empty!');
        } else {
            $this->categoriesParser->run();
            $output->writeln('Parsing and saving categories completed!');
        }
    }
}